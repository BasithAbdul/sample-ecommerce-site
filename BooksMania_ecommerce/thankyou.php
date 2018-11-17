<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';




require 'vendor/autoload.php';
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];

$full_name=sanitize($_POST['full_name']);
$email=sanitize($_POST['email']);
$addr=sanitize($_POST['addr']);
$pin=sanitize($_POST['pin']);
$city=sanitize($_POST['city']);


$cart_id=sanitize($_POST['cart_id']);
$total=sanitize($_POST['total']);
$charge_amount=number_format($total, 2)*100;

$metadata=array(
	'$card_id' => $cart_id,
	'$total'  => $total,
);



try {

	$charge = \Stripe\Charge::create([
    'amount' => $charge_amount,
    'currency' => CURRENCY,
    'description' => '',
    'source' => $token,
    'metadata' => $metadata,	
]);	

$query="UPDATE cart SET paid=1 WHERE id=$cart_id";
mysqli_query($dbc, $query);
$txn_query="INSERT INTO transactions (charge_id, cart_id, full_name, email, address, city, pincode, total, txn_type) VALUES 
	('$charge->id', '$cart_id', '$full_name', '$email', '$addr', '$city', '$pin', '$total', '$charge->object')";
mysqli_query($dbc, $txn_query);	
$domain=($_SERVER['HTTP_HOST']!='localhost')?'.'.$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE, '', 1, "/", $domain, false);
include 'includes/head.php';
include 'includes/nav.php';

?>

<h1 class="text-center">Thank You!</h1>
<hr>
<p>Your cart has been successfully charged <?= $total ?>. You have been emailed a reciept.</p>
<p>Your reciept number is: <strong><?= $cart_id ?></strong></p>
<p>Your order will be shipped to the address below: </p>
<address>
	<?= $full_name; ?>
	<?= $addr; ?>
	<?= $city; ?>


</address>

<?php
include 'includes/footer.php';

} 

catch (\Stripe\Error\Card $e) {
	echo $e;
}

 ?>