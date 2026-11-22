// HTTP-level checks against a running copy of the site: pages, markup, assets, the quote form round trip.
// Usage: node tests/http-tests.mjs http://localhost:8080 /path/to/wp-content   (Node 22+)
import { readFileSync, existsSync, rmSync, copyFileSync, mkdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
const [base = 'http://localhost:8080', content = ''] = process.argv.slice(2);
let pass = 0, fail = 0;
const ok = (c, l) => { c ? pass++ : fail++; console.log(`  ${c ? 'ok  ' : 'FAIL'} ${l}`); };
const get = async (p, o = {}) => fetch(base + p, { redirect: 'manual', ...o });
const html = async p => (await get(p)).text();

console.log('Pages');
for (const [p, h1] of [['/', 'Yards that look settled'], ['/services/', 'Services'], ['/projects/', 'Sample projects'], ['/about/', 'About'], ['/contact/', 'Contact'], ['/rain-garden/', 'Rain garden']]) {
  const r = await get(p); const t = await r.text();
  ok(r.status === 200, `${p} returns 200`);
  ok(t.includes(h1), `${p} has "${h1}"`);
  ok((t.match(/<h1[ >]/g) || []).length === 1, `${p} has exactly one h1`);
  ok(/<html[^>]* lang="/.test(t), `${p} sets the page language`);
  ok(!/fonts\.googleapis|cdn\.|cdnjs|unpkg/.test(t), `${p} loads nothing from a CDN`);
  ok(/<main[ >]/.test(t) && /<header[ >]/.test(t) && /<footer[ >]/.test(t), `${p} has header, main and footer landmarks`);
  const imgs = [...t.matchAll(/<img\b[^>]*>/g)].map(m => m[0]);
  ok(imgs.every(i => /\balt=/.test(i)), `${p}: every image has an alt attribute`);
}
ok((await get('/definitely-not-here/')).status === 404, 'unknown URL returns 404');
const four = await html('/definitely-not-here/'); ok(four.includes('That path does not lead anywhere'), '404 page uses the theme 404 template');

console.log('Assets');
const home = await html('/');
const css = [...home.matchAll(/href=['"]([^'"]+theme\.css[^'"]*)['"]/g)].map(m => m[1]);
ok(css.length >= 1, 'theme.css is enqueued');
ok(css.length && (await fetch(css[0])).status === 200, 'theme.css is served');
ok(/@font-face/.test(home) && /fraunces-latin-wght-normal\.woff2/.test(home), 'font-face rules point at the bundled Fraunces file');
const font = home.match(/url\(['"]?([^'")]*figtree[^'")]*\.woff2)/);
ok(font && (await fetch(font[1])).status === 200, 'Figtree woff2 is served from the theme');
ok(/service-area\/view\.js/.test(home), 'service-area view script is loaded where the block is used');
const contact = await html('/contact/');
ok(!/service-area\/view\.js/.test(contact), 'service-area view script is not loaded on a page without the block');
ok(/<a class="skip-link/.test(home) || /skip-link/.test(home), 'skip link is present');

console.log('Service area block');
ok((home.match(/class="ab-area__list"/g) || []).length === 1, 'town list renders');
ok(/<datalist id="ab-area-\d+-list">/.test(home), 'datalist renders');
ok(home.includes('Millbrook Ridge') && home.includes('Saturday'), 'towns and route days render');

console.log('Quote form round trip');
const logFile = content ? `${content}/mail-log.json` : '';
if (content) {
  mkdirSync(`${content}/mu-plugins`, { recursive: true });
  copyFileSync(fileURLToPath(new URL('./fixtures/mail-catcher.php', import.meta.url)), `${content}/mu-plugins/zz-mail-catcher.php`);
  if (existsSync(logFile)) rmSync(logFile);
}
const nonce = contact.match(/name="alderbend_nonce" value="([a-f0-9]+)"/)?.[1];
ok(!!nonce, 'form carries a nonce');
const post = async fields => get('/wp-admin/admin-post.php', { method: 'POST', headers: { 'content-type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(fields) });
const good = { action: 'alderbend_quote', alderbend_nonce: nonce, q_name: 'Jo Example', q_email: 'jo@example.com', q_phone: '(555) 010-0199', q_town: 'Ketterby', q_service: 'Patios and paths', q_message: 'We would like a small patio by the back door.' };
let r = await post({ ...good, alderbend_nonce: 'bad' });
ok(r.status === 403, 'bad nonce is refused with 403');
r = await post({ ...good, q_email: 'nope' });
ok(r.status === 302 && /quote=error&qref=[a-z0-9]{20}/i.test(r.headers.get('location')), 'invalid input redirects back with an error token');
const errPage = await (await fetch(r.headers.get('location'))).text();
ok(errPage.includes('That email address does not look right.') && errPage.includes('aria-invalid="true"') && errPage.includes('value="Jo Example"'), 'error page shows the message, marks the field and keeps what was typed');
r = await post({ ...good, company_site: 'http://spam.example' });
ok(r.status === 302 && /quote=sent/.test(r.headers.get('location')), 'honeypot gets a fake success');
ok(!content || !existsSync(logFile), 'honeypot sends no email');
r = await post(good);
ok(r.status === 302 && /quote=sent/.test(r.headers.get('location')), 'valid request redirects with quote=sent');
if (content) {
  const mail = existsSync(logFile) ? JSON.parse(readFileSync(logFile, 'utf8').trim().split('\n').pop()) : null;
  ok(mail && mail.to === 'admin@example.com' || (mail && String(mail.to).includes('example.com')), 'one email goes to the site admin address');
  ok(mail && mail.subject === 'Quote request: Patios and paths in Ketterby', 'subject names the service and town');
  ok(mail && mail.message.includes('Jo Example') && mail.message.includes('small patio'), 'body carries the request');
}
r = await post({ ...good, q_name: 'Jo Again' });
ok(r.status === 302 && /quote=wait/.test(r.headers.get('location')), 'a second request within a minute is held back');
const sent = await (await fetch(base + '/contact/?quote=sent')).text();
ok(sent.includes('Thanks, your request is in.') && sent.includes('role="status"'), 'success notice is announced with role=status');
if (content) rmSync(`${content}/mu-plugins/zz-mail-catcher.php`);

console.log(`\n${pass} passed, ${fail} failed`);
process.exit(fail ? 1 : 0);
