<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	include 'includes/head_admin.php';


	$email=(isset($_POST['email']))?sanitize($_POST['email']):'';
	$password=(isset($_POST['password']))?sanitize($_POST['password']):'';
	$email=trim($email);
	$password=trim($password);
	$password_hashed=password_hash($password, PASSWORD_DEFAULT);
 ?>

 	<div class="container" >
 		<div class="col-md-6" id="login-form">
 			<h2 class="text-center">Login</h2>
 			<hr>
 			<div>
 				<?php 
 					$errors=array();
 					if ($_POST) {
 						if (empty($_POST['email']) || empty($_POST['password'])) {
 							$errors[]="You must provide both email and password.";
 						}
 						else{
	 						if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	 						{
	 							$errors[]="Please enter valid email address.";
	 						}
	 						if(strlen($password)<6)
	 						{
	 							$errors[]="Password length must be atleast 6 characters.";
	 						}

	 						$query="SELECT * FROM users WHERE email='$email'";
	 						$result=mysqli_query($dbc, $query);
	 						$user=mysqli_fetch_assoc($result);
	 						$userC=mysqli_num_rows($result);
	 						if($userC==0)
	 						{
	 							$errors[]="User doesn't exist.";
	 						}

	 						if(!password_verify($password, $user['password']))
	 						{
	 							$errors[]="Incorrect Password.";
	 						}
	 						
 						}
 						if (!empty($errors)) {
	 						echo disp_err($errors);
	 					}
	 					else
	 					{
	 						$user_id=$user['id'];
	 						login($user_id);
	 					}

 					}
 				 ?>	
 			</div>

 			<form action="login.php" method="post">
 				<div class="form-group">
 					<label for="email">Email: </label>
 					<input type="text" name="email" class="form-control" value="<?= $email; ?>">
 				</div>
 				<div class="form-group">
 					<label for="password">Password: </label>
 					<input type="password" name="password" class="form-control" value="<?= $password; ?>">
 				</div>
 				<div class="form-group">
 					<input type="submit" name="submit" value="Login" class="btn btn-success">
 				</div>
 			</form>
 			<div class="text-right	">
 			<small><a href="../index.php" >Visit Homepage</a></small>
 			</div>
 		</div>
 	</div>


 <?php 
 	include 'includes/footer_admin.php';
  ?>