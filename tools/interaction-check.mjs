// Clicks through the interactive parts of the exported site in headless Chrome: mobile menu, town checker, demo form.
// Usage: node tools/interaction-check.mjs http://localhost:8091/alderbend-wordpress-theme/site/   (Node 22+)
import { spawn } from 'node:child_process';
const base = process.argv[2];
const port = 9900 + Math.floor(Math.random() * 90);
const chrome = spawn('google-chrome', ['--headless=new', '--no-sandbox', '--disable-gpu', `--remote-debugging-port=${port}`, `--user-data-dir=/tmp/ab-int-${port}`, 'about:blank'], { stdio: 'ignore' });
const sleep = ms => new Promise(r => setTimeout(r, ms));
let ws; for (let i = 0; i < 60 && !ws; i++) { try { const t = await (await fetch(`http://127.0.0.1:${port}/json`)).json(); const p = t.find(x => x.type === 'page'); if (p) ws = p.webSocketDebuggerUrl; } catch {} await sleep(150); }
const sock = new WebSocket(ws); await new Promise(r => sock.onopen = r);
let id = 0; const pend = new Map(); const errors = [];
sock.onmessage = m => { const d = JSON.parse(m.data); if (d.id && pend.has(d.id)) { pend.get(d.id)(d.result || d.error); pend.delete(d.id); } if (d.method === 'Runtime.exceptionThrown') errors.push(d.params.exceptionDetails.exception?.description || d.params.exceptionDetails.text); if (d.method === 'Runtime.consoleAPICalled' && d.params.type === 'error') errors.push(d.params.args.map(a => a.value || a.description).join(' ')); };
const send = (method, params = {}) => new Promise(r => { const i = ++id; pend.set(i, r); sock.send(JSON.stringify({ id: i, method, params })); });
const ev = async e => (await send('Runtime.evaluate', { expression: e, returnByValue: true, awaitPromise: true })).result?.value;
await send('Page.enable'); await send('Runtime.enable');
let pass = 0, fail = 0; const ok = (c, l) => { c ? pass++ : fail++; console.log(`  ${c ? 'ok  ' : 'FAIL'} ${l}`); };
const go = async (url, w, mobile) => { await send('Emulation.setDeviceMetricsOverride', { width: w, height: 900, deviceScaleFactor: 1, mobile }); await send('Page.navigate', { url }); await sleep(1500); };

await go(base, 390, true);
ok(await ev(`!!document.querySelector('.wp-block-navigation__responsive-container-open')`), 'phone shows the menu button');
await ev(`document.querySelector('.wp-block-navigation__responsive-container-open').click()`); await sleep(600);
ok(await ev(`document.querySelector('.wp-block-navigation__responsive-container').classList.contains('is-menu-open')`), 'menu button opens the overlay');
ok(await ev(`[...document.querySelectorAll('.wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation-item__label')].map(e=>e.textContent).join(',')`) === 'Services,Projects,About,Contact', 'overlay lists Services, Projects, About, Contact');
await ev(`document.querySelector('.wp-block-navigation__responsive-container-close').click()`); await sleep(400);
ok(await ev(`!document.querySelector('.wp-block-navigation__responsive-container').classList.contains('is-menu-open')`), 'close button closes it');

await go(base, 1440, false);
ok(await ev(`getComputedStyle(document.querySelector('.wp-block-navigation__responsive-container-open')).display === 'none'`), 'desktop shows the links, not the menu button');
const type = async v => { await ev(`(()=>{const i=document.querySelector('.ab-area__input');i.value=${JSON.stringify(v)};i.dispatchEvent(new Event('input',{bubbles:true}))})()`); await sleep(100); return ev(`document.querySelector('.ab-area__result').textContent`); };
ok((await type('ketterby')).includes('Yes, we are in Ketterby. Our crew is there on Wednesdays.'), 'town checker: Ketterby is served on Wednesdays');
ok(await ev(`document.querySelector('.ab-area__list li.is-match .ab-area__town')?.textContent`) === 'Ketterby', 'town checker highlights the matching row');
ok((await type('Springfield')).includes('not on our route yet'), 'town checker: unknown town gets an honest no');
ok((await type('Ke')) === '', 'town checker: stays quiet under 3 characters');

await go(base + 'contact/', 1440, false);
await ev(`document.querySelector('.ab-quote__form [type=submit]').click()`); await sleep(300);
ok((await ev(`document.querySelector('.ab-quote__notice')?.textContent || ''`)).startsWith('Demo only'), 'demo form explains that nothing is sent');
ok(await ev(`location.pathname.endsWith('/contact/')`), 'demo form does not navigate away');
console.log(errors.length ? 'console errors: ' + errors.join(' | ') : 'no console errors');
console.log(`\n${pass} passed, ${fail} failed`); sock.close(); chrome.kill(); process.exit(fail || errors.length ? 1 : 0);
