<?php
/**
 * Title: Contact
 * Slug: alderbend/contact
 * Description: Quote form beside contact details.
 * Categories: alderbend
 * Keywords: contact, form, quote
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
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:alderbend/quote-form /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:group {"className":"is-style-alderbend-panel","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-alderbend-panel"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Prefer to call?', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="tel:+15550100142">(555) 010-0142</a><br><a href="mailto:hello@example.com">hello@example.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Mon to Sat, 7 am to 5 pm. Leave a message after hours and we call back the next working day.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'What happens next', 'alderbend' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'We reply within one working day.', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'We book a free site walk.', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'You get a written quote in three days.', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
