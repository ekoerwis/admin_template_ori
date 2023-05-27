jQuery(document).ready(function () {
	$('.nama-penghadap, .penanggung-jawab').select2();
	$('.remove-current-file').click(function(){ 
		$parent = $(this).parent().hide();
		$parent.find('.delete-current-file').val(1);
	});
	
	
	$('.add-row').on('click', function(){
		$parent = $(this).parent();
		$container = $parent.parent();
		$clone = $parent.clone();
		$clone.find('input, textarea').val('').attr('required', 'required');
		$clone.find('a').removeAttr('class').addClass('btn btn-danger btn-xs delete-row mt-2').html('Hapus File');
		$clone.find('label').html('');
		$clone.find('.upload-img-thumb').hide();
		$clone.children().eq(0).show();
		
		// Find DIV row before submit and text muted
		index = $container.children().length - 1;
		console.log(index);
		$last = $container.children().eq(index);
		
		$clone.insertAfter($last);
	});
	
	$('#form-container').on('click', '.delete-row', function(){
		$(this).parent().remove();
		
	});
	
});