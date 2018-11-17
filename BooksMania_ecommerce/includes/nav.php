<!-- Navigation bar -->
<?php 
	$query="SELECT * FROM genre WHERE parent=0";
	$result=mysqli_query($dbc, $query);
 ?>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <a class="navbar-brand" href="index.php">BooksMania</a>

	  <div class="collapse navbar-collapse" id="navbarNavDropdown">
	    <ul class="navbar-nav">
	    	<?php while($row=mysqli_fetch_assoc($result)){ 
	    		$id=$row['id'];
	    		$query2="SELECT * FROM genre WHERE parent='$id'";
	        	$result2=mysqli_query($dbc, $query2);
	    		?>
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <?php echo $row['genre']; ?>
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
	        <?php 
	        	while ($subrow=mysqli_fetch_assoc($result2)) {
	         ?>
	          <a class="dropdown-item" href="genre_fe.php?genre=<?=$subrow['id']?>"><?php echo $subrow['genre']; ?></a>
	          <?php } ?>
	  
	        </div>
	      </li>
	      <?php } ?>
	      <li class="navbar-item"><a class="nav-link" href="cart.php"><span class="fa fa-shopping-cart"></span>&nbsp;My Cart</a></li>
	    </ul>
	  </div>
	</nav>  
<!-- end of navigation bar -->
