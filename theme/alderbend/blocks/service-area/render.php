<?php
/**
 * Service area checker, server-side render.
 *
 * @package Alderbend
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// This file is included inside WordPress's block render callback, so these variables are local, not global.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

$towns = alderbend_clean_towns( isset( $attributes['towns'] ) ? $attributes['towns'] : array() );

if ( empty( $towns ) ) {
	return;
}

$uid      = wp_unique_id( 'ab-area-' );
$wrapper  = get_block_wrapper_attributes( array( 'class' => 'ab-area' ) );
$messages = array(
	/* translators: 1: town name, 2: weekday. */
	'found'    => __( 'Yes, we are in %1$s. Our crew is there on %2$ss.', 'alderbend' ),
	/* translators: %s: town name. */
	'foundAny' => __( 'Yes, we work in %s.', 'alderbend' ),
	'missing'  => __( 'That town is not on our route yet. Send a quote request anyway and we will tell you honestly.', 'alderbend' ),
);
?>
<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by get_block_wrapper_attributes(). ?> data-ab-area data-messages="<?php echo esc_attr( wp_json_encode( $messages ) ); ?>" data-towns="<?php echo esc_attr( wp_json_encode( $towns ) ); ?>">
	<label class="ab-area__label" for="<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'Check your town', 'alderbend' ); ?></label>
	<input class="ab-area__input" id="<?php echo esc_attr( $uid ); ?>" type="text" list="<?php echo esc_attr( $uid ); ?>-list" autocomplete="off" placeholder="<?php esc_attr_e( 'Start typing a town', 'alderbend' ); ?>" aria-describedby="<?php echo esc_attr( $uid ); ?>-result">
	<datalist id="<?php echo esc_attr( $uid ); ?>-list">
		<?php foreach ( $towns as $town ) : ?>
			<option value="<?php echo esc_attr( $town['name'] ); ?>"></option>
		<?php endforeach; ?>
	</datalist>
	<p class="ab-area__result" id="<?php echo esc_attr( $uid ); ?>-result" role="status" aria-live="polite"></p>
	<ul class="ab-area__list">
		<?php foreach ( $towns as $town ) : ?>
			<li>
				<span class="ab-area__town"><?php echo esc_html( $town['name'] ); ?></span>
				<?php if ( '' !== $town['day'] ) : ?>
					<span class="ab-area__day"><?php echo esc_html( $town['day'] ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
