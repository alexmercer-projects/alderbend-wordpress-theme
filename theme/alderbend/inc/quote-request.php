<?php
/**
 * Quote request handling: validation, spam checks and the email to the site owner.
 *
 * @package Alderbend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validate and clean the raw form fields.
 *
 * Pure function (no globals, no output) so it can be tested on its own.
 *
 * @param array $raw Raw values, keys: name, email, phone, town, service, message.
 * @return array{values:array<string,string>,errors:array<string,string>}
 */
function alderbend_validate_quote( array $raw ) {
	$values = array(
		'name'    => isset( $raw['name'] ) ? sanitize_text_field( (string) $raw['name'] ) : '',
		'email'   => isset( $raw['email'] ) ? sanitize_email( (string) $raw['email'] ) : '',
		'phone'   => isset( $raw['phone'] ) ? sanitize_text_field( (string) $raw['phone'] ) : '',
		'town'    => isset( $raw['town'] ) ? sanitize_text_field( (string) $raw['town'] ) : '',
		'service' => isset( $raw['service'] ) ? sanitize_text_field( (string) $raw['service'] ) : '',
		'message' => isset( $raw['message'] ) ? sanitize_textarea_field( (string) $raw['message'] ) : '',
	);
	$errors = array();

	if ( '' === $values['name'] ) {
		$errors['name'] = __( 'Please tell us your name.', 'alderbend' );
	} elseif ( mb_strlen( $values['name'] ) > 80 ) {
		$errors['name'] = __( 'That name is a bit long. 80 characters at most.', 'alderbend' );
	}

	if ( '' === $values['email'] || ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'That email address does not look right.', 'alderbend' );
	}

	if ( '' !== $values['phone'] && ! preg_match( '/^[0-9+().\s-]{7,20}$/', $values['phone'] ) ) {
		$errors['phone'] = __( 'Use digits, spaces and dashes only, 7 to 20 characters.', 'alderbend' );
	}

	if ( '' === $values['town'] ) {
		$errors['town'] = __( 'Which town is the job in?', 'alderbend' );
	} elseif ( mb_strlen( $values['town'] ) > 60 ) {
		$errors['town'] = __( 'That town name is too long.', 'alderbend' );
	}

	if ( mb_strlen( $values['message'] ) < 10 ) {
		$errors['message'] = __( 'A sentence or two about the space helps us a lot (10 characters at least).', 'alderbend' );
	} elseif ( mb_strlen( $values['message'] ) > 3000 ) {
		$errors['message'] = __( 'Please keep it under 3,000 characters.', 'alderbend' );
	}

	return array(
		'values' => $values,
		'errors' => $errors,
	);
}

/**
 * Header-injection safe subject line.
 *
 * @param array $values Cleaned values.
 * @return string
 */
function alderbend_quote_subject( array $values ) {
	/* translators: 1: service, 2: town. */
	$subject = sprintf( __( 'Quote request: %1$s in %2$s', 'alderbend' ), $values['service'], $values['town'] );
	return trim( preg_replace( '/[\r\n]+/', ' ', $subject ) );
}

/**
 * Handle the form post (logged in or not).
 */
function alderbend_handle_quote() {
	$back = wp_get_referer();
	$back = $back ? $back : home_url( '/contact/' );
	$back = remove_query_arg( array( 'quote', 'qref' ), $back );

	if ( ! isset( $_POST['alderbend_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alderbend_nonce'] ) ), 'alderbend_quote' ) ) {
		wp_die( esc_html__( 'This form has expired. Please go back, reload the page and try again.', 'alderbend' ), '', array( 'response' => 403 ) );
	}

	// Honeypot: real visitors never see or fill this field. Pretend success.
	if ( ! empty( $_POST['company_site'] ) ) {
		wp_safe_redirect( add_query_arg( 'quote', 'sent', $back ) . '#quote-form' );
		exit;
	}

	$ip_key = 'alderbend_q_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown' );
	if ( get_transient( $ip_key ) ) {
		wp_safe_redirect( add_query_arg( 'quote', 'wait', $back ) . '#quote-form' );
		exit;
	}

	$raw = array();
	foreach ( array( 'name', 'email', 'phone', 'town', 'service', 'message' ) as $key ) {
		$raw[ $key ] = isset( $_POST[ 'q_' . $key ] ) ? wp_unslash( $_POST[ 'q_' . $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitised in alderbend_validate_quote().
	}
	$result = alderbend_validate_quote( $raw );

	if ( ! empty( $result['errors'] ) ) {
		$token = wp_generate_password( 20, false );
		set_transient( 'alderbend_qref_' . $token, $result, 5 * MINUTE_IN_SECONDS );
		wp_safe_redirect(
			add_query_arg(
				array(
					'quote' => 'error',
					'qref'  => $token,
				),
				$back
			) . '#quote-form'
		);
		exit;
	}

	$v    = $result['values'];
	$body = sprintf(
		/* translators: 1: name, 2: email, 3: phone, 4: town, 5: service, 6: message. */
		__( "Name: %1\$s\nEmail: %2\$s\nPhone: %3\$s\nTown: %4\$s\nService: %5\$s\n\n%6\$s", 'alderbend' ),
		$v['name'],
		$v['email'],
		'' === $v['phone'] ? '-' : $v['phone'],
		$v['town'],
		$v['service'],
		$v['message']
	);

	$headers = array( 'Reply-To: ' . $v['name'] . ' <' . $v['email'] . '>' );
	wp_mail( get_option( 'admin_email' ), alderbend_quote_subject( $v ), $body, $headers );

	set_transient( $ip_key, 1, MINUTE_IN_SECONDS );
	wp_safe_redirect( add_query_arg( 'quote', 'sent', $back ) . '#quote-form' );
	exit;
}
add_action( 'admin_post_nopriv_alderbend_quote', 'alderbend_handle_quote' );
add_action( 'admin_post_alderbend_quote', 'alderbend_handle_quote' );

/**
 * Status flag from the redirect, whitelisted.
 *
 * @return string sent|error|wait|''.
 */
function alderbend_quote_status() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display flag, whitelisted below.
	$status = isset( $_GET['quote'] ) ? sanitize_key( wp_unslash( $_GET['quote'] ) ) : '';
	return in_array( $status, array( 'sent', 'error', 'wait' ), true ) ? $status : '';
}

/**
 * Stored validation result for the redirect, if any.
 *
 * @return array
 */
function alderbend_quote_stored() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only token lookup.
	$token = isset( $_GET['qref'] ) ? sanitize_key( wp_unslash( $_GET['qref'] ) ) : '';
	if ( '' === $token || strlen( $token ) !== 20 ) {
		return array();
	}
	$stored = get_transient( 'alderbend_qref_' . $token );
	return is_array( $stored ) ? $stored : array();
}

/**
 * Field errors to show.
 *
 * @return array<string,string>
 */
function alderbend_quote_errors() {
	$stored = alderbend_quote_stored();
	return isset( $stored['errors'] ) && is_array( $stored['errors'] ) ? $stored['errors'] : array();
}

/**
 * Values to refill the form with after an error.
 *
 * @return array<string,string>
 */
function alderbend_quote_old_values() {
	$stored = alderbend_quote_stored();
	return isset( $stored['values'] ) && is_array( $stored['values'] ) ? $stored['values'] : array();
}
