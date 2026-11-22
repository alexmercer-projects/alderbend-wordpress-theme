// Full-page screenshots plus horizontal-overflow, console-error and outside-request checks in headless Chrome
// over the DevTools protocol (Node 22+, no packages).
// Usage: node tools/shoot.mjs <outdir> <name=url> [name=url ...]   (each URL is shot at 1440 and 390 wide)
import { spawn } from 'node:child_process';
import { writeFileSync, mkdirSync } from 'node:fs';
const [out, ...pages] = process.argv.slice(2);
mkdirSync(out, { recursive: true });
const port = 9333 + Math.floor(Math.random() * 500);
const chrome = spawn('google-chrome', ['--headless=new', '--no-sandbox', '--disable-gpu', '--hide-scrollbars', `--remote-debugging-port=${port}`, `--user-data-dir=/tmp/ab-chrome-${port}`, 'about:blank'], { stdio: 'ignore' });
const sleep = ms => new Promise(r => setTimeout(r, ms));
let ws;
for (let i = 0; i < 60 && !ws; i++) { try { const t = await (await fetch(`http://127.0.0.1:${port}/json`)).json(); const p = t.find(x => x.type === 'page'); if (p) ws = p.webSocketDebuggerUrl; } catch {} await sleep(150); }
const sock = new WebSocket(ws); await new Promise(r => sock.onopen = r);
let id = 0; const pend = new Map(); let errors = []; let origin = '';
sock.onmessage = m => {
  const d = JSON.parse(m.data);
  if (d.id && pend.has(d.id)) { pend.get(d.id)(d.result || d.error); pend.delete(d.id); }
  if (d.method === 'Runtime.exceptionThrown') errors.push(d.params.exceptionDetails.exception?.description || d.params.exceptionDetails.text);
  if (d.method === 'Runtime.consoleAPICalled' && d.params.type === 'error') errors.push(d.params.args.map(a => a.value || a.description).join(' '));
  if (d.method === 'Network.requestWillBeSent' && origin && !d.params.request.url.startsWith(origin) && !/^(data|blob):/.test(d.params.request.url)) errors.push('outside request: ' + d.params.request.url);
  if (d.method === 'Network.responseReceived' && d.params.response.status >= 400) errors.push(d.params.response.status + ' ' + d.params.response.url);
};
const send = (method, params = {}) => new Promise(r => { const i = ++id; pend.set(i, r); sock.send(JSON.stringify({ id: i, method, params })); });
const ev = async e => (await send('Runtime.evaluate', { expression: e, returnByValue: true, awaitPromise: true })).result.value;
await send('Page.enable'); await send('Runtime.enable'); await send('Network.enable');
const report = [];
for (const spec of pages) {
  const [name, url] = [spec.slice(0, spec.indexOf('=')), spec.slice(spec.indexOf('=') + 1)];
  origin = new URL(url).origin;
  for (const [label, width, mobile] of [['1440', 1440, false], ['390', 390, true]]) {
    errors = [];
    await send('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile });
    await send('Page.navigate', { url });
    await sleep(1500);
    await ev(`(async () => { for (let y = 0; y < document.documentElement.scrollHeight; y += 500) { scrollTo(0, y); await new Promise(r => setTimeout(r, 120)); } scrollTo(0, 0); await document.fonts.ready; await new Promise(r => setTimeout(r, 400)); return true; })()`);
    const m = await ev(`({ sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth, h: document.documentElement.scrollHeight,
      wide: [...document.querySelectorAll('body *')].filter(e => { const r = e.getBoundingClientRect(); return r.width && r.right > document.documentElement.clientWidth + 1 && getComputedStyle(e).position !== 'fixed' && !e.closest('.ab-quote__trap'); }).slice(0, 5).map(e => e.tagName + '.' + e.className) })`);
    const shot = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true, clip: { x: 0, y: 0, width, height: Math.min(m.h, 9000), scale: 1 } });
    writeFileSync(`${out}/${name}-${label}.png`, Buffer.from(shot.data, 'base64'));
    report.push({ page: name, width, scrollWidth: m.sw, clientWidth: m.cw, overflow: m.sw > m.cw, wideElements: m.wide, height: m.h, errors });
  }
}
console.log(JSON.stringify(report, null, 1));
sock.close(); chrome.kill();
