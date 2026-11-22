<?php
/**
 * Quote request form, server-side render.
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

$services = isset( $attributes['services'] ) && is_array( $attributes['services'] ) ? array_filter( array_map( 'sanitize_text_field', $attributes['services'] ) ) : array();
$label    = isset( $attributes['submitLabel'] ) ? sanitize_text_field( $attributes['submitLabel'] ) : __( 'Send my request', 'alderbend' );
$status   = alderbend_quote_status();
$errors   = alderbend_quote_errors();
$old      = alderbend_quote_old_values();
$uid      = wp_unique_id( 'ab-quote-' );
$wrapper  = get_block_wrapper_attributes(
	array(
		'class' => 'ab-quote',
		'id'    => 'quote-form',
	)
);

$field = static function ( $key ) use ( $old ) {
	return isset( $old[ $key ] ) ? $old[ $key ] : '';
};
?>
<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by get_block_wrapper_attributes(). ?>>
	<?php if ( 'sent' === $status ) : ?>
		<p class="ab-quote__notice is-ok" role="status"><?php esc_html_e( 'Thanks, your request is in. We reply within one working day.', 'alderbend' ); ?></p>
	<?php elseif ( 'error' === $status ) : ?>
		<p class="ab-quote__notice is-error" role="alert"><?php esc_html_e( 'Something needs a second look. Please check the highlighted fields.', 'alderbend' ); ?></p>
	<?php elseif ( 'wait' === $status ) : ?>
		<p class="ab-quote__notice is-error" role="alert"><?php esc_html_e( 'That was a quick second request. Please wait a minute and try again.', 'alderbend' ); ?></p>
	<?php endif; ?>

	<form class="ab-quote__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
		<input type="hidden" name="action" value="alderbend_quote">
		<?php wp_nonce_field( 'alderbend_quote', 'alderbend_nonce' ); ?>
		<p class="ab-quote__trap" aria-hidden="true">
			<label><?php esc_html_e( 'Leave this field empty', 'alderbend' ); ?> <input type="text" name="company_site" tabindex="-1" autocomplete="off"></label>
		</p>

		<div class="ab-quote__row">
			<p>
				<label for="<?php echo esc_attr( $uid ); ?>-name"><?php esc_html_e( 'Your name', 'alderbend' ); ?></label>
				<input id="<?php echo esc_attr( $uid ); ?>-name" name="q_name" type="text" autocomplete="name" required value="<?php echo esc_attr( $field( 'name' ) ); ?>"<?php echo isset( $errors['name'] ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $uid ) . '-name-err"' : ''; ?>>
				<?php
				if ( isset( $errors['name'] ) ) :
					?>
					<span class="ab-quote__err" id="<?php echo esc_attr( $uid ); ?>-name-err"><?php echo esc_html( $errors['name'] ); ?></span><?php endif; ?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $uid ); ?>-email"><?php esc_html_e( 'Email', 'alderbend' ); ?></label>
				<input id="<?php echo esc_attr( $uid ); ?>-email" name="q_email" type="email" autocomplete="email" required value="<?php echo esc_attr( $field( 'email' ) ); ?>"<?php echo isset( $errors['email'] ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $uid ) . '-email-err"' : ''; ?>>
				<?php
				if ( isset( $errors['email'] ) ) :
					?>
					<span class="ab-quote__err" id="<?php echo esc_attr( $uid ); ?>-email-err"><?php echo esc_html( $errors['email'] ); ?></span><?php endif; ?>
			</p>
		</div>
		<div class="ab-quote__row">
			<p>
				<label for="<?php echo esc_attr( $uid ); ?>-phone"><?php esc_html_e( 'Phone (optional)', 'alderbend' ); ?></label>
				<input id="<?php echo esc_attr( $uid ); ?>-phone" name="q_phone" type="tel" autocomplete="tel" value="<?php echo esc_attr( $field( 'phone' ) ); ?>"<?php echo isset( $errors['phone'] ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $uid ) . '-phone-err"' : ''; ?>>
				<?php
				if ( isset( $errors['phone'] ) ) :
					?>
					<span class="ab-quote__err" id="<?php echo esc_attr( $uid ); ?>-phone-err"><?php echo esc_html( $errors['phone'] ); ?></span><?php endif; ?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $uid ); ?>-town"><?php esc_html_e( 'Town', 'alderbend' ); ?></label>
				<input id="<?php echo esc_attr( $uid ); ?>-town" name="q_town" type="text" autocomplete="address-level2" required value="<?php echo esc_attr( $field( 'town' ) ); ?>"<?php echo isset( $errors['town'] ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $uid ) . '-town-err"' : ''; ?>>
				<?php
				if ( isset( $errors['town'] ) ) :
					?>
					<span class="ab-quote__err" id="<?php echo esc_attr( $uid ); ?>-town-err"><?php echo esc_html( $errors['town'] ); ?></span><?php endif; ?>
			</p>
		</div>
		<p>
			<label for="<?php echo esc_attr( $uid ); ?>-service"><?php esc_html_e( 'What do you need?', 'alderbend' ); ?></label>
			<select id="<?php echo esc_attr( $uid ); ?>-service" name="q_service">
				<?php foreach ( $services as $service ) : ?>
					<option value="<?php echo esc_attr( $service ); ?>" <?php selected( $field( 'service' ), $service ); ?>><?php echo esc_html( $service ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $uid ); ?>-msg"><?php esc_html_e( 'Tell us about the space', 'alderbend' ); ?></label>
			<textarea id="<?php echo esc_attr( $uid ); ?>-msg" name="q_message" rows="5" required<?php echo isset( $errors['message'] ) ? ' aria-invalid="true" aria-describedby="' . esc_attr( $uid ) . '-msg-err"' : ''; ?>><?php echo esc_textarea( $field( 'message' ) ); ?></textarea>
			<?php
			if ( isset( $errors['message'] ) ) :
				?>
				<span class="ab-quote__err" id="<?php echo esc_attr( $uid ); ?>-msg-err"><?php echo esc_html( $errors['message'] ); ?></span><?php endif; ?>
		</p>
		<p><button class="wp-element-button" type="submit"><?php echo esc_html( $label ); ?></button></p>
	</form>
</div>
