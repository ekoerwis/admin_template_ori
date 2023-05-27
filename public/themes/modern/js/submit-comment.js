jQuery(document).ready(function () {
	
	var comment_container = '<div class="comment-item"><ul{{comment_reply_class}}>{{comment_item}}</ul></div>';
	var comment_item = 
	'<li id="li-comment-{{id_comment}}" class="comment-item-pending">'
	+ '<article class="comment-body" id="comment-{{id_comment}}">'
		+ '<section class="comment-content">'
			+ '<div class="comment-meta">'
				+ '<div class="img-container"><img src="{{avatar}}"></div>'
				+ '<a href="#" class="author">{{nama_user}}</a>'
				+ '<a href="#" class="date">{{tgl_comment}}</a>'
			+ '</div>'
			+ '<div class="comment-text">'
				+ '<p>{{comment_text}}</p>'
			+ '</div>'
		+ '</section>'
		+ '<section class="footer"><a class="reply-comment" id="reply-{{id_comment}}" href="javascript:void(0)"><i class="fas fa-reply"></i>&nbsp;Reply</a></section>'
	+ '</article>'
	+ '<div class="overlay"></div><div class="label-moderation">In Moderation</div>'
	+ '</li>';
	
	var $comment_counter = $('.comment-counter');
	counter = parseInt($comment_counter.html());
	$('.star-rating a').mouseenter(function(){
		$(this).parent().removeClass('checked');
	});
	
	$('.comment-container').delegate('.reply-comment', 'click', function(){
		id = $(this).attr('id');
		id_parent = id.split('-')[1];
		$form = $('.comment-form-container').children().clone();
		$form.find('.alert').remove();
		$form.find('textarea').val('');
		$('.comment-form-reply').remove();
		
		$btn_close = $('<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$form.addClass('comment-form-reply');
		$form.prepend($btn_close);
		$form.find('.title').html('Tingglkan balasan');
		$form.find('textarea').val();
		$form.find('input[name="id_parent"]').val(id_parent);
		$form.find('input[type="checkbox"]').prop('checked', false);
		$(this).parent().parent().append($form);
		$btn_close.click(function(){
			$(this).parent().remove();
		});
	});
	
	$('body').delegate('.submit-comment', 'click', function(e){
		e.preventDefault();
		
		$button_submit = $(this);
		$button_submit.prepend('<i class="fas fa-circle-notch fa-spin mr-2 fa-lg"></i>');
		$button_submit.prop('disabled', true);
		$form = $button_submit.parents('form').eq(0);
		
		/* Check apakah user diperbolehkan comment (sudah login) */
		$.ajax({
			type: 'POST',
			url: base_url + '/comment/checkAllowed',
			data: $form.serialize(),
			dataType: 'text',
			success: function (data) 
			{
				// console.log(data);
				// return ;
				data = $.parseJSON(data);
				if (data.status == 'ok') 
				{
					form_data = $form.eq(0).serialize();
					$.ajax({
						type: 'POST',
						url: base_url + '/comment',
						data: form_data + '&submit=submit&current_url=' + current_url,
						dataType: 'text',
						success: function (data_submit) 
						{
							// console.log(data_submit);
							// return false;
							data = $.parseJSON(data_submit);
							if (data.status == 'ok') 
							{
								data_comment = data.data.comment;
								data_user_comment = data.data.user;
							
								$button_submit.find('i').remove();
								$button_submit.prop('disabled', false);
								console.log(data);
								comment_content = comment_item;
								comment_content = comment_content
													.replace(/{{tgl_comment}}/gi, data_comment.tgl_comment)
													.replace(/{{id_comment}}/gi, data_comment.id_comment)
													.replace(/{{avatar}}/gi, data_user_comment.url_avatar)
													.replace(/{{nama_user}}/gi, data_user_comment.nama)
													.replace(/{{comment_text}}/gi, data_comment.comment);
								
								id_parent = $form.find('input[name="id_parent"]').val();
								if ( id_parent && id_parent > 0 ) {
									$container = $form.parent().parent();
									$reply = $container.children('.comment-item');
									if ($reply.length > 0) {
										$container = $reply.children('.comment-reply');
										$new_comment = $(comment_content);
										$container.prepend($new_comment);
										
										
									} else {
										new_container = comment_container.replace('{{comment_reply_class}}', ' class="comment-reply"');
										$new_comment = $(new_container.replace('{{comment_item}}', comment_content));
										$container.append($new_comment);
										$form.remove();
									}
								} else {
									new_container = comment_container.replace('{{comment_reply_class}}', '');
									$container = $('.comment-container');
									$new_comment = $(new_container.replace('{{comment_item}}', comment_content));
									$container.prepend($new_comment);
								}
								
								$('html, body').animate({
									scrollTop: $new_comment.offset().top
								}, 500);
								$new_comment.find('.overlay').fadeTo(2500, 0.5);
								
								$form.prepend('<div class="alert alert-success alert-dismissible fade show">Komentar berhasil di submit, mohon ditunggu untuk proses moderasi<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
								
							} else {
								$button_submit.find('i').remove();
								$button_submit.prop('disabled', false);
								show_message = data.message;
								if (typeof data.message === 'object' && data.message !== null) {
									show_message = '<ul>';
									$.each(data.message, function(i, v) {
										show_message += '<li>' + v + '</li>';
									});
									show_message += '</ul>';
								}
								$form.prepend('<div class="alert alert-danger alert-dismissible fade show">' + show_message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
							}
							
							title = data.status == 'ok' ? 'Sukses !!!' : 'Error !!!';
							icon = data.status == 'ok' ? 'success' : 'error';
							Swal.fire({
								title: title,
								text: data.message,
								icon: icon,
								showConfirmButton: false,
								timer: 1800
							})
						},
						error: function (xhr) {
							console.log(xhr.responseText);
						}
					})
							
			
				} else {
					
					show_message = data.message;
					if (typeof data.message === 'object' && data.message !== null) {
						show_message = '<ul>';
						$.each(data.message, function(i, v) {
							show_message += '<li>' + v + '</li>';
						});
						show_message += '</ul>';
					}
					Swal.fire({
						title: 'Error !!!',
						html: show_message,
						icon: 'error',
						showCloseButton: true,
						confirmButtonText: 'OK'
					})
					$button_submit.find('i').remove();
					$button_submit.prop('disabled', false);
				}
			},
			error: function(xhr) {
				alert('AJAX Error: lihat console');
				console.log(xhr);
				$button_submit.find('i').remove();
				$button_submit.prop('disabled', false);
			}
		})
		
	});
	
	$('.star-rating label').click(function(){
		
		if ($('.bootbox').is(':visible')) {
			return;
		}
		
		prev_index = $(this).prevAll('label').length;
		var $form = $('#form-rating').clone().show();
		$main_rating = $('#main-rating').children().clone();
		
		// $main_rating.find('input').eq(0).attr('checked', 'checked');
		$main_rating.each(function(i, elm) 
		{
			attr_for = $(elm).attr('for');
			attr_id = $(elm).attr('id');
			if (attr_for !== undefined) {
				num = attr_for.substring(attr_for.length-1);
				$(elm).attr('for', 'rating-' + num);
			}
			
			if (attr_id !== undefined) {
				num = attr_id.substring(attr_id.length-1);
				$(elm).attr('id', 'rating-' + num);
			}
		})
		// console.log(prev_index);
		
		$form.find('.star-rating').append($main_rating);
		$form.find('.star-rating').find('input').prop('checked', false);
		$form.find('.star-rating').find('input').eq(prev_index).prop('checked', true);
		
		var $button = '';
		$.ajax({
			type: 'POST',
			url: base_url + '/comment/checkAllowed',
			data: $('#form-rating').serialize(),
			dataType: 'text',
			success: function (data) 
			{
				data = $.parseJSON(data);
				if (data.status == 'ok') 
				{
					$bootbox = bootbox.dialog({
						title: 'Beri Rating',
						message: '<div class="form-container">' +  $form[0].outerHTML + '</div>',
						buttons: {
							cancel: {
								label: 'Cancel'
							},
							success: {
								label: 'Submit',
								className: 'btn-success submit',
								callback: function() 
								{
									$bootbox.find('.alert').remove();
									$button_submit.prepend('<i class="fas fa-circle-notch fa-spin mr-2 fa-lg"></i>');
									$button.prop('disabled', true);
									$form_filled = $bootbox.find('form');
									data_post = $form_filled.serialize() + 'submit=submit&ajax=ajax';
									// console.log(data_post);
									// return false;
									$.ajax({
										type: 'POST',
										url: base_url + '/rating',
										data: $form_filled.serialize() + '&submit=submit&ajax=ajax',
										dataType: 'text',
										success: function (data_submit) 
										{
											// console.log(data);
											data = $.parseJSON(data_submit);
											
											if (data.status == 'ok') 
											{
												$bootbox.modal('hide');
												// bootbox.alert(data.message);
												Swal.fire({
													title: 'Sukses !!!',
													text: data.message,
													icon: 'success',
													showConfirmButton: false,
													timer: 1800
												})
											} else {
												$button_submit.find('i').remove();
												$button.prop('disabled', false);
												if (data.error_query != undefined) {
													Swal.fire({
														title: 'Error !!!',
														html: data.message,
														icon: 'error',
														showCloseButton: true,
														confirmButtonText: 'OK'
													})
												} else {
													show_message = data.message;
													if (typeof data.message === 'object' && data.message !== null) {
														show_message = '<ul>';
														$.each(data.message, function(i, v) {
															show_message += '<li>' + v + '</li>';
														});
														show_message += '</ul>';
													}
													$bootbox.find('.modal-body').append('<div class="alert alert-dismissible alert-danger" role="alert">' + show_message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
												}
											}
										},
										error: function (xhr) {
											console.log(xhr.responseText);
										}
									})
									return false;
								}
							}
						}
					})		
					$bootbox.find('.modal-body').empty().append($form);
					$button = $bootbox.find('button');
					$button_submit = $bootbox.find('button.submit');
				} else {
					
					show_message = data.message;
					if (typeof data.message === 'object' && data.message !== null) {
						show_message = '<ul>';
						$.each(data.message, function(i, v) {
							show_message += '<li>' + v + '</li>';
						});
						show_message += '</ul>';
					}
					Swal.fire({
						title: 'Error !!!',
						html: show_message,
						icon: 'error',
						showCloseButton: true,
						confirmButtonText: 'OK'
					})
				}
			},
			error: function(xhr) {
				alert('AJAX Error: lihat console');
				console.log(xhr);
			}
		})
		
	});
	
	$('body').delegate('.rating-comment', 'keyup', function(){
		rest_counter = counter - $(this).val().length;
		// console.log(rest_counter);
		// $comment_counter2.html(rest_counter);
		$(this).next().children().eq(0).html(rest_counter);
	});
});