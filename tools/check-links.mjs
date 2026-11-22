// Crawls the static export from a local server and checks every internal link and asset returns 200.
// Usage: node tools/check-links.mjs http://localhost:8091 /alderbend-wordpress-theme/site/
const [origin, start] = process.argv.slice(2);
const seen = new Set(), bad = [], pages = [start], assets = new Set();
while (pages.length) {
  const p = pages.shift(); if (seen.has(p)) continue; seen.add(p);
  const r = await fetch(origin + p); if (r.status !== 200) { bad.push(`${r.status} ${p}`); continue; }
  const html = await r.text();
  for (const m of html.matchAll(/(?:href|src)=["']([^"'#]+)["']/g)) {
    let u = m[1]; if (/^(https?:|mailto:|tel:|data:)/.test(u)) { if (u.startsWith('http') && !u.includes('alexmercerprojects.com')) bad.push(`external ${u} on ${p}`); continue; }
    u = new URL(u, origin + p); if (u.origin !== origin) continue;
    if (u.pathname.endsWith('/') && u.pathname.startsWith(start)) pages.push(u.pathname); else assets.add(u.pathname);
  }
}
for (const a of assets) { const r = await fetch(origin + a); if (r.status !== 200) bad.push(`${r.status} ${a}`); }
console.log(`${seen.size} pages and ${assets.size} assets checked; ${bad.length} problems`); bad.forEach(b => console.log(' ', b)); process.exit(bad.length ? 1 : 0);
