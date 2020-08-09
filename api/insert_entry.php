<?php

include_once 'api_functions.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$return = array(
		"error"     => false,
		"error_msg" => ""
	);

	if ( ! isset( $_POST['nonce_field'] ) || ! wp_verify_nonce( $_POST['nonce_field'], 'insert_entry' ) ) {
		$return["error"]     = true;
		$return["error_msg"] = "Tapahtui tuntematon virhe";
		echo( json_encode( $return ) );
		exit;
	}

	$is_ajax = false;

	if ( isset ( $_POST['is-ajax'] ) ) {
		$is_ajax = boolval( $_POST['is-ajax'] );
	}

	if ( ! empty ( $_POST['title'] ) ) {
		$title = sanitize_text_field( $_POST['title'] );
	} else {
		$return["error"]     = true;
		$return["error_msg"] = "Anna kuvalle otsikko";
	}

	if ( ! empty( $_POST['name'] ) ) {
		$name = sanitize_text_field( $_POST['name'] );
	} else {
		$return["error"]     = true;
		$return["error_msg"] = "Anna nimesi";
	}

	if ( ! empty ( $_POST['email'] ) ) {
		$email = sanitize_text_field( $_POST['email'] );
	} else {
		$return["error"]     = true;
		$return["error_msg"] = "Anna sähköpostiosoitteesi";
	}

	if ( ! empty ( $_FILES['image'] ) && $_FILES['image']['error'] == 0 ) {
		$image = $_FILES['image'];

		if ( $image['size'] > wp_max_upload_size() ) {
			$return["error"]     = true;
			$return["error_msg"] = "Kuva on liian iso";
		}

		if ( ! in_array( mime_content_type( $image['tmp_name'] ), get_allowed_mime_types() ) ) {
			$return["error"]     = true;
			$return["error_msg"] = "Kuvan tiedostomuoto on väärä";
		}
	} else {
		$return["error"]     = true;
		$return["error_msg"] = "Lisää kuva osallistuaksesi";
	}

	if ( ! isset ( $_POST['checkbox'] ) ) {
		$return["error"]     = true;
		$return["error_msg"] = "Hyväksy kilpailun ehdot osallistuaksesi";
	}

	if ( ! $return["error"] && ! insert_entry( $title, $name, $email, $image ) ) {
		$return["error"]     = true;
		$return["error_msg"] = "Tapahtui tuntematon virhe. Yritä uudestaan";
	}

	if ( ! $is_ajax ) {
		wp_redirect( home_url() );
		exit;
	} else {
		echo( json_encode( $return ) );
	}
}