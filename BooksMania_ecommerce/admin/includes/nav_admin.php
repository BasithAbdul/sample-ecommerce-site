<!-- Navigation bar -->
<?php 
	$query="SELECT * FROM genre WHERE parent=0";
	$result=mysqli_query($dbc, $query);
 ?>
	<nav class="navbar navbar-expand-sm bg-light navbar-light">
			<div class="navbar-header">
	  			<a class="navbar-brand" href="index_admin.php">BooksMania: Admin Console</a>
			</div>
	    	<ul class="navbar-nav">
	     	<li class="navbar-item"><a class="nav-link" href="authors.php">Authors</a></li>
	     	<li class="navbar-item"><a class="nav-link" href="genre.php">Genre</a></li>
	     	<li class="navbar-item"><a class="nav-link" href="products.php">Products</a></li>
	     	<?php if (has_permissions()): ?>
	     	<li class="navbar-item"><a class="nav-link" href="users.php">Users</a></li>
	     	<?php endif ?>
	     	<li class="nav-item dropdown ml-auto">
	     		<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><?= 'Hello '.$fn.'! ' ?></a>
	     		<ul class="dropdown-menu">
	     			<li class="dropdown-item"><a class="nav-link" href="change_password.php">Change Password</a></li>
	     			<li class="dropdown-item"><a class="nav-link" href="logout.php">Logout</a></li>
	     		</ul>
	     	</li>
	    	</ul>
	</nav>  
<!-- end of navigation bar -->
