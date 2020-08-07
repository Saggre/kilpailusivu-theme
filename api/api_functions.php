<?php

session_start();

function regenerate() {
	$_SESSION['code']      = uniqid();
	$_SESSION['code_time'] = time();
}

if ( empty( $_SESSION['code'] ) || time() - $_SESSION['code_time'] > 3600 ) {
	regenerate();
}

$uniqid = $_SESSION['code'];

// Load wp functionality
require( dirname( __FILE__ ) . '/../../../../wp-load.php' );

/**
 * Make sure that entrylikes table exists
 */
function check_table() {
	global $wpdb;

	// Set table name
	$table = $wpdb->prefix . 'entrylikes';

	$charset_collate = $wpdb->get_charset_collate();

	$query = "CREATE TABLE IF NOT EXISTS  " . $table . " (
            user_session_id VARCHAR(15),
            entry_id VARCHAR(255)
            )$charset_collate;";

	$wpdb->query( $query );
}

/**
 * Adds a like from the currently connected user for an entry
 *
 * @param $entry_id
 *
 * @return bool Returns true on success
 */
function like_entry( $entry_id ) {
	global $wpdb;
	global $uniqid;

	check_table();

	$sql    = $wpdb->prepare( "INSERT INTO " . $wpdb->prefix . "entrylikes (user_session_id, entry_id)
SELECT * FROM (SELECT %s, %s) AS tmp
WHERE NOT EXISTS (SELECT entry_id FROM wp_entrylikes WHERE user_session_id = %s AND entry_id = %s) LIMIT 1;", $uniqid, $entry_id, $uniqid, $entry_id );
	$result = $wpdb->query( $sql );

	//$result 0 means that the user already liked

	return true;
}

/**
 * Returns the number of likes for an entry
 *
 * @param $entry_id
 *
 * @return bool|int
 */
function get_entry_likes( $entry_id ) {
	global $wpdb;

	$sql    = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "entrylikes WHERE entry_id=%s LIMIT 9999;", $entry_id );
	$result = $wpdb->query( $sql );

	if ( ! $result ) {
		$result = 0;
	}

	return $result;
}

/**
 * Gets entries from wp
 *
 * @param $number
 * @param $offset
 *
 * @return array
 */
function get_entries( $number, $offset ) {
	$entries = array(
		"entries"    => array(),
		"post_count" => 0,
	);

	$args = array(
		'orderby'        => 'date',
		'order'          => 'DESC',
		'offset'         => $offset,
		'posts_per_page' => $number,
		'post_type'      => 'image-entry',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {
			$query->the_post();

			$id = get_the_ID();

			array_push( $entries["entries"], array(
				"id"    => get_the_ID(),
				"title" => get_the_title(),
				"name"  => get_field( "submitter", $id ),
				"image" => get_field( "image", $id ),
			) );
		}

	}

	wp_reset_postdata();

	$entries["post_count"] = wp_count_posts( 'image-entry' )->publish;

	return $entries;
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
function insert_entry( $title, $name, $email, $image ) {
	require_once( dirname( __FILE__ ) . '/../../../../wp-admin/includes/image.php' );

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

		wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $file_path ) );

	} else {
		return false;
	}

	if ( ! $id || ! class_exists( 'acf' ) || ! update_field( "submitter", $name, $id ) || ! update_field( "email", $email, $id ) || ! update_field( "image", $upload_id, $id ) ) {
		return false;
	}

	return true;
}