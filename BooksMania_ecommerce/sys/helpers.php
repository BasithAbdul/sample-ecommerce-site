<?php 
	function disp_err($errors)
	{
		$result='<ul class="list-unstyled">';
		foreach ($errors as $error) {
			$result.='<small><li class=" alert alert-danger"><span class="fa fa-warning"></span>  '.$error.'<a href="#" class="close" data-dismiss="alert">&times;</a></li></small>';
		}

		$result.='</ul>';
		return $result;
	}

	function sanitize($unclean)
	{
		return htmlentities($unclean, ENT_QUOTES, "UTF-8");
	}

	function login($user_id)
	{
		$_SESSION['user']=$user_id;
		global $dbc;	
		$date=date("Y-m-d H:i:s");
		$query="UPDATE users SET last_login='$date' WHERE id=$user_id";
		mysqli_query($dbc, $query);
		$_SESSION['success_m']="You have successfully logged in";
		header('Location: index_admin.php');
	}

	function is_logged_in()
	{
		if (isset($_SESSION['user']) && $_SESSION['user']>0) {
			return true;
		}
		return false;
	}

	function login_error_redirect($url='login.php')
	{
		$_SESSION['error_m']="You must be logged in to access that page.";
		header('Location:'.$url);
	}

	function permissions_error_redirect($url='index_admin.php')
	{
		$_SESSION['error_m']="You do not have permission to access that page.";
		header('Location:'.$url);
	}

	function has_permissions($permission='admin')
	{
		global $user_data;
		$permissions=explode(',', $user_data['permissions']);
		if(in_array($permission, $permissions, true))
		{
			return true;
		}
		return false;
	}

 ?>