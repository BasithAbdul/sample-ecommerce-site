<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	$product_id=sanitize($_POST['product_id']);
	$quantity=sanitize($_POST['quantity']);
	$available=sanitize($_POST['available']);
	$item=array();
	$item[]=array(
	'id' => $product_id,
	'quantity' => $quantity,
	);
	
	$domain=(($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
	$query=$dbc->query("SELECT * FROM products WHERE id = '{$product_id}'");
	$product=mysqli_fetch_assoc($query);
	$_SESSION['success_m']=$product['title']." has been added to your cart.";
	;


	if($cart_id != '')
	{
		$cart_query="SELECT * FROM cart WHERE id=$cart_id";
		$cart_result=mysqli_query($dbc, $cart_query);
		$cart=mysqli_fetch_assoc($cart_result);
		$prev_items=json_decode($cart['items'], true);
		$item_match=0;
		$new_items=array();
		foreach ($prev_items as $p_item) {
			if ($item[0]['id']==$p_item['id']) {
				$p_item['quantity']=$p_item['quantity']+$item[0]['quantity'];
				if ($p_item['quantity']>2) {
					$p_item['quantity']=2;
				}
				if ($p_item['quantity']>$available) {
					$p_item['quantity']=$available;
				}
				$item_match=1;
			}
			$new_items[]=$p_item;
		}
		if($item_match != 1)
		{
			$new_items=array_merge($item, $prev_items);
		}
		$items_json=json_encode($new_items);
		$cart_expire=date("Y-m-d H:i:s", strtotime("+30 days"));
		$dbc->query("UPDATE cart SET items='$items_json', expiry_date='$cart_expire' WHERE id=$cart_id ");
		setcookie(CART_COOKIE, '', 1, "/", $domain, false);
		setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, "/", $domain, false);
	}
	else
	{
		
		$items_json=json_encode($item);
		$cart_expire=date("Y-m-d H:i:s", strtotime("+30 days"));
		$dbc->query("INSERT INTO cart (items, expiry_date) VALUES ('{$items_json}', '{$cart_expire}')");
		$cart_id=$dbc->insert_id;
		setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
	}




 ?>