<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	include 'includes/head.php';
	include 'includes/nav.php';

	if ($cart_id != '') {
		$cart_query="SELECT * FROM cart WHERE id=$cart_id";
		$cart=mysqli_fetch_assoc(mysqli_query($dbc, $cart_query));
		$items=json_decode($cart['items'], true);
		$i=1;
		$totalq=0;
		$total=0;
	}


	

 ?>

 <div class="container">
 	<h2 class="text-center">My Shopping Cart</h2>
 	<hr>
 	<?php if ($cart_id=='') { ?>
 		<div class="alert alert-danger text-center"><span class="fa fa-warning"></span>&nbsp; Your shopping cart is empty</div>
 	<?php } ?>

 	<div class="col-md-12">
 		<table class="table table-striped table-bordered table-condensed">
 			<thead>
 				<th>No.</th>
 				<th>Title</th>
 				<th>Price</th>
 				<th>Quantity</th>
 				<th>Sub Total</th>
 			</thead>
 			<tbody>
				<?php 
				foreach ($items as $item) {
					$product_id=$item['id'];
					$product_query="SELECT * FROM products WHERE id=$product_id";
					$product=mysqli_fetch_assoc(mysqli_query($dbc, $product_query));
					$title=$product['title'];
					$price=$product['our_price'];
					$quantity=$item['quantity'];
					?>
					<tr>
						<td><?= $i++;?></td>
						<td><?= $title ?></td>
						<td><?= $price ?></td>
						<td>
							<button class="btn btn-xs fa fa-minus" onclick="update_cart('removeone', <?= $product['id'] ?>)"></button>
							
							<?= $quantity ?>
							<?php if ($quantity<3): ?>
							<button class="btn btn-xs fa fa-plus" onclick="update_cart('addone', <?= $product['id'] ?>)"></button>	
							<?php endif ?>
							
								
						</td>
						<td><?php $subtotal=$price*$quantity; echo $subtotal; ?></td>
					</tr>
				<?php
				$totalq+=$quantity;
				$total+=$subtotal;
				 } ?> 				
 			</tbody>
 		</table>
 		<table class="table table-condensed table-bordered">
 			<legend>Total</legend>
 			<thead>
 				<th>Total Items</th>
 				<th>Grand Total</th>
 			</thead>
 			<tbody class="text-right">
 				<td><?= $totalq ?></td>
 				<td><span class="fa fa-rupee">&nbsp;</span><?= $total ?></td>
 			</tbody>
 		</table>
 		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkOutModal"><span class="fa fa-shopping-cart">&nbsp;</span>Check Out</button>



 		<!-- The Modal -->
<div class="modal" id="checkOutModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="modal-title">Shipping Address</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="thankyou.php" method="post" id="payment-form">
        	<div id="pay_err"></div>
        	<input type="hidden" name="total" value="<?= $total ?>">
        	<input type="hidden" name="cart_id" value="<?= $cart_id ?>">
    
        	<div id="s1" style="display: block;"> 
	        		<div class="row">
	        		<div class="form-group col-md-6">
	        		<label for="full_name">Full Name: </label>
	        		<input type="text" name="full_name" id="full_name" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        		<label for="email">Email: </label>
	        		<input type="text" name="email" id="email" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        		<label for="phone">Phone: </label>
	        		<input type="text" name="phone" id="phone" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        		<label for="addr">Street Address: </label>
	        		<input type="text" name="addr" id="addr" class="form-control" data-stripe="address_line1">
	        		</div>
	        		<div class="form-group col-md-6">
	        		<label for="pin">Pincode: </label>
	        		<input type="text" name="pin" id="pin" class="form-control" data-stripe="address_zip">
	        		</div>
	        		<div class="form-group col-md-6">
	        		<label for="city">City/State: </label>
	        		<input type="text" name="city" id="city" class="form-control" data-stripe="address_city">
	        		</div>
        		</div>
        	</div>
        	<div id="s2" style="display: none;">
        		<div class="row">
	        		<div class="form-group col-md-4">
	        		<label for="c_name">Name on Card: </label>
	        		<input type="text" name="c_name" id="c_name" class="form-control" data-stripe="name">
	        		</div>

	        		<div class="form-group col-md-4">
	        		<label for="c_num">Card Number: </label>
	        		<input type="text" name="c_num" id="c_num" class="form-control" data-stripe="number">
	        		</div>

	        		<div class="form-group col-md-2">
	        		<label for="cvc">CVC: </label>
	        		<input type="text" name="cvc" id="cvc" class="form-control" data-stripe="cvc">
	        		</div>

	        		<div class="form-group col-md-2">
	        		<label for="exp_m">Expire Month: </label>
	        		<select name="exp_m" id="exp_m" class="form-control" data-stripe="exp_month">
	        			<option value=""></option>
	        			<?php for($i=1; $i<13; $i++){ ?>
	        			<option class="form-control" value="<?= $i; ?>"><?= $i; ?></option>
	        			<?php } ?>
	        		</select>
	        		</div>

	        		<div class="form-group col-md-2">
	        		<label for="exp_y">Expire Year: </label>
	        		<select name="exp_y" id="exp_y" class="form-control" data-stripe="exp_year">
	        			<option value=""></option>
	        			<?php 
	        			$y=date('Y');
	        			for($i=0; $i<25; $i++){ ?>
	        			<option class="form-control" value="<?= $y+$i; ?>"><?= $y+$i; ?></option>
	        			<?php } ?>
	        		</select>
	        		</div>
        		</div>	
        	</div>
        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning" onclick="verify_address()" id="next_btn">Next</button>
        <button type="button" class="btn btn-warning" onclick="back_address()" id="back_btn" style="display: none;">Back</button>
        <button type="submit" class="btn btn-warning" id="place_order" style="display: none;">Place Order</button>
        </form>
      </div>

    </div>
  </div>
</div>
 	</div>
 </div>

<script type="text/javascript">

	function back_address()
	{
		$('#pay_err').html('');
		$('#s1').css('display', 'block');
		$('#s2').css('display', 'none');
		$('#next_btn').css('display', 'inline-block');
		$('#back_btn').css('display', 'none');
		$('#place_order').css('display', 'none');
		$('#modal-title').html('Shipping Details');

	}


	function verify_address()
	{
		var data={'full_name': $('#full_name').val(),
				  'email': $('#email').val(),
				  'phone': $('#phone').val(),
				  'addr': $('#addr').val(),
				  'pin': $('#pin').val(),		
				  'city': $('#city').val(),
				};

		$.ajax({
			url: '/BooksMania/admin/parsers/verify_address.php',
			method: "post",
			data: data,
			success: function(data){
				if (data != 'successful') {
					 $('#pay_err').html(data);
				}
				else if (data === 'successful') {
					$('#pay_err').html('');
					$('#s1').css('display', 'none');
					$('#s2').css('display', 'block');
					$('#next_btn').css('display', 'none');
					$('#back_btn').css('display', 'inline-block');
					$('#place_order').css('display', 'inline-block');
					$('#modal-title').html('Card Details');
				}
			},
			error: function(){
				alert("Something went wrong!");
			},
		});
	}


	Stripe.setPublishableKey('<?= STRIPE_PUBLIC; ?>');

	function stripeResponseHandler(status, response)
	{
		var $form=$('#payment-form');

		if(response.error){
			$form.find('#pay-err').text(response.error.message);
			$form.find('button').prop('disabled', false);
		}
		else
		{
			var token=response.id;
			$form.append($('<input type="hidden" name="stripeToken" >').val(token));

			$form.get(0).submit();
		}
	};

	$(function($){
		$('#payment-form').submit(function(event){
			$form.find('button').prop('disabled', true);

			Stripe.card.createToken($form, stripeResponseHandler);

			return false;			 
		});
	});




</script>


 <?php include 'includes/footer.php'; ?>