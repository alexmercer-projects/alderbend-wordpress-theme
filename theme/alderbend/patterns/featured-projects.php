<?php
/**
 * Title: Featured projects
 * Slug: alderbend/featured-projects
 * Description: Latest three posts as project cards.
 * Categories: alderbend
 * Keywords: projects, query, gallery
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"surface","className":"ab-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-section has-surface-background-color has-background"><!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"className":"ab-section__head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group ab-section__head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"is-style-alderbend-kicker"} -->
<p class="is-style-alderbend-kicker"><?php esc_html_e( 'Recent projects', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Sample projects (illustrated, made up).', 'alderbend' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'All projects', 'alderbend' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"ab-project-grid","layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"ab-project","layout":{"type":"default"}} -->
<div class="wp-block-group ab-project"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","sizeSlug":"alderbend-card"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->

<!-- wp:post-excerpt {"excerptLength":22,"textColor":"muted"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
