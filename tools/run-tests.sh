#!/bin/bash
# Runs every check against a local WordPress. Set WP (wp-cli command), SITE (url) and CONTENT (wp-content path).
set -u
WP=${WP:-wp}; SITE=${SITE:-http://localhost:8080}; CONTENT=${CONTENT:?set CONTENT to the wp-content folder}
cd "$(dirname "$0")/.."
status=0
echo "== PHP lint =="; find theme demo tests -name '*.php' -print0 | xargs -0 -n1 php -l | grep -v '^No syntax errors' ; [ "${PIPESTATUS[1]}" = 0 ] || status=1; echo "lint done"
echo "== PHPCS (WordPress-Extra, WordPress-Docs) =="; phpcs --standard=phpcs.xml.dist --report=summary || status=1
echo "== Tests inside WordPress =="; $WP transient delete --all >/dev/null 2>&1; $WP eval-file "$PWD/tests/theme-tests.php" 2>&1 | grep -v '^Deprecated' || status=1
echo "== HTTP tests =="; node tests/http-tests.mjs "$SITE" "$CONTENT" || status=1
exit $status
