<?php
/**
 * Title: Seasonal calendar
 * Slug: alderbend/seasons
 * Description: Four seasonal panels with checklists.
 * Categories: alderbend
 * Keywords: seasons, upkeep, calendar
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"sage","className":"ab-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-section has-sage-background-color has-background"><!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-alderbend-kicker"} -->
<p class="is-style-alderbend-kicker"><?php esc_html_e( 'Seasonal upkeep', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'What a year of care looks like.', 'alderbend' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"ab-seasons","layout":{"type":"grid","minimumColumnWidth":"15rem"}} -->
<div class="wp-block-group ab-seasons">

<!-- wp:group {"className":"is-style-alderbend-panel","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-panel"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Spring', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Cleanup and bed edging', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Divide and replant perennials', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Check walls and paths after frost', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-panel","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-panel"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Summer', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Deep-water new plantings', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Prune after bloom', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Keep mulch two inches deep', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-panel","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-panel"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Fall', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Leaf and debris clear-out', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Plant bulbs and shrubs', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Add a fresh mulch layer', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-panel","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-panel"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Winter', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'Protect young shrubs', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Keep salt off stone and beds', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Plan next season\'s projects', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

</div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
