#!/bin/bash
# Fills a fresh WordPress install with the made-up Alderbend content, then activates the theme.
# Usage: WP="wp --path=/path/to/wordpress" bash demo/seed.sh
set -euo pipefail
WP=${WP:-wp}
HERE=$(cd "$(dirname "$0")" && pwd)

$WP theme activate alderbend
$WP option update blogname "Alderbend Landscape Co."
$WP option update blogdescription "Landscape design and building (made-up concept demo)"
$WP rewrite structure '/%postname%/' --hard
$WP option update timezone_string "America/Denver"
$WP post delete 1 2 --force >/dev/null 2>&1 || true

HOME_ID=$($WP post create --post_type=page --post_status=publish --post_title="Home" --post_name=home --porcelain)
SERV_ID=$($WP post create --post_type=page --post_status=publish --post_title="Services" --post_name=services --menu_order=1 --post_content='<!-- wp:pattern {"slug":"alderbend/services-detail"} /--><!-- wp:pattern {"slug":"alderbend/process"} /--><!-- wp:pattern {"slug":"alderbend/faq"} /--><!-- wp:pattern {"slug":"alderbend/cta"} /-->' --porcelain)
PROJ_ID=$($WP post create --post_type=page --post_status=publish --post_title="Projects" --post_name=projects --menu_order=2 --porcelain)
ABOUT_ID=$($WP post create --post_type=page --post_status=publish --post_title="About" --post_name=about --menu_order=3 --post_content='<!-- wp:pattern {"slug":"alderbend/about"} /--><!-- wp:pattern {"slug":"alderbend/service-area"} /--><!-- wp:pattern {"slug":"alderbend/cta"} /-->' --porcelain)
CONTACT_ID=$($WP post create --post_type=page --post_status=publish --post_title="Contact" --post_name=contact --menu_order=4 --post_content='<!-- wp:pattern {"slug":"alderbend/contact"} /-->' --porcelain)
$WP option update show_on_front page
$WP option update page_on_front "$HOME_ID"
$WP option update page_for_posts "$PROJ_ID"

$WP term create category "Patios and paths" >/dev/null 2>&1 || true
$WP term create category "Planting" >/dev/null 2>&1 || true
$WP term create category "Walls" >/dev/null 2>&1 || true
$WP term create category "Drainage" >/dev/null 2>&1 || true
$WP term update category 1 --name="Sample projects" >/dev/null 2>&1 || true

project() { # slug title category excerpt body-line1 body-line2 date
	local slug=$1 title=$2 cat=$3 excerpt=$4 l1=$5 l2=$6 date=$7
	local content="<!-- wp:paragraph --><p>$l1</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class=\"wp-block-heading\">The brief</h2><!-- /wp:heading --><!-- wp:paragraph --><p>$l2</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class=\"wp-block-heading\">What the plan covers</h2><!-- /wp:heading --><!-- wp:list {\"className\":\"is-style-alderbend-checklist\"} --><ul class=\"wp-block-list is-style-alderbend-checklist\"><!-- wp:list-item --><li>Scaled sketch and planting list</li><!-- /wp:list-item --><!-- wp:list-item --><li>Itemised written quote</li><!-- /wp:list-item --><!-- wp:list-item --><li>Care sheet for the first year</li><!-- /wp:list-item --></ul><!-- /wp:list --><!-- wp:paragraph --><p><em>Alderbend is a made-up company. This project, its town and its details are an invented example for a theme demo, and the picture is an original illustration.</em></p><!-- /wp:paragraph -->"
	local id
	id=$($WP post create --post_status=publish --post_title="$title" --post_name="$slug" --post_excerpt="$excerpt" --post_date="$date" --post_content="$content" --porcelain)
	$WP post term set "$id" category "$cat" >/dev/null
	local img
	img=$($WP media import "$HERE/seed-images/$slug.webp" --post_id="$id" --title="$title" --alt="Illustration for the sample project: $title" --featured_image --porcelain 2>/dev/null || true)
	echo "post $id image ${img:-none}"
}
project terraced-front-yard "Terraced front yard, Millbrook Ridge" "Walls" "A steep front slope turned into three planted terraces with a stepping-stone path." "A sample project: three low terraces replace one steep, hard-to-mow slope." "The owners of this made-up house wanted to stop mowing a slope and still have something to look at from the street." "2026-09-12 09:00:00"
project rain-garden "Rain garden for a wet corner, Harrow Landing" "Drainage" "A soggy corner becomes a shallow rain garden with native plants." "A sample project: a downspout and a low corner become a shallow basin that fills after storms and drains within a day." "The brief was simple: keep the water away from the foundation and make the corner something you want to look at." "2026-08-03 09:00:00"
project flagstone-patio "Flagstone patio with a built-in bench, Ketterby" "Patios and paths" "An irregular flagstone patio on a compacted base, pitched away from the house." "A sample project: a sunny back corner gets a flagstone patio with room for a table and a low planter wall." "The owners wanted somewhere to eat outside that did not turn to mud after rain." "2026-07-18 09:00:00"
project meadow-strip "Native meadow strip, Pinecrest Falls" "Planting" "A strip of lawn swapped for a low-water native meadow and a mown path through it." "A sample project: the front strip of lawn becomes a native meadow with a mown path through the middle." "The brief: less mowing, less watering, more bees." "2026-06-21 09:00:00"
project raised-beds "Cedar raised beds, Oriel Heights" "Planting" "Three cedar raised beds for vegetables, herbs and cut flowers, with gravel paths between." "A sample project: three cedar beds sit on a gravel pad near the kitchen door, each planted for a different use." "The owners wanted vegetables at arm's length from the kitchen and beds tall enough to avoid kneeling." "2026-05-30 09:00:00"
project curved-wall "Curved stone wall, Sandy Ford" "Walls" "A curved dry-stack wall holds a raised bed along a sloping drive." "A sample project: a curved dry-stack wall holds back a bank beside the drive and doubles as a planted edge." "The slope kept sliding onto the drive after storms, so the wall needed to drain as well as look good." "2026-05-02 09:00:00"
$WP rewrite flush --hard
$WP cache flush
echo "seeded"
