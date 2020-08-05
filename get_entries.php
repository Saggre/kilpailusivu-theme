<?php

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$return = array(
		"error"     => false,
		"error_msg" => "",
		"entries"   => array(),
	);

	// Load wp functionality
	require( dirname( __FILE__ ) . '/../../../wp-load.php' );

	if ( ! class_exists( 'acf' ) ) {
		$return["error"] = true;
		echo( json_encode( $return ) );
		exit;
	}

	if ( ! empty ( $_POST['number'] ) ) {
		$number = sanitize_text_field( $_POST['title'] );
	} else {
		$number = 8;
	}

	if ( ! empty( $_POST['offset'] ) ) {
		$offset = sanitize_text_field( $_POST['name'] );
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

	// The Loop
	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {
			$query->the_post();

			$id = get_the_ID();

			array_push( $return["entries"], array(
				"title" => get_the_title(),
				"name"  => get_field( "submitter", $id ),
				"email" => get_field( "email", $id ),
				"image" => get_field( "image", $id ),
			) );
		}

	} else {
		$return["error"] = true;
	}

	wp_reset_postdata();

	echo( json_encode( $return ) );

}
