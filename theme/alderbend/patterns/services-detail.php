<?php
/**
 * Title: Services in detail
 * Slug: alderbend/services-detail
 * Description: Six service rows with checklists, for the Services page.
 * Categories: alderbend
 * Keywords: services, detail, list
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ab-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-section">

<!-- wp:columns {"align":"wide","className":"ab-detail","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide ab-detail"><!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-design.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Garden design', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Every job starts with a scaled plan so you can see the whole yard before we touch it.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Scaled plan and planting list', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Two rounds of changes included', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Plan reviewed on site before work starts', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide","className":"ab-detail","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide ab-detail"><!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-stone.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Patios and paths', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Stone and pavers on a compacted gravel base, pitched so water leaves the house side.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Flagstone, pavers or gravel', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Edging that keeps its line', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Steps and landings built to code', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide","className":"ab-detail","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide ab-detail"><!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-wall.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Retaining walls', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Walls that hold soil and let water through. We build dry-stack and block walls up to four feet.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Drainage stone and pipe behind every wall', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Geogrid on taller walls', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'We flag permit needs at the quote stage', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide","className":"ab-detail","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide ab-detail"><!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-plant.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Planting and beds', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Plants chosen for your light, soil and the time you want to spend. No fussy beds unless you ask.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Soil test and amendment', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Mulch and edging', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Plant list with care notes', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide","className":"ab-detail","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide ab-detail"><!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-water.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Drainage and rain gardens', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Wet spots get a destination: a swale, a dry well or a rain garden that is pretty when it is wet.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Grading and swales', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Dry wells and downspout routing', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Rain gardens with native plants', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns {"align":"wide","className":"ab-detail","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide ab-detail"><!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-leaf.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Seasonal upkeep', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%"><!-- wp:paragraph -->
<p><?php esc_html_e( 'A simple plan for the four times a year a yard really needs a person.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Spring cleanup and fall mulch', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Summer pruning visits', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Pause or cancel any time', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

</section>
<!-- /wp:group -->
