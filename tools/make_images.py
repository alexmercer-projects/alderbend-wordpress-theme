#!/usr/bin/env python3
"""Draws the original illustrations used by the Alderbend demo (no stock images, no AI renders).
Run: python3 tools/make_images.py   -> theme/alderbend/assets/images/*.svg
"""
import math, random, os
OUT = os.path.join(os.path.dirname(__file__), '..', 'theme', 'alderbend', 'assets', 'images')
os.makedirs(OUT, exist_ok=True)
W, H = 1200, 800

def write(name, body, w=W, h=H):
    with open(os.path.join(OUT, name), 'w') as f:
        f.write(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" role="img" aria-hidden="true" focusable="false">{body}</svg>\n')

def hills(rng, base, colors, y0, amp):
    out = ''
    for i, c in enumerate(colors):
        y = y0 + i * 70
        pts = f'M0 {H} L0 {y}'
        for x in range(0, W + 100, 100):
            pts += f' Q{x+50} {y - rng.randint(10, amp)} {x+100} {y + rng.randint(-20, 20)}'
        pts += f' L{W} {H} Z'
        out += f'<path d="{pts}" fill="{c}"/>'
    return out

def tree(x, y, s, leaf, leaf2, trunk='#5b4630'):
    return (f'<rect x="{x-5*s}" y="{y-60*s}" width="{10*s}" height="{70*s}" rx="{3*s}" fill="{trunk}"/>'
            f'<circle cx="{x}" cy="{y-110*s}" r="{58*s}" fill="{leaf}"/><circle cx="{x-30*s}" cy="{y-85*s}" r="{38*s}" fill="{leaf2}"/>'
            f'<circle cx="{x+32*s}" cy="{y-92*s}" r="{34*s}" fill="{leaf2}" opacity=".85"/>')

def shrub(x, y, r, c1, c2):
    return (f'<ellipse cx="{x}" cy="{y}" rx="{r}" ry="{r*.72}" fill="{c1}"/>'
            f'<ellipse cx="{x-r*.25}" cy="{y-r*.15}" rx="{r*.55}" ry="{r*.4}" fill="{c2}" opacity=".8"/>')

def flowers(rng, x0, x1, y0, y1, n, cols):
    out = ''
    for _ in range(n):
        x = rng.uniform(x0, x1); y = rng.uniform(y0, y1)
        h = rng.uniform(26, 56)
        out += f'<path d="M{x:.0f} {y:.0f} q{rng.uniform(-6,6):.0f} -{h/2:.0f} 0 -{h:.0f}" stroke="#3c6a46" stroke-width="3" fill="none" stroke-linecap="round"/>'
        out += f'<circle cx="{x:.0f}" cy="{y-h:.0f}" r="{rng.uniform(6,10):.0f}" fill="{rng.choice(cols)}"/>'
    return out

def stones(rng, pts, c1, c2):
    out = ''
    for (x, y, rx, ry) in pts:
        out += f'<ellipse cx="{x}" cy="{y}" rx="{rx}" ry="{ry}" fill="{c1}"/><ellipse cx="{x}" cy="{y-ry*.25}" rx="{rx*.85}" ry="{ry*.65}" fill="{c2}" opacity=".7"/>'
    return out

SKY = '#e9efe2'

def scene_terraced():
    r = random.Random(1)
    b = f'<rect width="{W}" height="{H}" fill="{SKY}"/><circle cx="960" cy="150" r="70" fill="#f3d9a4"/>'
    b += hills(r, 0, ['#b9cba9', '#8fb08c'], 330, 40)
    for i, (y, c) in enumerate([(470, '#79a07a'), (570, '#5e8a64'), (670, '#46734f')]):
        b += f'<rect x="0" y="{y}" width="{W}" height="{H-y}" fill="{c}"/><rect x="0" y="{y}" width="{W}" height="14" fill="#d8cdb4"/><rect x="0" y="{y+14}" width="{W}" height="9" fill="#a99d85" opacity=".5"/>'
        b += flowers(r, 40, W-40, y+60, y+90, 18, ['#e9a37c', '#f2d26b', '#f7f3ea'])
    b += tree(200, 470, 1.1, '#4f7d57', '#6c9a68') + tree(980, 470, .9, '#4f7d57', '#6c9a68')
    b += stones(r, [(600, 520, 70, 16), (640, 580, 80, 18), (580, 650, 90, 20), (630, 730, 100, 22)], '#cfc4ac', '#e4dbc6')
    return b

def scene_raingarden():
    r = random.Random(2)
    b = f'<rect width="{W}" height="{H}" fill="#e4ecea"/>'
    b += '<g fill="#bfd0d4" opacity=".9"><ellipse cx="200" cy="120" rx="120" ry="30"/><ellipse cx="300" cy="140" rx="90" ry="24"/><ellipse cx="850" cy="100" rx="130" ry="30"/></g>'
    b += hills(r, 0, ['#a9c2a1', '#86a98a'], 360, 30)
    b += f'<rect x="0" y="520" width="{W}" height="{H-520}" fill="#6a9170"/>'
    b += '<path d="M120 640 Q300 560 600 580 Q900 560 1080 650 Q900 760 600 750 Q300 760 120 640Z" fill="#8a7a62"/><path d="M200 650 Q350 595 600 610 Q850 595 1000 655 Q850 725 600 720 Q350 725 200 650Z" fill="#6f95a3"/>'
    b += '<path d="M260 650 Q420 625 600 635 Q780 625 940 655" stroke="#a9c9d4" stroke-width="5" fill="none" opacity=".8" stroke-linecap="round"/>'
    b += stones(r, [(150, 690, 34, 14), (1050, 700, 40, 16), (330, 735, 38, 14), (880, 740, 36, 14)], '#bfb49b', '#d9cfb8')
    b += flowers(r, 90, 1110, 700, 780, 50, ['#b392c9', '#f0c4d4', '#e3b3e6', '#f7f3ea'])
    b += ''.join(f'<path d="M{x} 590 q-6 -60 6 -110 M{x+14} 590 q4 -50 -4 -90" stroke="#3f7248" stroke-width="5" fill="none" stroke-linecap="round"/>' for x in range(80, 1160, 46))
    b += '<g fill="#8bb3c7"><path d="M600 120 q14 24 0 36 q-14 -12 0 -36z"/><path d="M660 170 q10 18 0 28 q-10 -10 0 -28z"/><path d="M540 190 q10 18 0 28 q-10 -10 0 -28z"/></g>'
    return b

def scene_patio():
    r = random.Random(3)
    b = f'<rect width="{W}" height="{H}" fill="{SKY}"/>'
    b += '<rect x="0" y="0" width="1200" height="330" fill="#d8e3d0"/>'
    b += '<rect x="0" y="260" width="1200" height="12" fill="#93815f"/>' + ''.join(f'<rect x="{x}" y="150" width="16" height="130" fill="#a8946d"/>' for x in range(0, 1200, 60))
    b += tree(1000, 330, 1.3, '#57855b', '#76a36f') + tree(130, 330, 1, '#57855b', '#76a36f')
    b += f'<rect x="0" y="330" width="{W}" height="{H-330}" fill="#7fa57d"/>'
    b += '<path d="M180 470 L1020 470 L1120 780 L80 780Z" fill="#c9bda3"/>'
    for row in range(5):
        y0 = 470 + row * 62; y1 = y0 + 62
        xl0 = 180 - (row * 20); xr0 = 1020 + row * 20; xl1 = 180 - (row + 1) * 20; xr1 = 1020 + (row + 1) * 20
        cols = 5 + row % 2
        for c in range(cols):
            xa = xl0 + (xr0 - xl0) * c / cols + 4; xb = xl0 + (xr0 - xl0) * (c + 1) / cols - 4
            xa2 = xl1 + (xr1 - xl1) * c / cols + 4; xb2 = xl1 + (xr1 - xl1) * (c + 1) / cols - 4
            col = r.choice(['#d9ceb5', '#cfc3a8', '#e0d6c0', '#c4b89c'])
            b += f'<path d="M{xa:.0f} {y0+4} L{xb:.0f} {y0+4} L{xb2:.0f} {y1-2} L{xa2:.0f} {y1-2}Z" fill="{col}"/>'
    b += '<rect x="470" y="535" width="260" height="14" rx="6" fill="#6b4f35"/><rect x="490" y="549" width="12" height="60" fill="#6b4f35"/><rect x="698" y="549" width="12" height="60" fill="#6b4f35"/>'
    b += '<ellipse cx="600" cy="520" rx="130" ry="18" fill="#2f5039" opacity=".15"/><rect x="430" y="615" width="340" height="14" rx="6" fill="#8a6a49"/>'
    b += '<rect x="780" y="490" width="70" height="60" rx="10" fill="#a94a28"/><ellipse cx="815" cy="490" rx="40" ry="8" fill="#5b3a20"/>' + shrub(815, 470, 50, '#4f7d57', '#6c9a68')
    b += shrub(100, 600, 70, '#3f7248', '#5e9262') + shrub(1100, 640, 80, '#3f7248', '#5e9262')
    return b

def scene_meadow():
    r = random.Random(4)
    b = f'<rect width="{W}" height="{H}" fill="#f1e8cf"/><circle cx="220" cy="180" r="90" fill="#f4cf8e" opacity=".9"/>'
    b += hills(r, 0, ['#c7cfa0', '#a5b985'], 360, 36)
    b += f'<rect x="0" y="470" width="{W}" height="{H-470}" fill="#8aa56b"/>'
    b += '<path d="M-20 790 Q500 620 480 520 Q470 480 520 470 L640 470 Q600 560 780 650 Q980 740 1220 780Z" fill="#d6c7a0"/>'
    for _ in range(190):
        x = r.uniform(0, W); y = r.uniform(520, 790)
        h = r.uniform(50, 130) * (0.6 + (y - 470) / 500)
        col = r.choice(['#d78a4a', '#e8c24c', '#9a6bb0', '#f7f3ea', '#cf5f4a', '#6f9a58'])
        b += f'<path d="M{x:.0f} {y:.0f} q{r.uniform(-14,14):.0f} -{h/2:.0f} {r.uniform(-8,8):.0f} -{h:.0f}" stroke="#4d7a45" stroke-width="3" fill="none" stroke-linecap="round"/>'
        b += f'<circle cx="{x:.0f}" cy="{y-h:.0f}" r="{r.uniform(5,11):.0f}" fill="{col}"/>'
    b += tree(1050, 480, 1, '#5d8a55', '#7ca873')
    return b

def scene_beds():
    r = random.Random(5)
    b = f'<rect width="{W}" height="{H}" fill="#e7eddc"/><rect x="0" y="0" width="{W}" height="300" fill="#dbe6d3"/>'
    b += '<rect x="0" y="220" width="1200" height="14" fill="#a8946d"/>' + ''.join(f'<rect x="{x}" y="110" width="14" height="120" fill="#b7a27a"/>' for x in range(30, 1200, 70))
    b += f'<rect x="0" y="300" width="{W}" height="{H-300}" fill="#9bb67f"/>'
    b += '<path d="M0 560 L1200 560 L1200 590 L0 590Z" fill="#d7c9a6"/>'
    for i, x in enumerate([110, 450, 790]):
        b += f'<rect x="{x}" y="380" width="300" height="150" rx="6" fill="#7b5a3b"/><rect x="{x}" y="380" width="300" height="26" rx="6" fill="#a47d54"/><rect x="{x+8}" y="406" width="284" height="110" fill="#4d3524"/>'
        for k in range(6):
            cx = x + 36 + k * 46
            if i == 0:
                b += f'<ellipse cx="{cx}" cy="388" rx="26" ry="22" fill="#6aa361"/><ellipse cx="{cx}" cy="372" rx="18" ry="16" fill="#8bc07a"/>'
            elif i == 1:
                b += f'<path d="M{cx} 395 q-10 -50 0 -80" stroke="#4d8a4f" stroke-width="5" fill="none"/><circle cx="{cx}" cy="310" r="12" fill="{r.choice(["#d9503f","#e98a2f","#f2c13a"])}"/><circle cx="{cx+8}" cy="335" r="10" fill="#d9503f"/>'
            else:
                b += f'<path d="M{cx} 395 q-8 -40 6 -70" stroke="#4d8a4f" stroke-width="4" fill="none"/><circle cx="{cx+6}" cy="322" r="14" fill="{r.choice(["#e9a3c4","#f5e6a2","#f7f3ea"])}"/>'
    b += stones(r, [(200, 650, 70, 18), (380, 700, 76, 18), (600, 660, 80, 20), (820, 710, 78, 18), (1000, 660, 70, 18)], '#cfc4ac', '#e4dbc6')
    b += '<rect x="1060" y="570" width="60" height="50" rx="6" fill="#3d6b8a"/><rect x="1050" y="560" width="80" height="14" rx="6" fill="#4b7fa0"/>'
    return b

def scene_wall():
    r = random.Random(6)
    b = f'<rect width="{W}" height="{H}" fill="{SKY}"/><circle cx="1000" cy="130" r="60" fill="#f3d9a4"/>'
    b += hills(r, 0, ['#b6c9a6', '#8cb08a'], 260, 40)
    b += f'<rect x="0" y="560" width="{W}" height="{H-560}" fill="#6d9871"/>'
    b += '<path d="M0 520 Q300 380 600 420 Q900 460 1200 330 L1200 640 Q900 700 600 650 Q300 600 0 660Z" fill="#a9967a"/>'
    # stacked stone along the curve
    for row in range(5):
        for c in range(14):
            t = c / 13
            x = t * 1200
            ytop = (520 - 120 * math.sin(t * 2.4) - 60 * t) + row * 28
            wv = 1200 / 13 - 6
            col = r.choice(['#b9ab91', '#a89a80', '#c4b79c', '#9d8f76']) if row % 2 == 0 else r.choice(['#c0b296', '#b1a388', '#cdbfa3'])
            off = 0 if row % 2 == 0 else 40
            b += f'<rect x="{x-wv/2+off:.0f}" y="{ytop:.0f}" width="{wv:.0f}" height="26" rx="6" fill="{col}"/>'
    b += shrub(150, 520, 60, '#3f7248', '#5e9262') + shrub(700, 410, 60, '#4f7d57', '#6c9a68') + shrub(1050, 330, 55, '#4f7d57', '#7aa873')
    b += flowers(r, 40, 1160, 700, 780, 40, ['#f0c4d4', '#f2d26b', '#f7f3ea'])
    b += tree(300, 400, 1, '#4f7d57', '#6c9a68')
    return b

SCENES = {
    'project-terraced-front-yard.svg': scene_terraced,
    'project-rain-garden.svg': scene_raingarden,
    'project-flagstone-patio.svg': scene_patio,
    'project-meadow-strip.svg': scene_meadow,
    'project-raised-beds.svg': scene_beds,
    'project-curved-wall.svg': scene_wall,
}
for n, fn in SCENES.items():
    write(n, fn())

# Hero: tall arch-friendly garden vignette
def hero():
    r = random.Random(11)
    w, h = 900, 1000
    b = f'<rect width="{w}" height="{h}" fill="#dfe8d6"/><circle cx="660" cy="250" r="95" fill="#f4d9a3"/>'
    for i, c in enumerate(['#b9cba9', '#9dbb98', '#7fa883']):
        y = 480 + i * 90
        d = f'M0 {h} L0 {y}'
        for x in range(0, w + 100, 100):
            d += f' Q{x+50} {y - r.randint(20, 70)} {x+100} {y + r.randint(-20, 20)}'
        b += f'<path d="{d} L{w} {h}Z" fill="{c}"/>'
    b += f'<rect x="0" y="760" width="{w}" height="240" fill="#5e8a64"/>'
    b += '<path d="M330 1000 Q380 860 440 800 Q470 770 520 760 L600 760 Q540 800 560 880 Q580 940 700 1000Z" fill="#d9cdb2"/>'
    b += stones(r, [(470, 830, 46, 12), (560, 890, 56, 14), (500, 960, 66, 16)], '#c9bda3', '#ded4bc')
    b += tree(180, 740, 1.6, '#3f7248', '#5e9262') + tree(740, 740, 1.2, '#4f7d57', '#6c9a68')
    b += shrub(80, 850, 90, '#355f40', '#4f7d57') + shrub(820, 880, 100, '#355f40', '#4f7d57')
    b += flowers(r, 40, 860, 880, 990, 34, ['#e9a37c', '#f2d26b', '#f7f3ea', '#c79ad6'])
    write('hero-garden.svg', b, w, h)
hero()

# logo mark + icons (stroke icons, currentColor)
def icon(name, path):
    with open(os.path.join(OUT, name), 'w') as f:
        f.write(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{path}</svg>\n')
icon('icon-design.svg', '<path d="M8 38 L8 12 L40 12"/><path d="M8 38 Q20 38 24 26 T40 14"/><circle cx="34" cy="34" r="5"/>')
icon('icon-stone.svg', '<path d="M6 36 L14 24 L26 22 L34 28 L42 36Z"/><path d="M14 24 L18 36 M26 22 L26 36 M34 28 L32 36"/>')
icon('icon-plant.svg', '<path d="M24 42 V22"/><path d="M24 30 Q12 30 10 18 Q22 18 24 30Z"/><path d="M24 24 Q36 24 38 12 Q26 12 24 24Z"/>')
icon('icon-water.svg', '<path d="M24 6 Q36 22 36 30 A12 12 0 0 1 12 30 Q12 22 24 6Z"/><path d="M18 31 q2 5 7 5"/>')
icon('icon-wall.svg', '<rect x="6" y="12" width="36" height="24" rx="2"/><path d="M6 24 H42 M18 12 V24 M30 24 V36 M12 24 V36"/>')
icon('icon-leaf.svg', '<path d="M10 38 C10 18 22 8 40 8 C40 28 30 40 10 38Z"/><path d="M10 38 L28 20"/>')
print('ok')
