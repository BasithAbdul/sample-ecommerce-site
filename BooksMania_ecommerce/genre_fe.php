<?php 
	require_once 'sys/connectDB.php';
	include 'includes/head.php'; 
	include 'includes/nav.php';

	if(isset($_GET['genre']))
	{
		$genre_id=(int)sanitize($_GET['genre']);
	}
	$header_query="SELECT genre FROM genre where id=$genre_id";
	$header=mysqli_fetch_assoc(mysqli_query($dbc, $header_query));
	$query="SELECT * FROM products";
	$genre_result=mysqli_query($dbc, $query);
 ?>




<!-- featured products -->
<div class="container-fluid">
	<h2 class="text-center"><?= $header['genre'];?></h2>
	<div class="row">
	
	<!-- left side bar -->
	<div class="col-md-2">
		<h5>Left Side bar</h5>
	</div>	
	<!-- end of left side bar -->
	
	<!-- products section-->
	<div class="col-md-10">
		<div class="row">
			<?php while ($genre_find=mysqli_fetch_assoc($genre_result)) { 
				$genre_array=explode(',', $genre_find['genre']);
				if(in_array($genre_id, $genre_array))
				{
				$authorid=(int)$genre_find['author'];
 				$authorquery="SELECT author FROM author WHERE id = $authorid";
 				$author=mysqli_fetch_assoc(mysqli_query($dbc, $authorquery));
				?>
			<div class="col-md-3">
				<h4 class="book-title"><?= $genre_find['title']; ?></h4>		
				<h5 class="book-author"><?= $author['author']; ?></h5>
				<img class="product-image" src="<?= $genre_find['image']; ?>">
				<p class="list-price text-danger">List Price: <s>&#8377;<?= $genre_find['list_price']; ?></s></p>
				<p class="our-price">Our Price: &#8377;<?= $genre_find['our_price']; ?></p>
				<button type="button" class="btn btn-success" onclick="detailsModal(<?= $genre_find['id']; ?>)">Details</button>
			</div>

			<?php } } ?>
			

		</div><!--end row container-->
	</div>
	<!-- end of products section -->
	</div>
</div>
<!--end of featured products container-->


									
<?php include 'includes/footer.php'; ?>