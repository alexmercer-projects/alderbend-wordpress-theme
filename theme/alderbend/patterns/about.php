<?php
/**
 * Title: About
 * Slug: alderbend/about
 * Description: Short company story, values and a made-up crew note.
 * Categories: alderbend
 * Keywords: about, story
 * Viewport Width: 1400
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ab-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull ab-section"><!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'A small crew that likes finishing things.', 'alderbend' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Alderbend is a made-up landscaping company, invented to show what a WordPress theme can do for a small trade business. In the story, it is five people who design, build and look after yards in six towns.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'The way they work is the point of the site: one crew, a written quote, a fixed route day, and honest answers when a job is not worth doing.', 'alderbend' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-alderbend-checklist"} -->
<ul class="wp-block-list is-style-alderbend-checklist"><!-- wp:list-item -->
<li><?php esc_html_e( 'We build what we drew, or tell you why we changed it.', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Quotes list materials and labour separately.', 'alderbend' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'We clean up every day, not just at the end.', 'alderbend' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"is-style-alderbend-arch"} -->
<figure class="wp-block-image size-full is-style-alderbend-arch"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/project-curved-wall.svg' ) ); ?>" alt="<?php esc_attr_e( 'Illustration of a curved stone retaining wall with shrubs.', 'alderbend' ); ?>"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
