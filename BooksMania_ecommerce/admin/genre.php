<?php 
	require $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include 'includes/head_admin.php';
	include 'includes/nav_admin.php';

	$query="SELECT * FROM genre WHERE parent=0";
	$result=mysqli_query($dbc, $query);

	if(isset($_GET['delete']) && !empty($_GET['delete']))
	{
		$delete_id=(int)$_GET['delete'];
		$delete_id=sanitize($delete_id);

		$deletequery="DELETE FROM genre WHERE id=$delete_id";
		mysqli_query($dbc, $deletequery);
		$deletequery="SELECT parent FROM genre WHERE id=$delete_id";
		$ifparent=mysqli_fetch_assoc(mysqli_query($dbc, $deletequery));
		if($ifparent==0)
		{
			$deletequery="DELETE FROM genre WHERE parent=$delete_id";
			mysqli_query($dbc, $deletequery);
		}
		header('Location: genre.php');
	}

	if(isset($_GET['edit']) && !empty($_GET['edit']))
	{
		$edit_id=(int)$_GET['edit'];
		$edit_id=sanitize($edit_id);
		$editquery="SELECT * FROM genre WHERE id=$edit_id";
		$editresult=mysqli_query($dbc, $editquery);
		$editgenre=mysqli_fetch_assoc($editresult);

		?>
		
		<?php
	}

	$errors=array();
	if(isset($_POST) && !empty($_POST))
	{	
		$parent=sanitize($_POST['parent']);
		$gnr=sanitize($_POST['genre']);
		if($gnr=='')
		{	
			$errors[].="You must enter a genre";
		}
		else{
			$numquery="SELECT * FROM genre WHERE genre ='$gnr' AND parent=$parent";
			if (isset($_GET['edit'])) {
				$numquery="SELECT * FROM genre WHERE genre ='$gnr' AND parent=$parent AND id!=$edit_id";
			}
			$numresult=mysqli_query($dbc, $numquery);
			$num=mysqli_num_rows($numresult);
			if($num>0)
			{
				$errors[].=$gnr." already exists";
			}
		}
		if(!empty($errors))
		{	
			$message=disp_err($errors);?>
			<script type="text/javascript">
				$('document').ready(function(){
				$('#errorMessage').html('<?= $message;	 ?>');
			});
			</script>
			<?php
		}
		else{
			$addquery="INSERT INTO genre (genre, parent) VALUES ('$gnr', $parent)";
			if (isset($_GET['edit'])) {
				$addquery="UPDATE genre SET genre ='$gnr' WHERE id=$edit_id";
			}
			mysqli_query($dbc, $addquery);
			header('Location: genre.php');
		}
	}

	if (isset($_GET['edit'])) {
		$inputvalue=$editgenre['genre'];
		?>
		<script type="text/javascript">
			$('document').ready(function(){
				$('#select').val(<?= $editgenre['parent']; ?>).prop('selected', true);
			});
		</script>
		<?php
	}
	else if (isset($_POST['genre'])) {
		$inputvalue=$_POST['genre'];
		?>
		<script type="text/javascript">
			$('document').ready(function(){
				$('#select').val(<?= $parent; ?>).prop('selected', true);
			});
		</script>
		<?php
	}
	else{
		$inputvalue='';
	}


	




 ?>
<div class="container">
 <h2 class="text-center">Genre</h2>
 <hr>

<div class="row">


	<div class="col-md-6">
		<div id="errorMessage"></div>
		<form class="form" action="genre.php<?= (isset($_GET['edit']))?'?edit='.$edit_id:''; ?>" method="post">
			<?php 
				$queryform="SELECT * FROM genre WHERE parent=0";
				$resultform=mysqli_query($dbc, $queryform);
			 ?>

			 <div class="form-group">
			 	<label for="parent">Genre: </label>
			 	<select id="select" class="form-control" name="parent">
			 		<option class="form-option" value="0">Parent</option>
			 		<?php while ($optionform=mysqli_fetch_assoc($resultform)) { ?>
			 			<option value="<?= $optionform['id'] ?>"><?= $optionform['genre']; ?></option>
			 		<?php } ?>
			 	</select>
			 </div>

			 <div class="form-group">
			 	<label for="genre"><?= (isset($_GET['edit']))?'Edit ':'Add a '; ?> Genre: </label>
			 	<input type="text" name="genre" class="form-control" value="<?= $inputvalue ?>">
			 </div>
			<?php if (isset($_GET['edit'])): ?>
				<a href="genre.php" class="btn btn-secondary">Cancel</a>
			<?php endif ?>
			 <input type="submit" name="add" value="<?= (isset($_GET['edit']))?'Edit ':'Add '; ?> Genre" class="btn btn-success">
		</form>
	</div>



	<div class="col-md-6">
	 <table class="table table-bordered">
	 	<thead>
	 		<th>Genre</th>
	 		<th>Sub-Genre</th>
	 		<th></th>
	 	</thead>
	 	<tbody>
	 		<?php
	 		    while ($genre=mysqli_fetch_assoc($result)) { ?>
	 			<tr class="bg-info">
		 			<td><?= $genre['genre']?></td>
		 			<td></td>
		 			<td>
		 				<a href="genre.php?edit=<?=$genre['id']?>"><span class="fa fa-edit text-secondary"></span></a>
		 				<a href="genre.php?delete=<?=$genre['id']?>"><span class="fa fa-remove text-danger"></span></a>
		 			</td>
	 			</tr>

	 			<?php
	 			$parent_id=(int)$genre['id'];
	 			$query2="SELECT * FROM genre WHERE parent='$parent_id'";
	 			$result2=mysqli_query($dbc, $query2);
	 			while ($sub_genre=mysqli_fetch_assoc($result2)) {
	 			?>
	 			<tr>
		 			<td></td>
		 			<td><?= $sub_genre['genre']?></td>
		 			<td>
		 				<a href="genre.php?edit=<?=$sub_genre['id']?>"><span class="fa fa-edit text-secondary"></span></a>
		 				<a href="genre.php?delete=<?=$sub_genre['id']?>"><span class="fa fa-remove text-danger"></span></a>
		 			</td>
	 			</tr>	

	 		<?php } } ?>

	 		
	 	</tbody>
	 </table>
 	</div>
</div>


 <?php 
 	include 'includes/footer_admin.php';
  ?>