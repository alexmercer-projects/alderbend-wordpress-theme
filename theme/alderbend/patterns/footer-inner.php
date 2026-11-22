<?php
/**
 * Title: Footer
 * Slug: alderbend/footer-inner
 * Description: Contact details, links and the demo disclaimer.
 * Categories: alderbend
 * Keywords: footer
 * Viewport Width: 1400
 * Inserter: no
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"align":"full","backgroundColor":"primary-dark","textColor":"sage","className":"ab-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull ab-footer has-sage-color has-primary-dark-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:site-title {"level":0,"className":"ab-footer__brand"} /-->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Landscape design and building for homes in Millbrook Ridge, Harrow Landing, Ketterby, Pinecrest Falls, Oriel Heights and Sandy Ford.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Get in touch', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="tel:+15550100142">(555) 010-0142</a><br><a href="mailto:hello@example.com">hello@example.com</a><br><?php esc_html_e( 'Mon to Sat, 7 am to 5 pm', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'The site', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'alderbend' ); ?></a><br><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'Projects', 'alderbend' ); ?></a><br><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'alderbend' ); ?></a><br><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'alderbend' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"align":"wide","fontSize":"small","className":"ab-footer__legal"} -->
<p class="alignwide ab-footer__legal has-small-font-size"><?php esc_html_e( 'Alderbend Landscape Co. is a made-up business. This is a concept demo of a WordPress theme, not a real company or client work.', 'alderbend' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
