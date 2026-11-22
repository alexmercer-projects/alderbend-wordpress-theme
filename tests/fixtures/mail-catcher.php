<?php
/**
 * Test helper: writes outgoing mail to a file instead of sending it.
 *
 * @package AlderbendTests
 */

add_filter(
	'pre_wp_mail',
	function ( $return, $atts ) {
		file_put_contents( WP_CONTENT_DIR . '/mail-log.json', wp_json_encode( $atts ) . "\n", FILE_APPEND );
		return true;
	},
	10,
	2
);
