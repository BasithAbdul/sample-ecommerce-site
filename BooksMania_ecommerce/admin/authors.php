<?php 
	require_once '../sys/connectDB.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include 'includes/head_admin.php';
	include 'includes/nav_admin.php';

	$query="SELECT * FROM author ORDER BY author";
	$result=mysqli_query($dbc, $query);

	$errors=array();

	if(isset($_GET['edit']) && !empty($_GET['edit']))
	{
		$edit_id=(int)$_GET['edit'];
		$edit_id=sanitize($edit_id);
		$query5="SELECT * FROM author WHERE id=$edit_id";
		$editquery=mysqli_query($dbc, $query5);
		$editbrand=mysqli_fetch_assoc($editquery);
		
	}


	if(isset($_GET['delete']) && !empty($_GET['delete']))
	{
		$delete_id=(int)sanitize($_GET['delete']);
		$query4="DELETE FROM author WHERE id=$delete_id";
		mysqli_query($dbc, $query4);
		header('Location: authors.php');
	}


	if(isset($_POST['submit']))
	{
		$author=sanitize($_POST['author']);
		if($_POST['author']=='')
		{
			$errors[].="You must enter an author's name.";
		}
		$query2="SELECT * FROM author where author='$author'";
		if(isset($_GET['edit']))
		{
			$query2="SELECT * FROM author where author = '$author' AND id!=$edit_id";
		}
		$check=mysqli_query($dbc, $query2);
		$num=mysqli_num_rows($check);
		if($num>0)
		{
			$errors[].=$_POST['author']." already exists.";
		}

		if(!empty($errors))
		{
			echo disp_err($errors);
		}
		else{
			$query3="INSERT INTO author (author) VALUES ('$author')";
			if(isset($_GET['edit']))
			{
				$query3="UPDATE author SET author = '$author' WHERE id=$edit_id";
			}
			mysqli_query($dbc, $query3);
			header('Location: authors.php');
		}
	}
 ?>

<div class="container">
<h2 class="text-center">Authors</h2>
<div class="text-center">
	<form class="form-inline" action="authors.php<?= (isset($_GET['edit'])?'?edit='.$edit_id:'');?>" method="post">
		<div class="form-group">  
			<?php 
				if (isset($_GET['edit'])) {
					$brand=$editbrand['author'];
				}
				elseif (isset($_POST['author'])) {
					$brand=$_POST['author'];
				}
				else{
					$brand='';
				}
			 ?>
			<label for="author"><?= (isset($_GET['edit'])?'Edit':'Add an'); ?> Author: </label>
			<input type="text" name="author" class="form-control" value="<?= $brand ?>">
			<?php if (isset($_GET['edit'])): ?>
				<a href="authors.php" class="btn btn-secondary">Cancel</a>
			<?php endif ?>
			<input type="submit" value="<?= (isset($_GET['edit'])?'Edit':'Add'); ?> author" name="submit" class="btn btn-success">

		</div>	
	</form>
</div>
<hr>
<table class="table table-bordered table-striped" id="table-authors">
	<thead>
		<th></th><th>Authors</th><th></th>
	</thead>
	<tbody>
		<?php while($author=mysqli_fetch_assoc($result)){ ?>
		<tr>
			<td><a href="authors.php?edit=<?= $author['id'] ?>"><span class="fa fa-edit text-secondary"></span></a></td>
			<td><?= $author['author'] ?></td>
			<td><a href="authors.php?delete=<?= $author['id'] ?>"><span class="fa fa-remove text-danger"></span></a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>


 <?php include 'includes/footer_admin.php'; ?>
