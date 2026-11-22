<?php
/**
 * Title: Call to action
 * Slug: alderbend/cta
 * Description: A dark green band with a headline and one button.
 * Categories: alderbend
 * Keywords: cta, call to action, banner
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","gradient":"dusk","className":"ab-cta","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-cta has-dusk-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"textAlign":"center","textColor":"base"} -->
<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color"><?php esc_html_e( 'Not sure what your yard needs?', 'alderbend' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"sage","fontSize":"large"} -->
<p class="has-text-align-center has-sage-color has-text-color has-large-font-size"><?php esc_html_e( 'Book a free site walk. We will tell you what is worth doing and what can wait.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"base","textColor":"primary-dark"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-dark-color has-base-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request a quote', 'alderbend' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></section>
<!-- /wp:group -->
