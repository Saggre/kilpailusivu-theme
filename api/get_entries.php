<?php

include_once 'api_functions.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$return = array(
		"error"      => false,
		"error_msg"  => "",
		"post_count" => 0,
		"entries"    => array(),
	);

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

	$entries = get_entries( $number, $offset );

	$return["post_count"] = $entries["post_count"];
	$return["entries"]    = $entries["entries"];

	echo( json_encode( $return ) );
}
