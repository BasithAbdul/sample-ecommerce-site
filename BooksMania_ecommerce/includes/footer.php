<footer>
	<div>
		<hr>
		BooksMania&trade; is a conceptual e-commerce platform using jQuery, Bootstrap, PHP and MySQL. The trademark symbol is for aesthetic purposes only.  
		<hr>
	</div>	
</footer>














<script type="text/javascript">
	function detailsModal(id)
{
	var data={"id": id};
	jQuery.ajax({
		url: "includes/details-modal.php",
		method: "post",
		data: data,
		success: function(data){
			jQuery('body').append(data);
			jQuery('#details-modal').modal('toggle');
		},
		error: function(){
			alert("Something went wrong!");
		}
	});
}

function update_cart(mode, edit_id)
{
	var data={"mode": mode, "edit_id": edit_id};
	$.ajax({
		url: "/BooksMania/admin/parsers/update_cart.php",
		method: "post",
		data: data,
		success: function(){
			location.reload();
		},
		error: function(){
			alert("Something went wrong");
		}
	});
}


function add_to_cart()
{
	$('#modal_errors').html('');
	var quantity=$('#quantity').val();
	var available=$('#available').val();
	var error='';
	var data=$('#product_form').serialize();
	if(quantity==='')
	{
		error+='<p class="alert alert-danger">Please choose quantity</p>';
		$('#modal_errors').html(error);
		return;
	}
	else if(quantity>available)
	{
		error+='<p class="alert alert-danger">Quantity greater than available</p>';
		$('#modal_errors').html(error);
		return;
	}
	else
	{
		$.ajax({
			url: "/BooksMania/admin/parsers/add_cart.php",
			method: "post",
			data: data,
			success: function(){
				location.reload();
			},
			error: function(){alert("something went wrong!");}
		});
	}
}
</script>

</body>



</html>