jQuery(document).ready(function () 
{	
	function formatRibuan(nilai) 
	{
		var	number_string = nilai.toString(),
			sisa 	= number_string.length % 3,
			rupiah 	= number_string.substr(0, sisa),
			ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
				
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		return 'Rp ' + rupiah;
	}
	
	$('#check-voucher').click(function(e) 
	{
		e.preventDefault();
		
		$this = $(this);
		$cont = $this.parent().parent().parent();
		$cont.find('.alert').remove();
		$loader = $this.children('span').show();
		
		if ($this.hasClass('disabled')) {
			return false;
		}
		
		$this.addClass('disabled');
		// return false;
		var field_name = $this.prev().attr('name');
		var voucher_code = $this.prev().val();
			
		$.ajax({
			url: 'http://localhost/order/voucher'
			, method: 'POST'
			, data: field_name + '=' + voucher_code
			, success : function(data) {
				json = $.parseJSON(data);
				// console.log(json);
				$loader.hide();
				
				if (json.status == 'error') {
					$cont.append('<small class="alert alert-danger">'+json.message+'</small>');
					$this.removeClass('disabled');
				} else {
					$row = $('#row-voucher').show();
					$row.find('span').eq(1).html('-'+ formatRibuan(json.message.value));
					// voucher = json.message.value.replace(/\D/g, '');
					voucher = json.message.value;
					
					$row_total = $('#row-total');
					
					bayar = json.message.total;
					bayar = bayar-voucher;
					// console.log(bayar);
					if (bayar >= 1000) {
						bayar = formatRibuan(bayar);
					}
					$row_total.find('span').eq(1).html(bayar);
					$('#use-voucher-container').addClass('hide');
					$('#use-voucher-btn').addClass('used');
					$('#use-voucher-container').find('input').attr('readonly', 'readonly');
					$('#use-voucher').val(1);
					$this.removeClass('disabled');
				}
			}
			, error: function (xhr) {
				console.log(xhr);
				$loader.hide();
				$cont.append('<small class="alert alert-danger">System error...</small>');
				$this.removeClass('disabled');
			}
		})
	});
	
	$('#cancel-voucher').click(function() 
	{
		$this = $(this);
		
		if ($this.hasClass('disabled')) {
			return false;
		}
		
		$this.addClass('disabled');
		
		$.ajax({
			url: 'http://localhost/order/voucherCancel'
			, method: 'POST'
			, success : function(data) 
			{
				json = $.parseJSON(data);
				console.log(json);
				$row = $('#row-voucher').hide();
				$row.find('span').eq(1).html(0);
				$row_total.find('span').eq(1).html(formatRibuan(json.total));
				$this.removeClass('disabled');
				
				$('#use-voucher-btn').removeClass('used');
				$('#use-voucher').val(0);
				$('#use-voucher-container').find('input').removeAttr('readonly');
			}
			, error: function (xhr) {
				console.log(xhr);
				$('#use-voucher').val(0);
				$this.removeClass('disabled');
			}
		})
	});
	
	$('#use-voucher-btn').click(function()
	{
		if ($(this).hasClass('used'))
			return false;
		
		$('#use-voucher-container').stop(true, true).toggleClass('hide');
	});
});