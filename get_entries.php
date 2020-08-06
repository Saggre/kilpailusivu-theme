<?php

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$return = array(
		"error"      => false,
		"error_msg"  => "",
		"post_count" => 0,
		"entries"    => array(),
	);

	// Load wp functionality
	require( dirname( __FILE__ ) . '/../../../wp-load.php' );

	if ( ! class_exists( 'acf' ) ) {
		$return["error"] = true;
		echo( json_encode( $return ) );
		exit;
	}

	if ( ! empty ( $_POST['number'] ) && is_numeric( $_POST['number'] ) ) {
		$number = $_POST['number'];
	} else {
		$number = 8;
	}

	if ( ! empty( $_POST['offset'] ) && is_numeric( $_POST['offset'] ) ) {
		$offset = $_POST['offset'];
	} else {
		$offset = 0;
	}

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

			array_push( $return["entries"], array(
				"title" => get_the_title(),
				"name"  => get_field( "submitter", $id ),
				"image" => get_field( "image", $id ),
			) );
		}

	} else {
		$return["error"] = true;
	}

	wp_reset_postdata();

	$return["post_count"] = wp_count_posts( 'image-entry' )->publish;

	echo( json_encode( $return ) );

}
