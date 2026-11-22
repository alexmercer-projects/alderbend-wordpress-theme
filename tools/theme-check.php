<?php
/**
 * Runs the WordPress.org Theme Check plugin against the theme. Run: wp eval-file tools/theme-check.php
 *
 * @package AlderbendTools
 */

require_once WP_PLUGIN_DIR . '/theme-check/theme-check.php';
require_once WP_PLUGIN_DIR . '/theme-check/checkbase.php';
$ab_theme = wp_get_theme( 'alderbend' );
$ab_pass  = run_themechecks_against_theme( $ab_theme, 'alderbend' );
echo 'Theme Check plugin: ' . ( $ab_pass ? 'no required items' : 'required items found' ) . "\n\n";
$ab_html = display_themechecks();
$ab_html = preg_replace( '#<li[^>]*>#', "\n- ", $ab_html );
echo html_entity_decode( wp_strip_all_tags( $ab_html ), ENT_QUOTES ) . "\n";
