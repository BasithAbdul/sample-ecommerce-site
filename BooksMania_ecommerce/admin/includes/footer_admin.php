<script type="text/javascript">
	function get_sub(selected)
	{
		if (typeof selected==='undefined') {
			var selected='';
		}
		var parent_id=$('#selected_parent').val();
		$.ajax({
			url: '/BooksMania/admin/parsers/sub_genre.php',
			method: 'POST',
			data: {parent_id:parent_id, selected: selected},
			success: function(data){
				$('#sub_genre').html(data);
			},
			error: function(){
				alert("Something went wrong while getting subgenre.");
			}
		});
	}
	$('select[id="selected_parent"]').change(get_sub);
</script>

</body>



</html>