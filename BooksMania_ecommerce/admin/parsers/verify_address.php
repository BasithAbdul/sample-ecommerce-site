<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';

	$full_name=sanitize($_POST['full_name']);
	$email=sanitize($_POST['email']);
	$phone=(int)sanitize($_POST['phone']);
	$addr=sanitize($_POST['addr']);
	$pin=(int)sanitize($_POST['pin']);
	$city=sanitize($_POST['city']);

	$errors=array();

	$required=array(
		'full_name'    => 'Full Name',
		'email' => 'Email',
		'phone' => 'Phone',
		'addr'  => 'Street Address',
		'pin'   => 'Pincode',
		'city'  => 'City',
	);

	foreach ($required as $field => $f) {
		if (empty($_POST[$field]) || $_POST[$field]=='') {
			$errors[]="You must not leave any field empty";
			break;
		}
	}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[]="Please enter a valid email address";
		}

		if (!empty($errors)) {
			echo disp_err($errors);
		}
		else{
			echo "successful";
		}
	

 ?>