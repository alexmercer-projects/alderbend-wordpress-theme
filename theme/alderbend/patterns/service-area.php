<?php
/**
 * Title: Service area
 * Slug: alderbend/service-area
 * Description: Service area checker block with a short heading.
 * Categories: alderbend
 * Keywords: area, towns, checker
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ab-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-section"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%"><!-- wp:paragraph {"className":"is-style-alderbend-kicker"} -->
<p class="is-style-alderbend-kicker"><?php esc_html_e( 'Where we work', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Six towns, one route day each.', 'alderbend' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'Keeping to a fixed day per town means the crew is never an hour from your yard when something needs a look. Type your town to see ours.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%"><!-- wp:alderbend/service-area /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
