<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	include 'includes/head_admin.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}

	$hashed=$user_data['password'];
	$old_password=(isset($_POST['old_password']))?sanitize($_POST['old_password']):'';
	$old_password=trim($old_password);
	$password=(isset($_POST['password']))?sanitize($_POST['password']):'';
	$password=trim($password);
	$confirm_pass=(isset($_POST['confirm_pass']))?sanitize($_POST['confirm_pass']):'';
	$confirm_pass=trim($confirm_pass);
?>

	<div class="container" >
 		<div class="col-md-6" id="login-form">
 			<h2 class="text-center">Change Password</h2>
 			<hr>
 			<div>
 				<?php 
 					$errors=array();
 					if ($_POST) {
 						if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm_pass'])) {
 							$errors[]="You must not leave any field blank.";
 						}
 						else{
	 						if(!password_verify($old_password, $hashed))
	 						{
	 							$errors[]="Old password is incorrect.";
	 						}
	 						else
	 							{
	 							if(strlen($password)<6)
	 							{
	 								$errors[]="Password length must be atleast 6 characters.";
	 							}
	 						}

	 						if($password!=$confirm_pass)
	 						{
	 							$errors[]="New passwords do not match.";
	 						}
	 						
 						}
 						if (!empty($errors)) {
	 						echo disp_err($errors);
	 					}
	 					else
	 					{
	 						$password=password_hash($password, PASSWORD_DEFAULT);
	 						$id=$user_data['id'];
	 						$changequery="UPDATE users SET password='$password' WHERE id=$id";
	 						mysqli_query($dbc, $changequery);
	 						$_SESSION['success_m']="Your password has been updated";
	 						header('Location: index_admin.php');
	 					}

 					}
 				 ?>	
 			</div>

 			<form action="change_password.php" method="post">
 				<div class="form-group">
 					<label for="old_password">Old Password: </label>
 					<input type="password" name="old_password" class="form-control" value="<?= $old_password; ?>">
 				</div>
 				<div class="form-group">
 					<label for="password">New Password: </label>
 					<input type="password" name="password" class="form-control" value="<?= $password; ?>">
 				</div>
 				<div class="form-group">
 					<label for="confirm_pass">Confirm New Password: </label>
 					<input type="password" name="confirm_pass" class="form-control" value="<?= $confirm_pass; ?>">
 				</div>
 				<div class="form-group">
 					<a href="index_admin.php" class="btn btn-secondary">Cancel</a>
 					<input type="submit" name="submit" value="Change Password" class="btn btn-success">
 				</div>
 			</form>
 			<div class="text-right">
 			<small><a href="../index.php" >Visit Homepage</a></small>
 			</div>
 		</div>
 	</div>


<?php include 'includes/footer_admin.php'; ?>