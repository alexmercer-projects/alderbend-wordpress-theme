<?php
/**
 * Title: Back to home button
 * Slug: alderbend/home-button
 * Description: One button that links to the home page.
 * Categories: alderbend
 * Inserter: no
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the home page', 'alderbend' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
