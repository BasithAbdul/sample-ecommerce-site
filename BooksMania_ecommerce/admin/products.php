<?php 
	
	require_once '../sys/connectDB.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include 'includes/head_admin.php';
	include 'includes/nav_admin.php';

	if(isset($_GET['delete']))
	{
		$delete_id=(int)sanitize($_GET['delete']);
		$deletequery="UPDATE products SET archived = 1 WHERE id=$delete_id";
		mysqli_query($dbc, $deletequery);
		header('Location: products.php');
	}

	if (isset($_GET['add']) || isset($_GET['edit'])) {
		$authorquery="SELECT * FROM author ORDER BY author";
		$author_result=mysqli_query($dbc, $authorquery);

		$title=((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):'');
		$author=((isset($_POST['author']) && $_POST['author']!='')?(int)sanitize($_POST['title']):'');
		$author=((isset($_POST['author']) && $_POST['author']!='')?(int)sanitize($_POST['title']):'');

		$sub_genre=((isset($_POST['sub_genre']) && $_POST['sub_genre']!='')?(int)sanitize($_POST['sub_genre']):'');
		$parent=((isset($_POST['parent_genre']) && $_POST['parent_genre']!='')?sanitize($_POST['parent_genre']):'');
		$lp=((isset($_POST['list_price']) && $_POST['list_price']!='')?sanitize($_POST['list_price']):'');
		$op=((isset($_POST['our_price']) && $_POST['our_price']!='')?sanitize($_POST['our_price']):'');
		$blurb=((isset($_POST['blurb']) && $_POST['blurb']!='')?sanitize($_POST['blurb']):'');

		// $parent_genre=((isset($_POST['parent_genre']) && $_POST['parent_genre']!='')?(int)sanitize($_POST['parent_genre']):'');
		if(isset($_GET['edit']))
		{
			$edit_id=(int)$_GET['edit'];
			$edit_query="SELECT * FROM products WHERE id=$edit_id";
			$edit_result=mysqli_query($dbc, $edit_query);
			$edit_book=mysqli_fetch_assoc($edit_result);
			$genre_id=$edit_book['genre'];

			$title=((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):$edit_book['title']);
			$author=((isset($_POST['author']) && $_POST['author']!='')?sanitize($_POST['author']):$edit_book['author']);
			
			$sub_genre=((isset($_POST['sub_genre']) && $_POST['sub_genre']!='')?sanitize($_POST['sub_genre']):$edit_book['genre']);
			$parent_query="SELECT * FROM genre WHERE id=$sub_genre";
			$parent_id=mysqli_fetch_assoc(mysqli_query($dbc, $parent_query));
			$parent=((isset($_POST['parent_genre']) && $_POST['parent_genre']!='')?sanitize($_POST['parent_genre']):$parent_id['parent']);
			$lp=((isset($_POST['list_price']) && $_POST['list_price']!='')?sanitize($_POST['list_price']):$edit_book['list_price']);
			$op=((isset($_POST['our_price']) && $_POST['our_price']!='')?sanitize($_POST['our_price']):$edit_book['our_price']);
			$blurb=((isset($_POST['blurb']) && $_POST['blurb']!='')?sanitize($_POST['blurb']):$edit_book['blurb']);
			// $parent_genre=((isset($_POST['parent_genre']) && $_POST['parent_genre']!='')?sanitize($_POST['parent_genre']):$edit_book['parent_genre']);
			
			$blurb=$edit_book['blurb'];

			?>	
			<script type="text/javascript">
				// $('document').ready(function(){
				// 	$('#selected_author').val(<?=$author?>).prop('selected', true);
				// 	$('#selected_parent').val(<?=$parent?>).prop('selected', true);
				// });
			</script>


			<?php
		}
		if(isset($_POST['author']) || isset($_GET['edit']))
		{ ?>
			<script type="text/javascript">
				$('document').ready(function(){
					$('#selected_author').val(<?=$author?>).prop('selected', true);
					$('#selected_parent').val(<?=$parent?>).prop('selected', true);
				});
			</script>
 
		<?php }

		$genre_parent_query="SELECT * FROM genre WHERE parent=0";
		$genre_parent_result=mysqli_query($dbc, $genre_parent_query);

		if(isset($_POST) && !empty($_POST))
		{
				$errors=array();
				$fields=['title', 'author', 'genre_parent', 'sub_genre', 'list_price', 'our_price', 'blurb'];

				foreach ($fields as $field) {
				echo "check error";

				if($_POST[$field]=='')
				{
					echo "empty-positive";
					$errors[]="You must not leave a field empty";
					break;
				}
			}			
				echo "post";
				
				echo $title;
				
				$blurb=sanitize($_POST['blurb']);

			

			

			if(!empty($_FILES))
			{
				
				var_dump($_FILES);	
				$image=$_FILES['image'];
				$name=$image['name'];
				$image_array=explode('.', $name);
				$image_name=$image_array[0];
				$image_ext=$image_array[1];

				$mime_array=explode('/', $image['type']);
				$mime_name=$mime_array[0];
				$mime_type=$mime_array[1];
				$tmp_loc=$image['tmp_name'];
				$size=$image['size'];
				$upload_name=md5(microtime()).'.'.$image_ext;
				$upload_path=$_SERVER['DOCUMENT_ROOT'].'/BooksMania/images/'.$upload_name;
				$db_path='/BooksMania/images/'.$upload_name;
				

				$allowed_types=['png', 'jpg', 'jpeg'];
				if($mime_name != 'image')
				{
					$errors[]="You must upload an image.";
				}
				if(!in_array($image_ext, $allowed_types))
				{
					$errors[]="The image extension must be a png, jpg or jpeg.";
				}
				if($size>1000000)
				{
					echo "check size";
					$errors[]="File size exceeds limit. File size must be less than 1MB."; 
				}
			}

			if(!empty($errors))
			{
				echo "check errors";
				$message= disp_err($errors);
				echo $message;
			}

			else{
				move_uploaded_file($tmp_loc, $upload_path);
				echo "check1";
				$bookquery="INSERT INTO products (`title`, `author`, `genre`, `image`, `list_price`, `our_price`, `blurb`) VALUES ('$title', '$author', '$genre', '$db_path', '$lp', '$op', '$blurb')";
				echo "check 2";
				mysqli_query($dbc, $bookquery);
				echo "check3";
			}
		}

	 ?>
	<div class="container">
		<h2 class="text-center"><?= (isset($_GET['edit']))?'Edit ':'Add a New ' ?>Book</h2>
		<hr>
		<form action="products.php?<?= (isset($_GET['edit']))?'edit=$edit_id':'add=1' ?>" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="form-group col-md-3">
					<label for="title">Title: </label>
					<input type="text" class="form-control" name="title" value="<?= $title?>">
				</div>
				<div class="form-group col-md-3">
					<label for="genre">Author: </label>
					<select class="form-control" name="author" id="selected_author">
						<option></option>
						<?php 
						while($author=mysqli_fetch_assoc($author_result)){

						 ?>
						<option value="<?=$author['id']?>"><?= $author['author'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="selected_parent">Parent: </label>
					<select class="form-control" name="genre_parent" id="selected_parent">
						<option></option>
						<?php 
							while ($genre_parent=mysqli_fetch_assoc($genre_parent_result)) { ?>
							<option value="<?= $genre_parent['id'] ?>"><?= $genre_parent['genre'] ?></option>
							<?php }
						 ?>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="sub_genre">Sub-Genre: </label>
					<select class="form-control" name="sub_genre" id="sub_genre">	
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="list_price">List Price: </label>
					<input type="text" name="list_price" class="form-control" value="<?=$lp?>">
				</div>
				<div class="form-group col-md-3">
					<label for="our_price">Our Price: </label>
					<input type="text" name="our_price" class="form-control" value="<?=$op?>">
				</div>
				<div class="form-group col-md-6">	
					<label for="image" >Choose Book Cover: </label>
					<input type="file" name="image" class="form-control">
					
				</div>
				<div class="form-group col-md-6">
					<label for="blurb">Blurb: </label>
					<textarea class="form-control" name="blurb" rows="4" value="<?=$blurb?>"></textarea>
				</div>

			</div>
			<div class="row">
				<div class="col-md-1">
					<a href="products.php" class="btn btn-secondary">Cancel</a>
				</div>
			<div class="col-md-2">
				<input type="submit" name="add" value="<?= (isset($_GET['edit']))?'Edit ':'Add ' ?> Book" class="form-control btn btn-success">
			</div>
		</div> 
		</form>
	</div>
		
	<?php }
	else{
	$productsquery="SELECT * FROM products WHERE archived = 0";
	if (isset($_GET['archived']) && !empty($_GET['archived'])) {
		$productsquery="SELECT * FROM products WHERE archived = 1";
	}
	$results=mysqli_query($dbc, $productsquery);


	if (isset($_GET['featured'])) {
		$fid=(int)$_GET['id'];
		$featured=(int)$_GET['featured'];
		$featuredquery="UPDATE products SET featured = $featured WHERE id = $fid";
		mysqli_query($dbc, $featuredquery);
		if (isset($_GET['archived'])) {
			header('Location: products.php?archived=1');	
		}
		else
			header('Location: products.php');
	}
	if(isset($_GET['restore']))
	{
		echo "restore";
		$restore_id=$_GET['restore'];
		$restore_query="UPDATE products SET archived = 0 WHERE id=$restore_id";
		mysqli_query($dbc, $restore_query);
		header('Location: products.php?archived=1');
	}

	
 ?>
 <div class="container">
 	<h2 class="text-center">Books</h2>
 	<div class="d-inline pull-right" id="grp-btn">
 	<a href="products.php<?= (isset($_GET['archived'])?'':'?archived=1') ?>" class="btn btn-secondary"><?= (isset($_GET['archived']))?'Hide ':'Show ' ?> Archived</a>	
 	<?php if (!isset($_GET['archived'])): ?>
 	<a href="products.php?add=1" class="btn btn-success">Add Books</a>	
 	<?php endif ?>
 	
 	</div>
 	<div class="clearfix"></div>
 	<hr>
 	<table class="table table-striped table-bordered table-condensed">
 		<thead>
 			<th>Title</th>
 			<th>Author</th>
 			<th>Genre</th>
 			<th>List Price &#40;&#8377;&#41;</th>
 			<th>Our Price &#40;&#8377;&#41;</th>
 			<th>Featured</th>
 			<th>Sold</th>
 			<th></th>
 		</thead>
 		<tbody>
 			<?php while ($products=mysqli_fetch_assoc($results)) {
 				$authorid=(int)$products['author'];
 				$authorquery="SELECT author FROM author WHERE id = $authorid";
 				$author=mysqli_fetch_assoc(mysqli_query($dbc, $authorquery));

 				$genre_array=explode(',', $products['genre']);
 			 ?>
			<tr>
 				<td><?= $products['title']; ?></td>
 				<td><?= $author['author']; ?></td>
 				<td>
 					<?php 
 						foreach ($genre_array as $gnr) {
 							$genrequery="SELECT * FROM genre WHERE id=$gnr";
 							$genreresult=mysqli_fetch_assoc(mysqli_query($dbc, $genrequery));
 							$genre=$genreresult['genre'];
 							$parentid=$genreresult['parent'];
 							if($parentid!=0){
 							$parentresult="SELECT genre FROM genre WHERE id=$parentid";
 							$parent=mysqli_fetch_assoc(mysqli_query($dbc, $parentresult));
 							echo $genre.'-'.$parent['genre'].'<br>';
 							}
 							
 						}
 					 ?>
 				</td>
 				<td><?= $products['list_price']; ?></td>
 				<td><?= $products['our_price']; ?></td>
 				<?php if (!isset($_GET['archived'])): ?>
 				<td><a href="products.php?featured=<?= ($products['featured']==0?'1':'0')?>&id=<?=$products['id']?>"><span class="text-secondary fa fa-<?= ($products['featured']==0)?'ban':'check text-success'?>"></span></a><span><?=($products['featured']==0)?'':' featured'?></span></td>	
 				<?php endif ?>
 				<?php if (isset($_GET['archived'])): ?>
 				<td><a href="products.php?archived=1&featured=<?= ($products['featured']==0?'1':'0')?>&id=<?=$products['id']?>"><span class="text-secondary fa fa-<?= ($products['featured']==0)?'ban':'check text-success'?>"></span></a><span><?=($products['featured']==0)?'':' featured'?></span></td>	
 				<?php endif ?>
 				<td><?= $products['archived']; ?></td>
 				<?php if (!isset($_GET['archived'])): ?>
 				<td><a href="products.php?edit=<?= $products['id']; ?>"><span class="fa fa-edit text-secondary"></span></a>
 				<a href="products.php?delete=<?= $products['id']; ?>"><span class="fa fa-trash text-danger"></span></a></td>	
 				<?php endif ?>
 				<?php if (isset($_GET['archived'])): ?>
 					<td><a href="products.php?archived=1&restore=<?= $products['id']; ?>"><span class="fa fa-undo text-primary"></span></a></td>	
 				<?php endif ?>
 			</tr>

 			<?php } ?>
 			
 		</tbody>
 	</table>
 </div>


<?php } ?>


 <?php 
 	include 'includes/footer_admin.php';
  ?>

  <script type="text/javascript">
  	$('document').ready(function(){
  		<?php echo $sub_genre; ?>
  		get_sub('<?= $sub_genre?>');
  	});
  </script>