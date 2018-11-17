<?php 
	define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/BooksMania/');	
	define('CART_COOKIE', 'SSe12dFpQ1W3m20');
	define('CART_COOKIE_EXPIRE', time()+(86400*30));
	define('CURRENCY', 'INR');
	define('CHECKOUTMODE', 'test');

	if (CHECKOUTMODE=='test') {
		define('STRIPE_PRIVATE', 'sk_test_GmxT69Gk2kXy3ixwyvqPkDuE');
		define('STRIPE_PUBLIC', 'pk_test_gcHb1SJvyNwVez5Sfm8cIILS');
	}
	
	
 ?>
