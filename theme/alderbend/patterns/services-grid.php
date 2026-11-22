<?php
/**
 * Title: Services grid
 * Slug: alderbend/services-grid
 * Description: Six service cards with icons.
 * Categories: alderbend
 * Keywords: services, cards, grid
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ab-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-section"><!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-alderbend-kicker"} -->
<p class="is-style-alderbend-kicker"><?php esc_html_e( 'What we do', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Six kinds of work, done by the same small crew.', 'alderbend' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"ab-cards","layout":{"type":"grid","minimumColumnWidth":"19rem"}} -->
<div class="wp-block-group ab-cards">

<!-- wp:group {"className":"is-style-alderbend-card ab-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-card ab-service"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-design.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Garden design', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'A scaled plan, a planting list and a walk-through of the plan on site before we dig.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-card ab-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-card ab-service"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-stone.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Patios and paths', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Flagstone, pavers and gravel on a compacted base, laid to drain away from the house.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-card ab-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-card ab-service"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-wall.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Retaining walls', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Dry-stacked and block walls up to four feet, with drainage stone behind them.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-card ab-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-card ab-service"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-plant.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Planting and beds', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Beds that fit the light and soil you actually have, mulched and labelled.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-card ab-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-card ab-service"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-water.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Drainage and rain gardens', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Swales, dry wells and rain gardens that give wet corners somewhere to go.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-alderbend-card ab-service","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-card ab-service"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ab-service__icon"} -->
<figure class="wp-block-image size-full ab-service__icon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-leaf.svg' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Seasonal upkeep', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Spring cleanup, summer pruning and fall mulch on a plan you can pause.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

</div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
