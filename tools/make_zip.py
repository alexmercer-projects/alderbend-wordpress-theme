#!/usr/bin/env python3
"""Zips theme/alderbend into docs/alderbend-theme.zip (the folder name stays 'alderbend/')."""
import os, zipfile
root = os.path.join(os.path.dirname(__file__), '..')
out = os.path.join(root, 'docs', 'alderbend-theme.zip')
if os.path.exists(out): os.remove(out)
with zipfile.ZipFile(out, 'w', zipfile.ZIP_DEFLATED) as z:
    for d, _, fs in os.walk(os.path.join(root, 'theme', 'alderbend')):
        for f in sorted(fs):
            p = os.path.join(d, f)
            z.write(p, os.path.relpath(p, os.path.join(root, 'theme')))
print(out)
