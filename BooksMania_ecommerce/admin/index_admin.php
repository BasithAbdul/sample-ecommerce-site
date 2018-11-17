<?php 
	require_once '../sys/connectDB.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include 'includes/head_admin.php';
	include 'includes/nav_admin.php';
	
 ?>
 



 <?php include 'includes/footer_admin.php'; ?>