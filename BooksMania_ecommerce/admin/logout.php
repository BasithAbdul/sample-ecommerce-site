<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	include 'includes/head_admin.php';
	unset($_SESSION['user']);
	header('Location: login.php');
?>



<?php include 'includes/footer_admin.php'; ?>