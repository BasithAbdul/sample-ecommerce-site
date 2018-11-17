<?php 
	require $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	$parent_id=(int)$_POST['parent_id'];
	$selected=sanitize($_POST['selected']);
	$sub_genre_query="SELECT * FROM genre WHERE parent = $parent_id ORDER BY genre";
	$sub_genre_result=mysqli_query($dbc, $sub_genre_query);

	ob_start(); ?>
		<option></option>
	<?php 
	while ($sub_genre=mysqli_fetch_assoc($sub_genre_result)) {
		?>	
		<option value="<?= $sub_genre['id'] ?>" <?= (($selected==$sub_genre['id'])?'selected':'');?> ><?= $sub_genre['genre'] ?></option>
		<?php
	}

echo ob_get_clean();
 ?>