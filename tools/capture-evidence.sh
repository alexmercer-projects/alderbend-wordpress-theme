#!/bin/bash
# Re-runs every check and saves the raw output to docs/evidence/. Needs php, phpcs and wp on PATH, the demo site running
# on SITE, the static export served on STATIC (python3 -m http.server), and CONTENT pointing at wp-content.
set -u
cd "$(dirname "$0")/.."
SITE=${SITE:-http://localhost:8080}; STATIC=${STATIC:-http://localhost:8091}; : "${CONTENT:?set CONTENT}"
mkdir -p docs/evidence
strip() { sed 's/\x1b\[[0-9;]*m//g' | grep -v '^Deprecated'; }
SITE=$SITE CONTENT=$CONTENT ./tools/run-tests.sh 2>&1 | strip > docs/evidence/tests.txt
echo "exit: $?" >> docs/evidence/tests.txt
{ echo "WordPress $(wp core version 2>&1 | strip), PHP $(php -r 'echo PHP_VERSION;')"; cat <<'X'
X
} > docs/evidence/versions.txt
wp eval-file "$PWD/tools/theme-check.php" 2>&1 | strip > docs/evidence/theme-check.txt
ID=$(wp post create --post_type=page --post_status=draft --post_title="validation scratch" --post_content="$(for p in hero facts-strip services-grid featured-projects process seasons service-area cta faq services-detail about contact page-heading header-inner footer-inner; do printf '<!-- wp:pattern {"slug":"alderbend/%s"} /-->' $p; done)" --porcelain 2>&1 | strip)
node tools/editor-check.mjs "$ID" "$SITE" > docs/evidence/editor-check.txt 2>&1
wp post delete "$ID" --force >/dev/null 2>&1
./tools/export-all.sh > docs/evidence/export.txt 2>&1
node tools/check-links.mjs "$STATIC" /alderbend-wordpress-theme/site/ > docs/evidence/links.txt 2>&1
node tools/interaction-check.mjs "$STATIC/alderbend-wordpress-theme/site/" > docs/evidence/interaction.txt 2>&1
echo done
