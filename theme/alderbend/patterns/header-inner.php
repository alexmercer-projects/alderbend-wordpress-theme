<?php
/**
 * Title: Header
 * Slug: alderbend/header-inner
 * Description: Brand, navigation and quote button.
 * Categories: alderbend
 * Keywords: header
 * Viewport Width: 1400
 * Inserter: no
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"align":"full","className":"ab-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull ab-header"><!-- wp:group {"align":"wide","className":"ab-header__inner","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide ab-header__inner"><!-- wp:site-title {"level":0,"className":"ab-brand"} /-->

<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"},"className":"ab-nav"} -->
<!-- wp:navigation-link {"label":"<?php echo esc_attr_x( 'Services', 'menu item', 'alderbend' ); ?>","type":"page","url":"<?php echo esc_url( home_url( '/services/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"<?php echo esc_attr_x( 'Projects', 'menu item', 'alderbend' ); ?>","type":"page","url":"<?php echo esc_url( home_url( '/projects/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"<?php echo esc_attr_x( 'About', 'menu item', 'alderbend' ); ?>","type":"page","url":"<?php echo esc_url( home_url( '/about/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"<?php echo esc_attr_x( 'Contact', 'menu item', 'alderbend' ); ?>","type":"page","url":"<?php echo esc_url( home_url( '/contact/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation -->

<!-- wp:buttons {"className":"ab-header__cta"} -->
<div class="wp-block-buttons ab-header__cta"><!-- wp:button {"className":"is-style-alderbend-ghost"} -->
<div class="wp-block-button is-style-alderbend-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get a quote', 'alderbend' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
