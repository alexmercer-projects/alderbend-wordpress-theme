// Turns the running local WordPress into plain files that GitHub Pages can serve.
// It fetches each page from the real site, copies every file those pages use (CSS, fonts, images, scripts,
// WordPress core modules), and rewrites the URLs to the public path. No PHP runs on the result.
// Usage: node tools/export-static.mjs <local-origin> <out-dir> <public-prefix> <path> [path ...]
import { mkdirSync, writeFileSync, rmSync } from 'node:fs';
import { dirname, join } from 'node:path';
const [origin, out, prefix, ...paths] = process.argv.slice(2);
rmSync(out, { recursive: true, force: true });
const pageSet = new Set(paths.map(p => p.replace(/\/?$/, '/').replace(/^\/\/$/, '/')));
const assets = new Map(); // url path -> Buffer
const queue = [];
const esc = origin.replace(/[.*+?^${}()|[\]\\\/]/g, '\\$&');
const urlRe = new RegExp(`${esc}(?:\\/|\\\\\\/)[^"'\\s)<>\\\\]*(?:\\\\\\/[^"'\\s)<>\\\\]*)*`, 'g');
const clean = u => u.replace(/\\\//g, '/');
const toPublic = (p) => {
  const [path] = p.split(/[?#]/);
  const hash = p.includes('#') ? '#' + p.split('#')[1] : '';
  if (pageSet.has(path.replace(/\/?$/, '/')) ) return prefix + path.replace(/\/?$/, '/') + hash;
  return prefix + path;
};
function rewrite(text, isEscaped) {
  return text.replace(urlRe, m => {
    const u = clean(m).slice(origin.length) || '/';
    const [path] = u.split(/[?#]/);
    const isPage = pageSet.has(path.replace(/\/?$/, '/'));
    if (!isPage && /\.[a-z0-9]{2,5}$/i.test(path) && !assets.has(path)) { assets.set(path, null); queue.push(path); }
    const out = toPublic(u).replace(/\?ver=[^"'&\s]*/, '');
    return isEscaped ? out.replace(/\//g, '\\/') : out;
  });
}
async function fetchBin(path) {
  const r = await fetch(origin + path);
  if (!r.ok) throw new Error(`${r.status} ${path}`);
  return Buffer.from(await r.arrayBuffer());
}
const save = (rel, data) => { const f = join(out, rel); mkdirSync(dirname(f), { recursive: true }); writeFileSync(f, data); };

for (const p of pageSet) {
  let html = await (await fetch(origin + p)).text();
  // things that only make sense with a live WordPress behind them
  html = html.replace(/<link rel=["'](?:https:\/\/api\.w\.org\/|alternate|EditURI|wlwmanifest|shortlink|pingback)[^>]*>\s*/g, '');
  html = html.replace(/<link rel=['"]canonical[^>]*>\s*/g, '');
  html = html.replace(/<meta name="generator"[^>]*>\s*/g, '');
  html = html.replace(/<link rel='dns-prefetch'[^>]*>\s*/g, '');
  html = html.split(`"${origin}"`).join(`"${prefix}/"`).split(`'${origin}'`).join(`'${prefix}/'`);
  html = rewrite(html, false);
  html = rewrite(html, true);
  html = html.replace(/action="[^"]*admin-post\.php"/g, 'action="#"');
  save(join(p, 'index.html'), html);
}
while (queue.length) {
  const path = queue.shift();
  let data;
  try { data = await fetchBin(path); } catch (e) { console.error('skip', e.message); continue; }
  if (/\.(css|js|mjs)$/i.test(path)) {
    let t = data.toString('utf8');
    t = rewrite(t, false);
    // relative references inside css/js
    const dir = dirname(path);
    const rel = [];
    if (/\.css$/i.test(path)) for (const m of t.matchAll(/url\(\s*['"]?(?!data:|https?:|\/)([^'")]+)['"]?\s*\)/g)) rel.push(m[1]);
    else for (const m of t.matchAll(/["'](\.{1,2}\/[^"']+\.(?:m?js))["']/g)) rel.push(m[1]);
    for (const r of rel) {
      const clean = r.split(/[?#]/)[0];
      const abs = new URL(clean, 'http://x' + dir + '/').pathname;
      if (!assets.has(abs)) { assets.set(abs, null); queue.push(abs); }
    }
    data = Buffer.from(t);
  }
  assets.set(path, data);
  save(path.slice(1), data);
}
console.log(`exported ${pageSet.size} pages and ${[...assets.values()].filter(Boolean).length} files to ${out}`);
