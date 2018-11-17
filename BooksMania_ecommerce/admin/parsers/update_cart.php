<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/BooksMania/sys/connectDB.php';
	$mode=sanitize($_POST['mode']);
	$edit_id=sanitize($_POST['edit_id']);

	$cart_query="SELECT * FROM cart WHERE id=$cart_id";
	$cart=mysqli_fetch_assoc(mysqli_query($dbc, $cart_query));
	$items=json_decode($cart['items'], true);
	$updated_items=array();

	$domain=($_SERVER['HTTP_HOST']!='localhost')?'.'.$_SERVER['HTTP_HOST']:false;
	if($mode=='removeone')
	{
		foreach($items as $item)
		{
			if($item['id']==$edit_id)
			{
				$item['quantity']=$item['quantity']-1;
			}
			if($item['quantity']>0)
			{
				$updated_items[]=$item;
			}
		}
	}

	if($mode=='addone')
	{
		foreach($items as $item)
		{
			if($item['id']===$edit_id)
			{
				$item['quantity']=$item['quantity']+1;
			}
			$updated_items[]=$item;
		}
	}

	if(!empty($updated_items))
	{
		$json_updated=json_encode($updated_items);
		$update_query="UPDATE cart SET items = '{$json_updated}' WHERE id=$cart_id";
		mysqli_query($dbc, $update_query);
	}
 ?> 