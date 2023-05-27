$(document).ready(function(){
	
	$('#jenis').change(function(){
		$('#consultation-other').hide();
		if($(this).val() == 99) {
			$('#consultation-other').show();
		}
	})
	$('#rate').keyup(function(){
		this.value = this.value.replace(/\D/g, '');
		var	number_string = this.value.toString(),
			sisa 	= number_string.length % 3,
			rupiah 	= number_string.substr(0, sisa),
			ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
				
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		this.value = rupiah;
	});
	$('#id-consultation-status').change(function(){
		$('#date-finished-row').hide();
		if($(this).val() == 3) {
			$('#date-finished-row').show();
		}
	})
})