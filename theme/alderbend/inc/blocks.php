<?php
/**
 * Registers the two dynamic blocks that ship with the theme.
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register block types from their block.json files.
 */
function alderbend_register_blocks() {
	register_block_type( get_theme_file_path( 'blocks/service-area' ) );
	register_block_type( get_theme_file_path( 'blocks/quote-form' ) );
}
add_action( 'init', 'alderbend_register_blocks' );

/**
 * Normalise the service-area attribute into a clean list of towns.
 *
 * Anything that is not a non-empty name is dropped; days are limited to Monday to Saturday.
 *
 * @param mixed $towns Raw attribute value.
 * @return array<int,array{name:string,day:string}>
 */
function alderbend_clean_towns( $towns ) {
	$days  = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
	$clean = array();

	if ( ! is_array( $towns ) ) {
		return $clean;
	}

	foreach ( $towns as $town ) {
		if ( ! is_array( $town ) || empty( $town['name'] ) || ! is_string( $town['name'] ) ) {
			continue;
		}
		$name = trim( sanitize_text_field( $town['name'] ) );
		$day  = isset( $town['day'] ) && in_array( $town['day'], $days, true ) ? $town['day'] : '';
		if ( '' === $name ) {
			continue;
		}
		$clean[] = array(
			'name' => $name,
			'day'  => $day,
		);
	}

	return $clean;
}
