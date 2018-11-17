<?php 
	require_once '../sys/connectDB.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	if(!has_permissions())
	{
		permissions_error_redirect();
	}
	include 'includes/head_admin.php';
	include 'includes/nav_admin.php';
	$users_query="SELECT * FROM users ORDER BY full_name";
	$users_result=mysqli_query($dbc, $users_query);

	if (isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id=$_GET['delete'];
		$delete_query="DELETE FROM users WHERE id=$delete_id";
		mysqli_query($dbc, $delete_query);
		$_SESSION['success_m']="User has been deleted";
		header('Location: users.php');
	}


	if (isset($_GET['add']) && !empty($_GET['add'])) {
		$fn=(isset($_POST['fn']) && !empty($_POST['fn']))?sanitize($_POST['fn']):'';
		$ln=(isset($_POST['ln']) && !empty($_POST['ln']))?sanitize($_POST['ln']):'';
		$email=(isset($_POST['email']) && !empty($_POST['email']))?sanitize($_POST['email']):'';
		$permissions=(isset($_POST['permissions']) && !empty($_POST['permissions']))?sanitize($_POST['permissions']):'';
		$password=(isset($_POST['password']) && !empty($_POST['password']))?sanitize($_POST['password']):'';
		$confirm_pass=(isset($_POST['confirm_pass']) && !empty($_POST['confirm_pass']))?sanitize($_POST['confirm_pass']):'';

		if($_POST)
		{

		$errors=array();
		$fields=['fn', 'ln', 'email', 'permissions', 'password', 'confirm_pass'];

		foreach ($fields as $field) {
			if($_POST["$field"]==='')
			{
				$errors[]="You must not leave any field empty.";
				break;
			}
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$errors[]="You must enter a valid email address.";
		}

		if(strlen($password)<8)
		{
			$errors[]="Password must be atleast 8 characters long.";
		}
		if($password!==$confirm_pass)
		{
			$errors[]="Password and confirm password do not match";
		}
		$exist_query="SElECT * FROM users WHERE email='$email'";
		$exist_result=mysqli_query($dbc, $exist_query);
		$num=mysqli_num_rows($exist_result);
		if($num>0)
		{
			$errors[]="Email already exists. Please enter another email.";
		}

		if(!empty($errors))
		{
			echo disp_err($errors);
		}
		else{
			$full_name=$fn.' '.$ln;
			$hashed=password_hash($password, PASSWORD_DEFAULT);
			$add_query="INSERT INTO users (`full_name`, `email`, `password`, `permissions`) VALUES ('$full_name', '$email', '$hashed', '$permissions')";
			mysqli_query($dbc, $add_query);
			$_SESSION['success_m']="User has been successfully added";
			header('Location: users.php');
			}

	}

?>
	<div class="container">
		<h2 class="text-center">Add a New User</h2>
		<hr>
			<form action="users.php?add=1" method="post">
				<div class="col-md-6 form-group">
					<label for="fn">First Name:</label>
					<input type="text" name="fn" class="form-control" value="<?= $fn ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="ln">Last Name:</label>
					<input type="text" name="ln" class="form-control" value="<?= $ln ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="email">Email:</label>
					<input type="text" name="email" class="form-control" value="<?= $email ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="fn">Password: </label>
					<input type="password" name="password" class="form-control" value="<?= $password ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="fn">Confirm Password: </label>
					<input type="password" name="confirm_pass" class="form-control" value="<?= $confirm_pass ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="permissions">Permissions</label>
					<select name="permissions" class="form-control">
						<option value="" <?= $permissions==''?'selected':'' ?>></option>
						<option value="admin,editor" <?= $permissions=='admin,editor'?'selected':'' ?>>Admin, Editor</option>
						<option value="editor" <?= $permissions=='editor'?'selected':'' ?>>Editor</option>
					</select>
				</div>
				<div class="form-group col-md-3">
					<a href="users.php" class="btn btn-secondary">Cancel</a>
					<input type="submit" name="submit" class="btn btn-success" value="Add User">
				</div>

			</form>
	</div>



		
	<?php 
	}
	
	else{
 ?>
 
<div class="container">
	<h2 class="text-center">Users</h2>
	<a href="users.php?add=1" class="btn btn-success pull-right" id="grp-btn">Add a User</a>
	<div class="clearfix"></div>
	<hr>
	<table class="table table-bordered table-striped">
		<thead>
			<th>Full Name</th>
			<th>E-mail</th>
			<th>Join Date</th>
			<th>Last Login</th>
			<th>Permissions</th>
			<th></th>
		</thead>
		<tbody>
			<?php while ($users=mysqli_fetch_assoc($users_result)) { ?>
				<tr>
					<td><?= $users['full_name'] ?></td>	
					<td><?= $users['email'] ?></td>	
					<td><?= $users['join_date'] ?></td>	
					<td><?=($users['last_login']==='0000-00-00 00:00:00')?'Never':$users['last_login'] ?></td>
					<td><?= $users['permissions'] ?></td>	
					<td>
						<a href="users.php?edit=<?= $users['id'] ?>"><span class="fa fa-edit text-secondary"></span></a>
						<?php if ($users['id']!==$user_data['id']): ?>
						<a href="users.php?delete=<?= $users['id'] ?>"><span class="fa fa-trash text-danger"></span></a>	
						<?php endif ?>
						
					</td>
				</tr>
			<?php } ?>	
		</tbody>
	</table>
</div>
<?php } ?>


 <?php include 'includes/footer_admin.php'; ?>