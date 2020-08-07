<?php

include_once 'api_functions.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$return = array(
		"error"     => false,
		"error_msg" => "",
		"likes"     => 0,
	);

	if ( ! empty( $_POST['entry_id'] ) && is_numeric( $_POST['entry_id'] ) ) {
		$entry_id = $_POST['entry_id'];
	} else {
		$return["error"]     = true;
		$return["error_msg"] = "Tapahtui tuntematon virhe";
		echo( json_encode( $return ) );
		exit;
	}

	$success = like_entry( $entry_id );

	if ( ! $success ) {
		$return["error"]     = true;
		$return["error_msg"] = "Tapahtui tuntematon virhe";
	}

	$return["likes"] = get_entry_likes( $entry_id );

	echo( json_encode( $return ) );
}