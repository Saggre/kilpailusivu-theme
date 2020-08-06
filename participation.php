<?php

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && ! empty( $_POST['action'] ) && $_POST['action'] == "new-participation" ) {

	$return = array(
		"error"     => false,
		"error_msg" => ""
	);

	$is_ajax = false;

	if ( isset ( $_POST['is-ajax'] ) ) {
		$is_ajax = boolval( $_POST['is-ajax'] );
	}

	// Load wp functionality
	require( dirname( __FILE__ ) . '/../../../wp-load.php' );

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

	if ( ! $return["error"] && ! insert_participation( $title, $name, $email, $image ) ) {
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

/**
 * Inserts a new image-entry post type with input data
 * Returns true on success
 *
 * @param $title
 * @param $name
 * @param $email
 * @param $image
 *
 * @return bool
 */
function insert_participation( $title, $name, $email, $image ) {
	$upload_dir = wp_upload_dir();

	$new_participation = array(
		'post_title'    => $title,
		'post_content'  => "",
		'post_category' => array(),
		'tags_input'    => array(),
		'post_status'   => 'publish',
		'post_type'     => 'image-entry'
	);

	$id = wp_insert_post( $new_participation );

	// Save image
	$i         = 1;
	$file_path = $upload_dir['path'] . '/' . $image['name'];
	while ( file_exists( $file_path ) ) {
		$i ++;
		$file_path = $upload_dir['path'] . '/' . $i . '_' . $image['name'];
	}

	$mime = mime_content_type( $image['tmp_name'] );
	if ( move_uploaded_file( $image['tmp_name'], $file_path ) ) {

		$upload_id = wp_insert_attachment( array(
			'guid'           => $file_path,
			'post_mime_type' => $mime,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $image['name'] ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		), $file_path );

		// For wp_generate_attachment_metadata()
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $file_path ) );

	} else {
		return false;
	}

	if ( ! $id || ! class_exists( 'acf' ) || ! update_field( "submitter", $name, $id ) || ! update_field( "email", $email, $id ) || ! update_field( "image", $upload_id, $id ) ) {
		return false;
	}

	return true;
}