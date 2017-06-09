jQuery(document).ready(function(){
	jQuery('#contactForm').submit(function(){
		if(contactForm.validator.validate()) {
			var action = jQuery(this).attr('action');
			jQuery('.ajax-contact-overlay').fadeIn(200, function () {
				jQuery('.ajax-contact-loader').show();
			});
			jQuery.post(action, {
					name: jQuery('#name').val(),
					email: jQuery('#email').val(),
					telephone: jQuery('#telephone').val(),
					comment: jQuery('#comment').val(),
					product_name: jQuery('#product_name').val(),
					product_url: jQuery('#product_url').val()
				}, function (data) {
					jQuery('#contactForm #submit').attr('disabled', 'true');
					jQuery('.response').remove();
					jQuery('#contactForm').before(data);
					jQuery('.ajax-contact-loader').hide();
					jQuery('.ajax-contact-overlay').fadeOut(200);
					jQuery('.response').slideDown();

				}
			);
			return false;
		}
	});
});