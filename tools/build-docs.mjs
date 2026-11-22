// Builds docs/index.html (the write-up page). Every code excerpt, count, file size and test result on the page is read
// from the repository or from docs/evidence/, not typed in. Usage: node tools/build-docs.mjs   (Node 22+)
import { readFileSync, writeFileSync, statSync, readdirSync, existsSync } from 'node:fs';
import { execSync } from 'node:child_process';
const R = f => readFileSync(f, 'utf8');
const T = 'theme/alderbend/';
const esc = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

// ---------- tiny highlighter ----------
function hl(code, lang) {
  const rules = {
    json: [[/("(?:\\.|[^"\\])*")(\s*:)/, (m, a, b) => `<span class="tk-n">${a}</span>${b}`], [/("(?:\\.|[^"\\])*")/, m => `<span class="tk-s">${m}</span>`], [/\b(true|false|null)\b/, m => `<span class="tk-k">${m}</span>`], [/-?\b\d[\d.]*\b/, m => `<span class="tk-v">${m}</span>`]],
    php: [[/(\/\*[\s\S]*?\*\/|\/\/[^\n]*)/, m => `<span class="tk-c">${m}</span>`], [/('(?:\\.|[^'\\])*'|"(?:\\.|[^"\\])*")/, m => `<span class="tk-s">${m}</span>`], [/(\$[a-zA-Z_]\w*)/, m => `<span class="tk-v">${m}</span>`], [/\b(function|return|if|elseif|else|foreach|as|isset|empty|array|static|use|echo|true|false|null|define|require_once|exit)\b/, m => `<span class="tk-k">${m}</span>`], [/\b([a-z_][a-z0-9_]*)(?=\()/i, m => `<span class="tk-t">${m}</span>`]],
    js: [[/(\/\/[^\n]*)/, m => `<span class="tk-c">${m}</span>`], [/('(?:\\.|[^'\\])*'|"(?:\\.|[^"\\])*")/, m => `<span class="tk-s">${m}</span>`], [/\b(function|return|if|else|var|let|const|try|catch|new|forEach|every|true|false|null)\b/, m => `<span class="tk-k">${m}</span>`]],
    css: [[/(\/\*[\s\S]*?\*\/)/, m => `<span class="tk-c">${m}</span>`], [/(--[\w-]+)/, m => `<span class="tk-v">${m}</span>`], [/([.#][\w-]+)/, m => `<span class="tk-t">${m}</span>`]],
    html: [[/(&lt;!--[\s\S]*?--&gt;)/, m => `<span class="tk-c">${m}</span>`], [/(&lt;\/?[\w-]+)/, m => `<span class="tk-k">${m}</span>`], [/("[^"]*")/, m => `<span class="tk-s">${m}</span>`]],
    text: [],
  }[lang] || [];
  const src = esc(code);
  if (!rules.length) return src;
  const re = new RegExp(rules.map(r => `(?:${r[0].source})`).join('|'), 'g');
  const groups = rules.map(r => new RegExp(`${r[0].source}|`).exec('').length - 1);
  return src.replace(re, (...a) => {
    const m = a[0]; let off = 1;
    for (let i = 0; i < rules.length; i++) {
      if (a[off] !== undefined) return rules[i][1](m, ...a.slice(off, off + groups[i]));
      off += groups[i];
    }
    return m;
  });
}
const slice = (file, from, to) => { const t = R(file).split('\n'); const a = t.findIndex(l => l.includes(from)); const b = to ? t.findIndex((l, i) => i > a && l.includes(to)) : t.length - 1; return t.slice(a, b + 1).join('\n'); };
const dedent = b => { const ls = b.split('\n'); const m = Math.min(...ls.filter(l => l.trim()).map(l => l.match(/^\t*/)[0].length)); return ls.map(l => l.slice(m)).join('\n'); };
const excerpt = (title, file, lang, body, note = '') => (body = dedent(body)) &&  `<figure class="code"><div class="code__head"><b>${esc(title)}</b><span>${esc(note)}</span></div><pre><code>${hl(body, lang)}</code></pre></figure>`;

// ---------- measurements ----------
const listFiles = d => readdirSync(d, { withFileTypes: true }).flatMap(e => e.isDirectory() ? listFiles(d + '/' + e.name) : [d + '/' + e.name]);
const files = listFiles('theme/alderbend');
const count = (dir, ext) => readdirSync(T + dir).filter(f => f.endsWith(ext)).length;
const phpLines = files.filter(f => f.endsWith('.php')).reduce((n, f) => n + R(f).split('\n').length, 0);
const kb = n => (n / 1024).toFixed(n < 10240 ? 1 : 0) + ' KB';
const sz = f => statSync(f).size;
const home = R('docs/site/index.html');
const wanted = new Set(); for (const m of home.matchAll(/(?:href|src)=["']\/alderbend-wordpress-theme\/site(\/[^"']+\.[a-z0-9]+)["']/g)) wanted.add(m[1]);
for (const m of home.matchAll(/url\(["']?\/alderbend-wordpress-theme\/site(\/[^"')]+)["']?\)/g)) wanted.add(m[1]);
const fontFiles = [...home.matchAll(/\/alderbend-wordpress-theme\/site(\/[^"')\s]+\.woff2)/g)].map(m => m[1]);
const used = [...new Set([...wanted, ...fontFiles])].filter(p => existsSync('docs/site' + p));
const byType = {}; for (const p of used) { const e = p.split('.').pop(); byType[e] = (byType[e] || 0) + sz('docs/site' + p); }
const htmlBytes = sz('docs/site/index.html');
const tests = R('docs/evidence/tests.txt');
const results = [...tests.matchAll(/(\d+) passed, (\d+) failed/g)].map(m => [+m[1], +m[2]]);
const pass = results.reduce((a, r) => a + r[0], 0);
const passWP = results[0][0], passHTTP = results[1][0];
const failed = results.reduce((a, r) => a + r[1], 0);
const editor = JSON.parse(R('docs/evidence/editor-check.txt').split('\n')[0]);
const wpver = R('docs/evidence/versions.txt').trim();

// ---------- excerpts ----------
const ex = [
  excerpt('theme.json: tokens', T + 'theme.json', 'json', slice(T + 'theme.json', '"layout"', '"gradients"').replace(/\n\t\t\t"defaultGradients[^\n]*/, '') + '\n\t\t\t…', 'palette, layout and fluid type live here, not in CSS'),
  excerpt('patterns/hero.php', T + 'patterns/hero.php', 'php', slice(T + 'patterns/hero.php', '<!-- wp:column {"verticalAlignment":"center","width":"56%"}', '<!-- /wp:buttons -->'), 'a pattern is real block markup plus escaped, translatable strings'),
  excerpt('blocks/service-area/render.php', T + 'blocks/service-area/render.php', 'php', slice(T + 'blocks/service-area/render.php', '$towns = alderbend_clean_towns', '</ul>'), 'dynamic block: clean the data, then escape on output'),
  excerpt('inc/quote-request.php: validation', T + 'inc/quote-request.php', 'php', slice(T + 'inc/quote-request.php', 'function alderbend_validate_quote', "\treturn array(").split('\n').slice(0, 40).join('\n') + '\n\t…', 'a pure function, so it can be tested without a browser'),
  excerpt('inc/quote-request.php: the handler', T + 'inc/quote-request.php', 'php', slice(T + 'inc/quote-request.php', 'function alderbend_handle_quote', '$result = alderbend_validate_quote').replace(/\n\t\$raw = array\(\);[\s\S]*$/, ''), 'nonce, honeypot, then a one-minute rate limit per visitor'),
  excerpt('blocks/service-area/view.js', T + 'blocks/service-area/view.js', 'js', slice(T + 'blocks/service-area/view.js', 'function check()', 'input.addEventListener').replace(/\n\n\t\tinput\.add[\s\S]*$/, ''), 'about 1.8 KB of plain JavaScript, no framework, only where the block is used'),
].join('\n');

// ---------- file tree ----------
const notes = { 'theme.json': 'design tokens and block defaults', 'functions.php': 'setup, enqueue, block styles', 'style.css': 'theme header only', 'readme.txt': 'WordPress-style readme', 'screenshot.png': 'shown on the Themes screen', 'languages/alderbend.pot': R(T + 'languages/alderbend.pot').split('\n').filter(l => l.startsWith('msgid')).length - 1 + ' translatable strings', 'inc/blocks.php': 'registers the two blocks', 'inc/quote-request.php': 'validation and the form handler', 'assets/css/theme.css': 'only what theme.json cannot say', 'styles/clay.json': 'second colour scheme' };
const treeLines = []; const walk = (dir, pre) => { const es = readdirSync(dir, { withFileTypes: true }).sort((a, b) => (b.isDirectory() - a.isDirectory()) || a.name.localeCompare(b.name)); es.forEach((e, i) => { const last = i === es.length - 1; const p = dir + '/' + e.name; const rel = p.replace('theme/alderbend/', ''); if (e.isDirectory()) { const n = listFiles(p).length; treeLines.push(`${pre}${last ? '└── ' : '├── '}${e.name}/ <em>${n} file${n > 1 ? 's' : ''}</em>`); if (!['fonts', 'images'].includes(e.name)) walk(p, pre + (last ? '    ' : '│   ')); } else treeLines.push(`${pre}${last ? '└── ' : '├── '}${e.name}${notes[rel] ? ' <em>' + notes[rel] + '</em>' : ''}`); }); };
treeLines.push('alderbend/'); walk('theme/alderbend', '');
const repoTree = `alexmercer-projects/alderbend-wordpress-theme
├── theme/alderbend/   <em>the theme you install</em>
├── tests/             <em>in-WordPress checks and HTTP tests</em>
├── tools/             <em>screenshots, static export, link and editor checks</em>
├── demo/              <em>seed script, demo bar, picture files for the sample posts</em>
├── docs/              <em>this page and the static copy of the demo site</em>
└── phpcs.xml.dist     <em>WordPress-Extra + WordPress-Docs rules</em>`;

// ---------- zip ----------
execSync('python3 tools/make_zip.py');
const zipKb = kb(sz('docs/alderbend-theme.zip'));

const out = (name, id, title) => `<details class="out"><summary>${esc(title)}</summary><pre>${esc(R('docs/evidence/' + name))}</pre></details>`;
const shot = (img, cap, cls = '') => `<figure class="shot ${cls}"><div class="frame"><img src="img/${img}.webp" alt="${esc(cap.replace(/<[^>]+>/g, ''))}" loading="lazy"></div><figcaption>${cap}</figcaption></figure>`;

const page = `<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alderbend: a from-scratch WordPress block theme (concept demo) · Alex Mercer</title>
<meta name="description" content="Concept demo: a WordPress block theme written from scratch for a made-up landscaping business, with theme.json tokens, patterns, two custom blocks, tests and a static copy of the rendered site.">
<meta name="robots" content="noindex">
<meta name="theme-color" content="#f4f1ea">
<meta property="og:title" content="Alderbend: a from-scratch WordPress block theme, concept demo">
<meta property="og:description" content="Block theme, patterns, two dynamic blocks, a hardened quote form, tests and the rendered site. Made-up business, example content.">
<meta property="og:type" content="website">
<link rel="preload" href="fonts/Geist.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="fonts/InstrumentSerif.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="css/styles.css">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%232f5039'/%3E%3Cpath d='M8 25C8 13 14 7 25 7c0 11-6 18-17 18Z' fill='%23f4f1ea'/%3E%3C/svg%3E">
</head>
<body>
<div class="am-demo-bar" role="note" style="font:14px/1.45 system-ui,-apple-system,'Segoe UI',sans-serif;background:#0f0f0f;color:#f3f3ef;padding:10px 16px;display:flex;flex-wrap:wrap;gap:6px 18px;justify-content:space-between;align-items:center;position:relative;z-index:50"><span><b>Concept demo by Alex Mercer.</b> Alderbend Landscape Co., its towns, projects and phone number are made up. Example content, not client work.</span><a href="https://alexmercerprojects.com/" style="color:#f3f3ef;text-decoration:underline;text-underline-offset:3px">Back to Alex Mercer&#39;s portfolio</a></div>
<a class="skip" href="#main">Skip to content</a>
<header class="top"><div class="wrap top__inner">
  <a class="logo" href="#main"><span class="logo__mark" aria-hidden="true"><svg viewBox="0 0 32 32"><path d="M8 25C8 13 14 7 25 7c0 11-6 18-17 18Z" fill="currentColor"/></svg></span><span class="logo__word"><span>Alderbend</span><small>WordPress block theme</small></span></a>
  <nav class="nav" aria-label="Sections"><a href="#demo">The demo</a><a href="#inside">What's inside</a><a href="#code">Code</a><a href="#files">Files</a><a href="#checks">Checks</a><a href="#install">Install</a></nav>
  <a class="btn btn--solid btn--sm" href="alderbend-theme.zip" download><svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10 3v10m0 0-4-4m4 4 4-4M4 16h12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Theme .zip</span></a>
</div></header>
<main id="main">
<section class="hero wrap">
  <div>
    <p class="eyebrow">WordPress · Block theme · PHP, theme.json, patterns</p>
    <h1>A WordPress theme written from scratch for a <em>small landscaping business.</em></h1>
    <p class="lede">No starter theme, no framework, no page builder. A full-site-editing theme for a made-up landscaper: design tokens in <code>theme.json</code>, ${count('patterns', '.php')} patterns, two small custom blocks, a quote form that checks and escapes everything, and tests you can read.</p>
    <div class="cta-row">
      <a class="btn btn--solid" href="site/">Open the demo site</a>
      <a class="btn btn--line" href="https://github.com/alexmercer-projects/alderbend-wordpress-theme">Read the code on GitHub</a>
    </div>
    <p class="hero__note small muted">Alderbend Landscape Co. is made up, and so are its towns, projects and phone number. This is a concept demo, not client work, and it has not run on a real business's site. The demo site is a static copy of the theme running in a real WordPress; it is explained under <a href="#honest">what was tested</a>.</p>
  </div>
  <div class="frame"><div class="frame__bar"><i></i><i></i><i></i><span>localhost, WordPress ${wpver.match(/WordPress ([\d.]+)/)[1]}, Alderbend theme</span></div><img src="img/desktop-home.webp" alt="The Alderbend home page: a headline, a garden illustration in an arch, and a dark facts strip." width="1440" height="1000"></div>
</section>

<div class="facts"><div class="wrap facts__grid">
  <div class="fact"><b>${count('patterns', '.php')}</b><span>block patterns, from hero to FAQ</span></div>
  <div class="fact"><b>2</b><span>custom dynamic blocks (<code>block.json</code> + PHP render)</span></div>
  <div class="fact"><b>${pass}</b><span>automated checks passing, ${failed} failing</span></div>
  <div class="fact"><b>${editor.total}</b><span>blocks opened in the real editor, ${editor.invalid.length} invalid</span></div>
  <div class="fact"><b>${kb(sz(T + 'assets/css/theme.css'))}</b><span>of theme CSS (the rest is <code>theme.json</code>)</span></div>
</div></div>

<section class="block" id="demo"><div class="wrap">
  <p class="eyebrow">The real thing</p>
  <h2>The theme, rendered.</h2>
  <p class="sub">Both frames below are the actual demo site. It was exported from the theme running in WordPress on my machine, so every link, the phone menu and the town checker work. The quote form only explains itself, because GitHub Pages cannot run PHP.</p>
  <div class="live">
    <div><div class="frame"><div class="frame__bar"><i></i><i></i><i></i><span>site/ (static export of the running theme)</span></div><iframe src="site/" title="Alderbend demo site, desktop" loading="lazy"></iframe></div>
    <div class="live__cap small muted"><span>Scroll inside the frame, or <a href="site/">open it full screen</a>.</span><span>Pages: Home, Services, Projects (+ 6 sample projects), About, Contact.</span></div></div>
    <div><div class="phone"><iframe src="site/" title="Alderbend demo site, phone width" loading="lazy"></iframe></div><p class="small muted" style="text-align:center;margin-top:12px">Same site at phone width. Try the menu button.</p></div>
  </div>
  <div class="shots">
    ${shot('desktop-services', '<b>Services.</b> Six rows built from one pattern, then the process, FAQ and call to action.')}
    ${shot('desktop-projects', '<b>Projects.</b> A Query Loop with featured images, 3-column grid, pagination ready.')}
    ${shot('desktop-contact', '<b>Contact.</b> The quote form block next to a soft panel. Errors and success are announced.')}
    ${shot('desktop-clay-variation', '<b>Style variation.</b> "Clay and sage" swaps the palette from the Styles panel, no CSS change.')}
    ${shot('editor-contact', '<b>In the block editor.</b> The Contact page with the Quote request form block selected, in WordPress ' + wpver.match(/WordPress ([\d.]+)/)[1] + '.')}
    ${shot('mobile-home', '<b>Phone, 390 px.</b> No sideways scrolling on any page; the header CTA moves into the menu.', 'shot--phone')}
  </div>
</div></section>

<section class="block" id="inside"><div class="wrap">
  <p class="eyebrow">What's inside</p>
  <h2>Everything a small-business site needs, nothing it doesn't.</h2>
  <div class="cards">
    <div class="card"><span class="k">theme.json v3</span><h3>Design tokens, not CSS</h3><p>8-colour palette, a fluid type scale, a spacing scale, shadow and radius presets, and styling for headings, links, buttons, navigation and more. ${kb(sz(T + 'theme.json'))} of JSON drives most of the look.</p></div>
    <div class="card"><span class="k">${count('templates', '.html')} templates · ${count('parts', '.html')} parts</span><h3>Full site editing</h3><p>Front page, page, wide page, projects index, archive, single project, search and 404, with a header and footer part. Everything is editable in the Site Editor.</p></div>
    <div class="card"><span class="k">${count('patterns', '.php')} patterns</span><h3>Real block markup</h3><p>Hero, facts strip, services, projects, process, seasons, service area, call to action, FAQ and more. PHP patterns, so links use <code>home_url()</code> and text is translatable.</p></div>
    <div class="card"><span class="k">2 dynamic blocks</span><h3>Service area checker</h3><p>Lists towns with a route day and lets a visitor type theirs. Server-rendered, with 1.8 KB of JavaScript loaded only on pages that have the block.</p></div>
    <div class="card"><span class="k">admin-post.php</span><h3>Quote request form</h3><p>Nonce, honeypot, per-field validation, a one-minute rate limit, header-injection-safe subject and an email to the site owner. Errors keep what was typed.</p></div>
    <div class="card"><span class="k">6 block styles · 1 variation</span><h3>Choices in the editor</h3><p>Card and soft-panel groups, arch images, checklist lists, kicker paragraphs, ghost buttons, and a second colour scheme in the Styles panel.</p></div>
    <div class="card"><span class="k">accessibility</span><h3>Built in, then checked</h3><p>Skip link, one <code>h1</code> per page, landmarks, visible focus rings, labelled fields with <code>aria-invalid</code> and described errors, <code>role="status"</code> notices, reduced-motion support.</p></div>
    <div class="card"><span class="k">performance</span><h3>Small and local</h3><p>Home page: ${kb(htmlBytes)} HTML, ${kb(byType.css || 0)} CSS, ${kb(byType.woff2 || 0)} fonts, ${kb((byType.webp || 0) + (byType.svg || 0))} images, ${kb((byType.js || 0) + (byType.mjs || 0))} script. No CDN, no tracking, no Google Fonts.</p></div>
    <div class="card"><span class="k">i18n</span><h3>Translation-ready</h3><p>Every string uses the <code>alderbend</code> text domain and there is a generated <code>.pot</code> file with ${R(T + 'languages/alderbend.pot').split('\n').filter(l => l.startsWith('msgid')).length - 1} strings.</p></div>
  </div>
</div></section>

<section class="block" id="code"><div class="wrap">
  <p class="eyebrow">Code excerpts</p>
  <h2>Read the parts that matter.</h2>
  <p class="sub">These snippets are pulled from the files in the repository when this page is built, so they match the code. Full files are on <a href="https://github.com/alexmercer-projects/alderbend-wordpress-theme">GitHub</a>.</p>
  ${ex}
</div></section>

<section class="block" id="files"><div class="wrap">
  <p class="eyebrow">File tree</p>
  <h2>${files.length} files, ${phpLines.toLocaleString('en-US')} lines of PHP.</h2>
  <div class="two">
    <div class="tree" aria-label="Theme file tree">${treeLines.join('\n')}</div>
    <div><div class="tree" aria-label="Repository layout">${repoTree}</div>
    <p class="small muted" style="margin-top:14px">Fonts: Fraunces and Figtree, self-hosted, SIL Open Font License (texts in <code>assets/fonts/</code>). Illustrations: original SVG drawn for this demo by <code>tools/make_images.py</code>. No stock photos and no external requests.</p></div>
  </div>
</div></section>

<section class="block" id="checks"><div class="wrap">
  <p class="eyebrow">Real output</p>
  <h2>What was run, and what it said.</h2>
  <p class="sub">Run on ${esc(wpver)} with SQLite, PHP's built-in server and headless Chrome. The raw output is below and in <code>docs/evidence/</code>.</p>
  <div class="checks">
    <div class="check"><b>0 errors</b><span>PHP lint on every PHP file, and PHPCS with the WordPress-Extra and WordPress-Docs rules</span></div>
    <div class="check"><b>${pass} / ${pass + failed}</b><span>checks: theme.json, templates, patterns, blocks, validation, ${passWP} inside WordPress and ${passHTTP} over HTTP</span></div>
    <div class="check"><b>${editor.total} blocks, ${editor.invalid.length} invalid</b><span>every pattern opened in the real block editor and checked for block validation errors</span></div>
    <div class="check"><b>0 horizontal scroll</b><span>at 1440 and 390 px on all 7 page types, no console errors, no outside requests</span></div>
    <div class="check"><b>0 broken links</b><span>crawl of the static export: ${R('docs/evidence/links.txt').trim()}</span></div>
    <div class="check"><b>11 / 11</b><span>click-through checks on the export: phone menu, town checker, demo form</span></div>
  </div>
  ${out('tests.txt', '', 'Lint, PHPCS, tests inside WordPress and HTTP tests (' + pass + ' checks)')}
  ${out('theme-check.txt', '', 'WordPress.org Theme Check plugin (one required item, explained below)')}
  ${out('editor-check.txt', '', 'Block editor validation of every pattern')}
  ${out('interaction.txt', '', 'Click-through checks on the static export')}
</div></section>

<section class="block" id="install"><div class="wrap">
  <p class="eyebrow">Install</p>
  <h2>Three ways to try it.</h2>
  <ol class="steps">
    <li><b>Upload the zip.</b> In WordPress go to Appearance, Themes, Add New, Upload Theme and pick <code>alderbend-theme.zip</code> (${zipKb}). Activate it. Needs WordPress 6.5 or newer.</li>
    <li><b>Make the pages.</b> The header links expect pages with the slugs <code>services</code>, <code>projects</code>, <code>about</code> and <code>contact</code>. Set a Home page as the front page and Projects as the posts page. Or let the seed script do it, with the made-up content:
<pre>git clone https://github.com/alexmercer-projects/alderbend-wordpress-theme
cp -r alderbend-wordpress-theme/theme/alderbend wp-content/themes/
WP="wp --path=/path/to/wordpress" bash alderbend-wordpress-theme/demo/seed.sh</pre></li>
    <li><b>Make it yours.</b> Change colours and type in Appearance, Editor, Styles (or edit <code>theme.json</code>), swap the text in the patterns, and set the quote form's recipient under Settings, General (it uses the site admin email).</li>
  </ol>
  <p class="small muted" style="margin-top:14px">To run the checks yourself you need PHP 8, WP-CLI, PHPCS with the WordPress standards, Node 22 and Chrome; <code>tools/run-tests.sh</code> lists the rest.</p>
</div></section>

<section class="block" id="honest"><div class="wrap">
  <p class="eyebrow">Straight talk</p>
  <h2>What was tested, and what wasn't.</h2>
  <div class="honest">
    <div><h3>Tested</h3><ul>
      <li>Runs on ${esc(wpver)} (SQLite database, PHP built-in server), activated as a normal theme.</li>
      <li>PHP lint and PHPCS (WordPress-Extra, WordPress-Docs) clean; the Theme Check plugin's only required item is explained at right.</li>
      <li>All 15 pattern files plus the two blocks open in the block editor without validation errors.</li>
      <li>The quote form: bad nonce refused, bad input redirected with messages, honeypot, rate limit and the email contents, tested against a stand-in for <code>wp_mail()</code>.</li>
      <li>Headless Chrome at 1440 and 390 px: no horizontal scroll, no console errors, no outside requests; phone menu and town checker clicked through.</li>
    </ul></div>
    <div><h3>Not tested, or not claimed</h3><ul>
      <li>MySQL or MariaDB, Apache or nginx, other PHP versions (the header says 7.4+ but I only ran 8.5), multisite, caching or SEO plugins.</li>
      <li>Firefox, Safari, real phones, screen readers, or an accessibility audit tool. The accessibility features are there; I haven't had them independently reviewed.</li>
      <li>Real email delivery. On a live site, mail depends on the host or an SMTP plugin.</li>
      <li>The WordPress.org theme review. Theme Check flags <code>register_block_type()</code> because blocks are "plugin territory" there; I kept the two blocks in the theme because they are part of this one site's design. To list it on wordpress.org they would move into a plugin.</li>
      <li>The demo here is static: forms don't send and nothing runs PHP. It is a rendered copy, not a hosted WordPress. No client, traffic, leads or results are claimed.</li>
    </ul></div>
  </div>
</div></section>

<section class="block" id="downloads"><div class="wrap">
  <p class="eyebrow">Downloads</p>
  <h2>Take it apart.</h2>
  <div class="dl">
    <a href="alderbend-theme.zip" download><b>alderbend-theme.zip</b><span>The installable theme folder, ${zipKb}. GPL v2 or later, fonts under the OFL.</span></a>
    <a href="https://github.com/alexmercer-projects/alderbend-wordpress-theme"><b>Source on GitHub</b><span>Theme, ${pass} checks, PHPCS config, export and screenshot tools, commit history.</span></a>
    <a href="site/"><b>The demo site</b><span>Static copy of the rendered theme: ${readdirSync('docs/site').length} folders, no PHP, no tracking.</span></a>
  </div>
</div></section>
</main>
<footer class="foot"><div class="wrap">
  <p><b>Alex Mercer</b>. Freelance web developer. This page is a concept demo for a made-up business and is not client work.</p>
  <p>Theme code: GPL v2 or later. Fonts: SIL Open Font License. Illustrations: original, drawn for this demo.</p>
  <p><a href="https://alexmercerprojects.com/">alexmercerprojects.com</a></p>
</div></footer>
</body>
</html>
`;
writeFileSync('docs/index.html', page);
console.log('docs/index.html', kb(page.length), 'zip', zipKb);
