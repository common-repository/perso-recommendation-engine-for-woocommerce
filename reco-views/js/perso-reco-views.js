jQuery(document).ready(function($) {
	
	jQuery('#ajax-views-loader').show();
			
	jQuery.ajax({
		url : ajax_object.ajax_url,
		type : 'post',
		data : {
			action : 'perso_input_action',
			post_id : ajax_object.post_id
		},
		success : function( response ) {

	
		}
	});	
			
	jQuery.ajax({
		url : ajax_object.ajax_url,
		type : 'post',
		data : {
			action : 'perso_views_action',
			post_id : ajax_object.post_id
		},
		success : function( response ) {
			
			jQuery('#ajax-views-loader').hide();
			
			if (response != 'empty'){
				jQuery('#perso-others-views').html( response );
				jQuery('#perso-others-views').show();
			}else{
				jQuery('#perso-others-views-title').hide();
			}	
		}
	});
})

