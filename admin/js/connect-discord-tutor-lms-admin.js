(function( $ ) {
	'use strict';
	if (etsTutorLms.is_admin) {
		$(document).ready(function(){
			if(jQuery().select2) {
				$('#ets_tutor_lms_discord_redirect_url').select2({ width: 'resolve' });
				$('#ets_tutor_lms_discord_redirect_url').on('change', function(){
					$.ajax({
						url: etsTutorLms.admin_ajax,
						type: "POST",
						context: this,
						data: { 'action': 'ets_tutor_lms_discord_update_redirect_url', 'ets_tutor_lms_page_id': $(this).val() , 'ets_tutor_lms_discord_nonce': etsTutorLms.ets_tutor_lms_discord_nonce },
						beforeSend: function () {
							$('p.redirect-url').find('b').html("");
							$('p.ets-discord-update-message').css('display','none');                                               
							$(this).siblings('p.description').find('span.spinner').addClass("ets-is-active").show();
						},
						success: function (data) {
							console.log(data);
							$('p.redirect-url').find('b').html(data.formated_discord_redirect_url);
							$('p.ets-discord-update-message').css('display','block');                                               
						},
						error: function (response, textStatus, errorThrown ) {
							console.log( textStatus + " :  " + response.status + " : " + errorThrown );
						},
						complete: function () {
							$(this).siblings('p.description').find('span.spinner').removeClass("ets-is-active").hide();
						}
					});
	
				});                        
			}			
		}); // DOM ready 

	} // Admin
	$.skeletabs.setDefaults({
		keyboard: false,
	});

})( jQuery );
