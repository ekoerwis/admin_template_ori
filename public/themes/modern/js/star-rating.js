/*
Behavior
Jika mouse enter maka beri nilai rating-value menjadi 0 karena mouse leave akan selalu mengecek ini
Jika mouse leave maka cek nilai rating value
Jika rating di klik
- Jika modal di close, set nilai rating-value menjadi 0
- Jika submit modal, set nilai rating-value menjadi 0, jika status oke maka stars-selected width nya disesuaikan. jika status error maka biarkan apa adanya
- Tambahan script ada di product.js
wdi = WebDev Indonesia

*/

$(document).ready(function(){
	$rating_input = $('.wdi-stars-container').children('.rating-value');
	$('.wdi-stars-container').mouseenter(function(){
		$('.stars-selected').css('display', 'none');
	}).mouseleave(function(){
		rating_val = $rating_input.val();
		if (!rating_val) {
			$('.stars-hover i').removeClass('star-item-selected');
			$('.stars-selected').css('display', 'flex');
		}
	});
	
	$('.stars-hover i').hover(function() {
		
		$(this).siblings().removeClass('star-item-selected');
		$(this).addClass('star-item-selected');
		$(this).prevAll().addClass('star-item-selected');
	});
	
	$('.stars-hover i').click(function() {
		// $('.stars-selected').remove();
		rating_val = $(this).index() + 1;
		$(this).parent().parent().children('.rating-value').val(rating_val);
		$(this).parent().children('i:lt(' + (rating_val) + ')').addClass('star-item-selected');
		
	});
});