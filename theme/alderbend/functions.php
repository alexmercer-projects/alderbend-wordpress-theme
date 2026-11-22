<?php
/**
 * Alderbend theme setup.
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALDERBEND_VERSION', '1.0.0' );

require_once get_theme_file_path( 'inc/blocks.php' );
require_once get_theme_file_path( 'inc/quote-request.php' );

/**
 * Theme supports.
 */
function alderbend_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 800, true );
	add_image_size( 'alderbend-card', 720, 540, true );
}
add_action( 'after_setup_theme', 'alderbend_setup' );

/**
 * Front-end stylesheet. One small file, versioned by file time so edits bust the cache.
 */
function alderbend_enqueue_assets() {
	$file = get_theme_file_path( 'assets/css/theme.css' );
	wp_enqueue_style(
		'alderbend-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array(),
		file_exists( $file ) ? (string) filemtime( $file ) : ALDERBEND_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'alderbend_enqueue_assets' );

/**
 * Block styles that show up in the editor's Styles panel.
 */
function alderbend_register_block_styles() {
	register_block_style(
		'core/group',
		array(
			'name'  => 'alderbend-card',
			'label' => __( 'Card', 'alderbend' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'alderbend-panel',
			'label' => __( 'Soft panel', 'alderbend' ),
		)
	);
	register_block_style(
		'core/image',
		array(
			'name'  => 'alderbend-arch',
			'label' => __( 'Arch', 'alderbend' ),
		)
	);
	register_block_style(
		'core/list',
		array(
			'name'  => 'alderbend-checklist',
			'label' => __( 'Checklist', 'alderbend' ),
		)
	);
	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'alderbend-kicker',
			'label' => __( 'Kicker', 'alderbend' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'alderbend-ghost',
			'label' => __( 'Ghost', 'alderbend' ),
		)
	);
}
add_action( 'init', 'alderbend_register_block_styles' );

/**
 * Pattern categories.
 */
function alderbend_register_pattern_categories() {
	register_block_pattern_category(
		'alderbend',
		array( 'label' => __( 'Alderbend', 'alderbend' ) )
	);
}
add_action( 'init', 'alderbend_register_pattern_categories' );

/**
 * Add a "Project" label to the single-post template's body class so CSS can target it.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function alderbend_body_class( $classes ) {
	if ( is_singular( 'post' ) ) {
		$classes[] = 'is-project';
	}
	return $classes;
}
add_filter( 'body_class', 'alderbend_body_class' );
