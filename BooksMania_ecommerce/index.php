

<?php 
	require_once 'sys/connectDB.php';
	include 'includes/head.php'; 
	include 'includes/nav.php';
	include 'includes/banner.php';
	$query="SELECT * FROM products WHERE featured =1";
	$featured=mysqli_query($dbc, $query);
 ?>




<!-- featured products -->
<div class="container-fluid">
	<h2 class="text-center heading">Featured Products</h2>
	<div class="row">
	
	<!-- left side bar -->
	<div class="col-md-2">
		<h5>Left Side bar</h5>
		
	</div>	
	<!-- end of left side bar -->
	
	<!-- products section-->
	<div class="col-md-10">
		<div class="row book-row">
			<span id="land"></span>
			<?php while ($row=mysqli_fetch_assoc($featured)) { 
				$authorid=(int)$row['author'];
 				$authorquery="SELECT author FROM author WHERE id = $authorid";
 				$author=mysqli_fetch_assoc(mysqli_query($dbc, $authorquery));
				?>
			<div class="col-md-3 book-box">
				<h4 class="book-title"><?= $row['title']; ?></h4>		
				<h5 class="book-author"><?= $author['author']; ?></h5>
				<br>
				<img class="product-image" src="<?= $row['image']; ?>">
				<br><br>
				<p class="list-price text-danger">List Price: <s>&#8377;<?= $row['list_price']; ?></s></p>
				<p class="our-price">Our Price: &#8377;<?= $row['our_price']; ?></p>
				<button type="button" class="btn btn-success" onclick="detailsModal(<?= $row['id']; ?>)">Details</button>
				<br>
				<hr>
			</div>

			<?php } ?>
			

		</div><!--end row container-->
	</div>
	<!-- end of products section -->
	</div>
</div>
<!--end of featured products container-->



<?php include 'includes/footer.php'; ?>