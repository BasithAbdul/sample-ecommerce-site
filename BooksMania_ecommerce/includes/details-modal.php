
<?php 
	require '../sys/connectDB.php';
	$id=$_POST['id'];
	$id=(int)$id;
	$query="SELECT * FROM products WHERE id=$id";
	$result=mysqli_query($dbc, $query);
	$product=mysqli_fetch_assoc($result);

	$authorid=(int)$product['author'];
 	$authorquery="SELECT author FROM author WHERE id = $authorid";
 	$author=mysqli_fetch_assoc(mysqli_query($dbc, $authorquery));

	$genre_string=$product['genre'];
	$genre_array=explode(',', $genre_string);
	$size=sizeof($genre_array)-1;
	$i=0;
 ?>



<?php ob_start(); ?>

<div class="modal fade" id="details-modal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-center d-block"><?= $product['title']?> - <small>by <?= $author['author']?></small></h4>
				<button class="close" type="button" onclick="modalClose()" ><span>&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<img class="modal-image" src="<?= $product['image']?>">
						</div>
						<div class="col-sm-6">
							<h4>Details</h4>
							<p style="max-height: 200px; overflow-y: scroll;">
								<?= $product['blurb']?>
							</p>
							<p><small>
								Genre: <?php 
									foreach ($genre_array as $genre_id) {
										$query2="SELECT genre FROM genre WHERE id='$genre_id'";
										$genre=mysqli_fetch_assoc(mysqli_query($dbc, $query2));									
										echo $genre['genre'];
										if($i!=$size)
										{
											echo ", ";
											$i++;
										}

									}
								?>
							</p></small>
							<hr>
							<p>List Price: <s class="text-danger">&#8377;<?= $product['list_price']?></s></p>
							<p>Our Price: &#8377;<?= $product['our_price']?></p>
							<form action="addCart.php" method="post" id="product_form">
								<div class="form-group col-md-3">
									<input type="hidden" name="product_id" value="<?= $product['id'];?>">
									<input type="hidden" name="available" value="4" id="available">
									<label for="quantity">Quantity: </label><input id="quantity" type="number" min="1" max="3" class="form-control" name="quantity">
								</div>
							</form>
							<div id="modal_errors"></div>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
  
				<button class="btn btn-default"  onclick="modalClose()">Close</button>
				<button class="btn btn-warning" onclick="add_to_cart(); return false; "><span class="fa fa-shopping-cart"></span> Add to Cart</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function modalClose()
	{
		$("#details-modal").modal('hide');
		setTimeout(function(){
			$("#details-modal").remove();	
			$(".modal-backdrop").remove();
		}, 500);
		
	}
	$('.modal').on('hidden.bs.modal', function () {
  		$("#details-modal").modal('hide');
		setTimeout(function(){
			$("#details-modal").remove();	
			$(".modal-backdrop").remove();
		}, 500);
	});
</script>
<?php echo ob_get_clean(); ?>


   