<?php
/**
 * Unit-style checks that run inside WordPress.
 * Run: wp eval-file tests/theme-tests.php
 *
 * @package AlderbendTests
 */

$GLOBALS['ab_pass'] = 0;
$GLOBALS['ab_fail'] = 0;

function ab_ok( $cond, $label ) {
	if ( $cond ) {
		++$GLOBALS['ab_pass'];
		echo "  ok   $label\n";
	} else {
		++$GLOBALS['ab_fail'];
		echo "  FAIL $label\n";
	}
}

echo "Theme\n";
$theme = wp_get_theme( 'alderbend' );
ab_ok( $theme->exists(), 'theme is installed' );
ab_ok( $theme->is_block_theme(), 'WordPress sees it as a block theme' );
ab_ok( 'alderbend' === get_stylesheet(), 'theme is active' );
ab_ok( '1.0.0' === $theme->get( 'Version' ), 'version header read' );

echo "theme.json\n";
$settings = wp_get_global_settings();
$slugs    = wp_list_pluck( $settings['color']['palette']['theme'], 'slug' );
ab_ok( in_array( 'primary', $slugs, true ) && in_array( 'accent', $slugs, true ), 'palette has primary and accent' );
ab_ok( count( $settings['typography']['fontFamilies']['theme'] ) === 2, 'two font families (Fraunces, Figtree)' );
ab_ok( '74rem' === $settings['layout']['wideSize'], 'wide size is 74rem' );
$css = wp_get_global_stylesheet();
ab_ok( false !== strpos( $css, '--wp--preset--color--primary' ), 'global stylesheet emits palette variables' );
$faces = WP_Theme_JSON_Resolver::get_theme_data()->get_settings()['typography']['fontFamilies']['theme'][0]['fontFace'][0]['src'][0];
ab_ok( 'file:./assets/fonts/fraunces-latin-wght-normal.woff2' === $faces && file_exists( get_theme_file_path( 'assets/fonts/fraunces-latin-wght-normal.woff2' ) ), 'font files are declared in theme.json and exist on disk' );
ab_ok( false === strpos( $css, 'fonts.googleapis.com' ), 'no Google Fonts request' );
$variations = WP_Theme_JSON_Resolver::get_style_variations();
ab_ok( 1 === count( array_filter( $variations, fn( $v ) => 'Clay and sage' === ( $v['title'] ?? '' ) ) ), 'the Clay and sage style variation loads' );

echo "Templates and parts\n";
foreach ( array( 'front-page', 'page', 'page-wide', 'index', 'archive', 'single', 'search', '404' ) as $t ) {
	ab_ok( (bool) get_block_file_template( 'alderbend//' . $t, 'wp_template' ), "template $t" );
}
foreach ( array( 'header', 'footer' ) as $t ) {
	ab_ok( (bool) get_block_file_template( 'alderbend//' . $t, 'wp_template_part' ), "part $t" );
}

echo "Patterns and blocks\n";
$reg = WP_Block_Patterns_Registry::get_instance();
foreach ( array( 'hero', 'facts-strip', 'services-grid', 'featured-projects', 'process', 'seasons', 'service-area', 'cta', 'faq', 'services-detail', 'about', 'contact', 'page-heading', 'header-inner', 'footer-inner' ) as $p ) {
	ab_ok( $reg->is_registered( 'alderbend/' . $p ), "pattern alderbend/$p registered" );
}
$bt = WP_Block_Type_Registry::get_instance();
ab_ok( $bt->is_registered( 'alderbend/service-area' ), 'block alderbend/service-area registered' );
ab_ok( $bt->is_registered( 'alderbend/quote-form' ), 'block alderbend/quote-form registered' );
$styles = WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( 'core/group' );
ab_ok( isset( $styles['alderbend-card'] ) && isset( $styles['alderbend-panel'] ), 'group block styles registered' );

echo "alderbend_clean_towns()\n";
$clean = alderbend_clean_towns(
	array(
		array( 'name' => '  Ketterby  ', 'day' => 'Wednesday' ),
		array( 'name' => '<b>Sandy Ford</b>', 'day' => 'Sunday' ),
		array( 'name' => '', 'day' => 'Monday' ),
		'not an array',
		array( 'day' => 'Monday' ),
		array( 'name' => array( 'x' ) ),
	)
);
ab_ok( 2 === count( $clean ), 'drops empty, non-array and malformed rows' );
ab_ok( 'Ketterby' === $clean[0]['name'], 'trims names' );
ab_ok( 'Sandy Ford' === $clean[1]['name'], 'strips tags from names' );
ab_ok( '' === $clean[1]['day'], 'rejects a day outside Monday to Saturday' );
ab_ok( array() === alderbend_clean_towns( 'nope' ), 'non-array input gives an empty list' );

echo "alderbend_validate_quote()\n";
$good = alderbend_validate_quote(
	array(
		'name'    => 'Jo Example',
		'email'   => 'jo@example.com',
		'phone'   => '(555) 010-0199',
		'town'    => 'Ketterby',
		'service' => 'Patios and paths',
		'message' => 'We would like a small patio by the back door.',
	)
);
ab_ok( array() === $good['errors'], 'a complete request passes' );
$empty = alderbend_validate_quote( array() );
ab_ok( isset( $empty['errors']['name'], $empty['errors']['email'], $empty['errors']['town'], $empty['errors']['message'] ), 'empty request flags name, email, town and message' );
ab_ok( ! isset( $empty['errors']['phone'] ), 'phone is optional' );
$bad_mail = alderbend_validate_quote( array_merge( $good['values'], array( 'email' => 'jo@@example' ) ) );
ab_ok( isset( $bad_mail['errors']['email'] ), 'bad email is rejected' );
$bad_phone = alderbend_validate_quote( array_merge( $good['values'], array( 'phone' => 'call me maybe' ) ) );
ab_ok( isset( $bad_phone['errors']['phone'] ), 'letters in the phone field are rejected' );
$short = alderbend_validate_quote( array_merge( $good['values'], array( 'message' => 'hi' ) ) );
ab_ok( isset( $short['errors']['message'] ), 'a one-word message is rejected' );
$long = alderbend_validate_quote( array_merge( $good['values'], array( 'message' => str_repeat( 'a', 3001 ) ) ) );
ab_ok( isset( $long['errors']['message'] ), 'a 3,001 character message is rejected' );
$tags = alderbend_validate_quote( array_merge( $good['values'], array( 'name' => '<script>alert(1)</script>Jo' ) ) );
ab_ok( false === strpos( $tags['values']['name'], '<' ), 'tags are stripped from the name' );
ab_ok( mb_strlen( alderbend_validate_quote( array_merge( $good['values'], array( 'name' => str_repeat( 'x', 81 ) ) ) )['errors']['name'] ?? '' ) > 0, 'a name over 80 characters is rejected' );

echo "alderbend_quote_subject()\n";
$subject = alderbend_quote_subject(
	array(
		'service' => "Patios\r\nBcc: spam@example.com",
		'town'    => 'Ketterby',
	)
);
ab_ok( false === strpos( $subject, "\n" ) && false === strpos( $subject, "\r" ), 'line breaks are removed from the subject (header injection)' );

echo "\n{$GLOBALS['ab_pass']} passed, {$GLOBALS['ab_fail']} failed\n";
exit( $GLOBALS['ab_fail'] ? 1 : 0 );
