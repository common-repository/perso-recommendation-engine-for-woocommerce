jQuery(document).ready(function($) {
	
	jQuery('#ajax-buys-loader').show();
			
	jQuery.ajax({
		url : ajax_object.ajax_url,
		type : 'post',
		data : {
			action : 'perso_buys_action',
			post_id : ajax_object.post_id
		},
		success : function( response ) {

			jQuery('#ajax-buys-loader').hide();
				
			if (response != 'empty'){
				jQuery('#perso-others-buys').html( response );
				jQuery('#perso-others-buys').show();
			}else{
				jQuery('#perso-others-buys-title').hide();
			}
		}
	});
})