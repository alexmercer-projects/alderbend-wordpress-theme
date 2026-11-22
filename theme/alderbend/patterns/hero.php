<?php
/**
 * Title: Hero
 * Slug: alderbend/hero
 * Description: Headline, short intro, two buttons and a garden illustration.
 * Categories: alderbend
 * Keywords: hero, landscaping, header
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ab-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-hero" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"ab-hero__cols","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center ab-hero__cols"><!-- wp:column {"verticalAlignment":"center","width":"56%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:56%"><!-- wp:paragraph {"className":"is-style-alderbend-kicker"} -->
<p class="is-style-alderbend-kicker"><?php esc_html_e( 'Landscape design and building in Millbrook Ridge', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Yards that look settled in from the first spring.', 'alderbend' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large","textColor":"muted"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'Alderbend designs and builds patios, paths, walls and planting for homes in six towns. One crew from first sketch to last mulch, and a written quote before anything starts.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Ask for a free site walk', 'alderbend' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-alderbend-ghost"} -->
<div class="wp-block-button is-style-alderbend-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'See recent projects', 'alderbend' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"44%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:44%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"is-style-alderbend-arch ab-hero__art"} -->
<figure class="wp-block-image size-full is-style-alderbend-arch ab-hero__art"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-garden.svg' ) ); ?>" alt="<?php esc_attr_e( 'Illustration of a garden with a stone path, young trees and flower beds.', 'alderbend' ); ?>"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
