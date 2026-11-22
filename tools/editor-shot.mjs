// Screenshot of a page open in the real block editor (throwaway local admin account).
// Usage: node tools/editor-shot.mjs <post-id> <out.png> [site-url]   (Node 22+)
import { spawn } from 'node:child_process';
import { writeFileSync } from 'node:fs';
const [postId, outFile, site = 'http://localhost:8080'] = process.argv.slice(2);
const port = 9500 + Math.floor(Math.random() * 150);
const chrome = spawn('google-chrome', ['--headless=new', '--no-sandbox', '--disable-gpu', '--hide-scrollbars', `--remote-debugging-port=${port}`, `--user-data-dir=/tmp/ab-es-${port}`, 'about:blank'], { stdio: 'ignore' });
const sleep = ms => new Promise(r => setTimeout(r, ms));
let ws; for (let i = 0; i < 60 && !ws; i++) { try { const t = await (await fetch(`http://127.0.0.1:${port}/json`)).json(); const p = t.find(x => x.type === 'page'); if (p) ws = p.webSocketDebuggerUrl; } catch {} await sleep(150); }
const sock = new WebSocket(ws); await new Promise(r => sock.onopen = r);
let id = 0; const pend = new Map();
sock.onmessage = m => { const d = JSON.parse(m.data); if (d.id && pend.has(d.id)) { pend.get(d.id)(d.result || d.error); pend.delete(d.id); } };
const send = (method, params = {}) => new Promise(r => { const i = ++id; pend.set(i, r); sock.send(JSON.stringify({ id: i, method, params })); });
const ev = async e => (await send('Runtime.evaluate', { expression: e, returnByValue: true, awaitPromise: true })).result?.value;
await send('Page.enable'); await send('Runtime.enable');
await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 900, deviceScaleFactor: 1, mobile: false });
await send('Page.navigate', { url: site + '/wp-login.php' }); await sleep(1500);
await ev(`document.getElementById('user_login').value='admin';document.getElementById('user_pass').value='pw';document.getElementById('wp-submit').click()`); await sleep(3000);
await send('Page.navigate', { url: `${site}/wp-admin/post.php?post=${postId}&action=edit` }); await sleep(8000);
await ev(`wp.data.dispatch('core/preferences').set('core/edit-post','welcomeGuide',false); wp.data.dispatch('core/preferences').set('core/edit-post','fullscreenMode',false); wp.data.dispatch('core/preferences').set('core','welcomeGuide',false); wp.data.dispatch('core/editor').setDeviceType && 0`);
await send('Page.reload'); await sleep(8000);
await ev(`(()=>{const b=wp.data.select('core/block-editor').getBlocks();const q=(function f(bs){for(const x of bs){if(x.name==='alderbend/quote-form')return x;const r=f(x.innerBlocks);if(r)return r}})(b); if(q) wp.data.dispatch('core/block-editor').selectBlock(q.clientId)})()`); await sleep(3000);
const shot = await send('Page.captureScreenshot', { format: 'png' });
writeFileSync(outFile, Buffer.from(shot.data, 'base64')); sock.close(); chrome.kill();
