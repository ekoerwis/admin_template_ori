function show_alert(title, content, icon, timer) {

	Swal.fire({
		title: title,
		text: content,
		icon: icon,
		showConfirmButton: false,
		timer: timer
	})
}

function generate_alert(type, message) {
	return '<div class="alert alert-dismissible alert-'+type+'" role="alert">' + message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	
}