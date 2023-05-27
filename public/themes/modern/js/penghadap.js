jQuery(document).ready(function () {
	$('#add-row').on('click', function(){
		$source = $(this).parent().parent();
		$container = $source.parent();
		$clone = $source.clone();
		$clone.find('input').val('');
		$clone.find('a').removeAttr('class').addClass('btn btn-danger delete-row').removeAttr('id').find('i').removeAttr('class').addClass('fas fa-times');
		
		// Find DIV row before submit and text muted
		index = $container.children().length - 2 - 1;
		console.log(index);
		$last = $container.children().eq(index);
		
		$clone.insertAfter($last);
	});
	
	$('#form-container').on('click', '.delete-row', function(){
		$(this).parent().parent().remove();
		
	});
	
});