#!/bin/bash
# Static export of every page of the local demo site into docs/site.
cd "$(dirname "$0")/.."
node tools/export-static.mjs "${SITE:-http://localhost:8080}" docs/site /alderbend-wordpress-theme/site / /services/ /projects/ /about/ /contact/ \
  /terraced-front-yard/ /rain-garden/ /flagstone-patio/ /meadow-strip/ /raised-beds/ /curved-wall/ \
  /category/walls/ /category/drainage/ /category/patios-and-paths/ /category/planting/
