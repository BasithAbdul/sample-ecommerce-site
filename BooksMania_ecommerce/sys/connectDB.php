<?php 
	$dbc=mysqli_connect('localhost', 'root', '1997', 'booksmania');
	if(mysqli_connect_errno())
	{
		echo "Connection to database has failed due to the following errors: ".mysqli_connect_error();
		die();
	}
	
	

	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/helpers.php';
	require $_SERVER['DOCUMENT_ROOT'].'/BooksMania/vendor/autoload.php';

	$cart_id='';
	
		if(isset($_COOKIE[CART_COOKIE]))
		{
			$cart_id=sanitize($_COOKIE[CART_COOKIE]);
		}

	if (isset($_SESSION['user'])) {
		$user_id=$_SESSION['user'];
		$query="SELECT * FROM users WHERE id=$user_id";
		$user_data=mysqli_fetch_assoc(mysqli_query($dbc, $query));
		$full_name=explode(' ', $user_data['full_name']);
		$fn=$full_name[0];
		$ln=$full_name[1];
	}
	
	if (isset($_SESSION['success_m'])) {
	echo '<div class="alert alert-success text-center">'.$_SESSION['success_m'].'</div>';
	unset($_SESSION['success_m']);
	}

	if (isset($_SESSION['error_m'])) {
	echo '<div class="alert alert-danger text-center">'.$_SESSION['error_m'].'</div>';
	unset($_SESSION['error_m']);
	}




 ?>