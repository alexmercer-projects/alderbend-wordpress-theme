<?php
/**
 * Title: Page heading
 * Slug: alderbend/page-heading
 * Description: Kicker, page title and intro for inner pages.
 * Categories: alderbend
 * Keywords: heading, title
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"surface","className":"ab-pagehead","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-pagehead has-surface-background-color has-background"><!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-alderbend-kicker"} -->
<p class="is-style-alderbend-kicker"><?php esc_html_e( 'Alderbend Landscape Co.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-title {"level":1} /--></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
