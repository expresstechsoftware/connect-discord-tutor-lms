(function( $ ) {
	'use strict';

	/*Call-back on disconnect from discord and kick student if the case */
	$(document).on('click', '#tutor-lms-discord-disconnect-discord', function (e) {
            
		e.preventDefault();
		var userId = $(this).data('user-id');
		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: etsTutorLms.admin_ajax,
			data: { 'action': 'tutor_lms_disconnect_from_discord', 'user_id': userId, 'ets_tutor_lms_discord_nonce': etsTutorLms.ets_tutor_lms_discord_nonce },
			beforeSend: function () {
				$(".ets-spinner").addClass("ets-is-active");
			},
			success: function (response) {   
                   
				if (response.status == 1) {
					window.location = window.location.href.split("?")[0];
				}
			},
			error: function (response ,  textStatus, errorThrown) {
				console.log( textStatus + " :  " + response.status + " : " + errorThrown );
			}
		});
	});                

})( jQuery );
