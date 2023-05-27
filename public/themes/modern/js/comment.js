/**
* Written by: Agus Prawoto Hadi
* Year		: 2020
* Website	: jagowebdev.com
*/

jQuery(document).ready(function () 
{
	column = $.parseJSON($('#dataTables-column').html());
	setting = $.parseJSON($('#dataTables-setting').html());
	url = $('#dataTables-url').html();
	datatable =  $('#table-result').DataTable( {
        "processing": true,
        "serverSide": true,
		"scrollX": true,
		"order" :setting.order,
		"ajax": {
            "url": url,
            "type": "POST"
        },
        "columns": column,
		"initComplete": function( settings, json ) {
			datatable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
				$row = $(this.node());
				$attr = $row.find('.comment-attribute');
				if ($attr.length) {
					attr = $.parseJSON($attr.html());
					if (attr.status == 1) {
						$row.addClass('comment-pending');
					}
				}
				/* this
					.child(
						$(
							'<tr>'+
								'<td>'+rowIdx+'.1</td>'+
								'<td>'+rowIdx+'.2</td>'+
								'<td>'+rowIdx+'.3</td>'+
								'<td>'+rowIdx+'.4</td>'+
							'</tr>'
						)
					)
					.show(); */
			} );
		 }
    } );
	
	$('table').delegate('.comment-reply', 'click', function(e) {
		e.preventDefault();
		$this = $(this);
		$parent = $this.parents('.row-action').eq(0);
		$comment_form = $this.parents('td').eq(0).find('.comment-container');
		if ($comment_form.length > 0) {
			$comment_form.remove();
			return;
		}
	
		$('<div class="comment-container"><form method="post" action="' + $(this).attr('href') + '"><div class="form-group row"><div class="col"><textarea class="form-control" name="comment"></textarea></div></div><div class="row"><div class="col"><button class="btn btn-sm btn-success mr-1 submit-reply">Approve & Reply</button><button class="btn btn-sm btn-danger comment-reply-close">Cancel</button><input type="hidden" name="form_comment_admin" value="'+$this.attr('data-token')+'"/><input type="hidden" name="id" value="'+$this.attr('data-id')+'"/></div></div></form></div>').insertBefore($parent);
	});
	
	$('table').delegate('.comment-reply-close', 'click', function(e) {
		$(this).parents('.comment-container').eq(0).remove();
	});
	
	$('table').delegate('.comment-proses', 'click', function(e) {
		e.preventDefault();
		$this = $(this);
		$container = $this.parent();
		$this.addClass('pl-0');
		$loader = $('<i class="fas fa-circle-notch fa-spin mr-2 fa-lg"></i>');
		$container.prepend($loader);
	
		$.get($this.attr('href'), function(data){
			// console.log(data);
			$this.removeClass('pl-0');
			$loader.remove();
			title = data.status == 'ok' ? 'Sukses !!!' : 'Error...';
			icon = data.status == 'ok' ? 'success' : 'error';
			show_alert(title, data.content, icon, 1800);
			
			action = $this.attr('data-action');
			if (action == 'approve') {
				$this.parents('tr').eq(0).removeClass('comment-pending');
			}
			
			if (action == 'trash' || action == 'spam') {
				$this.parents('tr').eq(0).remove();
				// datatable.clear().draw();
			}
			
			if(data.status == 'ok') {
				if ($this.attr('data-action') == 'approve' && data.nonce_unapprove != '') {
					href_split = $this.attr('href').split('token=');
					new_href = href_split[0] + 'token=' + data.nonce_unapprove;
					$this.attr('data-action', 'unapprove');
					$this.attr('href', new_href);
					$this.html('Unapprove');
				}
			}
			
		}, "json");
	});
	
	// Click Approve & Reply
	$('table').delegate('.submit-reply', 'click', function(e) {
		e.preventDefault();
		$this = $(this);
		$container = $this.parents('.comment-container').eq(0);
		$container.find('alert').remove();
		$loader = $('<i class="fas fa-circle-notch fa-spin mr-2 fa-lg"></i>');
		$this.prepend($loader);
		$buttons = $this.parent().find('button').attr('disabled', 'disabled');
		
		$form = $this.parents('form').eq(0);
		$.ajax({
			'url' : $form.attr('action'),
			'method' : 'POST',
			'data' : $form.serialize() + '&submit=submit',
			success: function(data) {
				console.log(data);
				data = $.parseJSON(data);
				alert_message = '<small style="display:block;margin-bottom: 2px;" class="alert  alert-success" role="alert">Data berhasil disimpan</small>';
				$container.prepend(alert_message);
				show_alert('Sukses !!!', 'Data berhasil disimpan', 'success', 1800);
				$this.find('i').remove();
				$buttons.removeAttr('disabled');
				$this.parents('tr').removeClass('comment-pending');
				$a = $this.parents('td').eq(0).find('a[data-action="approve"]');
				
				$this.parents('form').eq(0).remove();
				
				// Replace Approve to Unapprove
				if(data.status == 'ok') {
					href_split = $a.attr('href').split('token=');
					new_href = href_split[0] + 'token=' + data.nonce_unapprove;
					$a.attr('data-action', 'unapprove');
					$a.attr('href', new_href);
					$a.html('Unapprove');
				}
			},
			error: function(xhr) {
				$this.find('i').remove();
				$buttons.removeAttr('disabled');
				alert('AJAX error: lihat console');
				console.log(xhr);
			}
			
		}, "json");
	});

});