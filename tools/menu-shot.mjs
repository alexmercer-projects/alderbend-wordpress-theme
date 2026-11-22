// Phone screenshot with the navigation overlay open. Usage: node tools/menu-shot.mjs <url> <out.png>
import { spawn } from 'node:child_process';
import { writeFileSync } from 'node:fs';
const [url, outFile] = process.argv.slice(2);
const port = 9600 + Math.floor(Math.random() * 90);
const chrome = spawn('google-chrome', ['--headless=new', '--no-sandbox', '--disable-gpu', `--remote-debugging-port=${port}`, `--user-data-dir=/tmp/ab-ms-${port}`, 'about:blank'], { stdio: 'ignore' });
const sleep = ms => new Promise(r => setTimeout(r, ms));
let ws; for (let i = 0; i < 60 && !ws; i++) { try { const t = await (await fetch(`http://127.0.0.1:${port}/json`)).json(); const p = t.find(x => x.type === 'page'); if (p) ws = p.webSocketDebuggerUrl; } catch {} await sleep(150); }
const sock = new WebSocket(ws); await new Promise(r => sock.onopen = r);
let id = 0; const pend = new Map(); sock.onmessage = m => { const d = JSON.parse(m.data); if (d.id && pend.has(d.id)) { pend.get(d.id)(d.result || d.error); pend.delete(d.id); } };
const send = (method, params = {}) => new Promise(r => { const i = ++id; pend.set(i, r); sock.send(JSON.stringify({ id: i, method, params })); });
await send('Page.enable'); await send('Runtime.enable');
await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 2, mobile: true });
await send('Page.navigate', { url }); await sleep(1800);
await send('Runtime.evaluate', { expression: `document.querySelector('.wp-block-navigation__responsive-container-open').click()` }); await sleep(700);
const shot = await send('Page.captureScreenshot', { format: 'png' });
writeFileSync(outFile, Buffer.from(shot.data, 'base64')); sock.close(); chrome.kill();
