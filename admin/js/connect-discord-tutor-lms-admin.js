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
		/*Load all roles from discord server*/
		$.ajax({
			type: "POST",
			dataType: "JSON",
			url:etsTutorLms.admin_ajax,
			data: { 'action': 'ets_tutor_lms_load_discord_roles', 'ets_tutor_lms_discord_nonce': etsTutorLms.ets_tutor_lms_discord_nonce, },
			beforeSend: function () {
				$(".discord-roles .spinner").addClass("is-active");
				$(".initialtab.spinner").addClass("is-active");
			},

			success: function (response) {
				//console.log(response);
				if (response != null && response.hasOwnProperty('code') && response.code == 50001 && response.message == 'Missing Access'){
					$(".tutor-lms-btn-connect-to-bot").show();
				} else if ( response.code === 10004 && response.message == 'Unknown Guild' ) {
					$(".tutor-lms-btn-connect-to-bot").show().after('<p><b>The server ID is wrong or you did not connect the Bot.</b></p>');
				}else if( response.code === 0 && response.message == '401: Unauthorized' ) {
					$(".tutor-lms-btn-connect-to-bot").show().html("Error: Unauthorized - The Bot Token is wrong").addClass('error-bk');										
				} else if (response == null || response.message == '401: Unauthorized' || response.hasOwnProperty('code') || response == 0) {
					$("#tutor-lms-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
				} else {
					if ($('.ets-tabs button[data-identity="level-mapping"]').length) {
						$('.ets-tabs button[data-identity="level-mapping"]').show();
					}
					$("#tutor-lms-connect-discord-bot").show().html("Bot Connected <i class='fab fa-discord'></i>").addClass('not-active');
					
					var activeTab = localStorage.getItem('activeTab');
						if ($('.ets-tabs button[data-identity="level-mapping"]').length == 0 && activeTab == 'level-mapping') {
							$('.ets-tabs button[data-identity="tutorlms_application"]').trigger('click');
						}
						/* fetch all roles from discord server */

					$.each(response, function (key, val) {
						var isbot = false; 
						
						if (val.hasOwnProperty('tags')) {
							if (val.tags.hasOwnProperty('bot_id')) {
								isbot = true;
							}
						}
						if (key != 'previous_mapping' && isbot == false && val.name != '@everyone') {
							$('.discord-roles').append('<div class="makeMeDraggable" style="background-color:#' + val.color.toString(16) + '" data-role_id="' + val.id + '" >' + val.name + '</div>');
							$('#defaultRole').append('<option value="' + val.id + '" >' + val.name + '</option>');
							makeDrag($('.makeMeDraggable'));
						}
						
					});

					var defaultRole = $('#selected_default_role').val();
						if (defaultRole) {
							$('#defaultRole option[value=' + defaultRole + ']').prop('selected', true);
						}

						if (response.previous_mapping) {
							var mapjson = response.previous_mapping;
						} else {
							var mapjson = localStorage.getItem('TutorlmsMappingjson');
						}



					$("#ets_tutor_lms_mapping_json_val").html(mapjson);
						$.each(JSON.parse(mapjson), function (key, val) {
							var arrayofkey = key.split('id_');
							var preclone = $('*[data-role_id="' + val + '"]').clone();
							if(preclone.length>1){
								preclone.slice(1).hide();
							}
							if (jQuery('*[data-course_id="' + arrayofkey[1] + '"]').find('*[data-role_id="' + val + '"]').length == 0) {
								$('*[data-course_id="' + arrayofkey[1] + '"]').append(preclone).attr('data-drop-role_id', val).find('span').css({ 'order': '2' });
							}
							if ($('*[data-course_id="' + arrayofkey[1] + '"]').find('.makeMeDraggable').length >= 1) {
								$('*[data-course_id="' + arrayofkey[1] + '"]').droppable("destroy");
							}
							preclone.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'order': '1' }).attr('data-course_id', arrayofkey[1]);
							makeDrag(preclone);
							
						});

				}
			},
			error: function (response) {
				$("#tutor-lms-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
				console.error(response);
			},
			complete: function () {
				$(".discord-roles .spinner").removeClass("is-active").css({ "float": "right" });
				$("#skeletabsTab1 .spinner").removeClass("is-active").css({ "float": "right", "display": "none" });
			}	
		});
		
		/*Create droppable element*/
				
		function init() {
			if ( $('.makeMeDroppable').length){
				$('.makeMeDroppable').droppable({
					drop: handleDropEvent,
					hoverClass: 'hoverActive',
				});
			}
			if ( $('.discord-roles-col').length){
				$('.discord-roles-col').droppable({
					drop: handlePreviousDropEvent,
					hoverClass: 'hoverActive',
				});
			}

		}

		$(init);

		/*Create draggable element*/

		function makeDrag(el) {

		el.draggable({
			revert: "invalid",
			helper: 'clone',
			start: function(e, ui) {
			ui.helper.css({"width":"45%"});
			}
		});
		}


		function handlePreviousDropEvent(event, ui) {
		var draggable = ui.draggable;
		if(draggable.data('course_id')){
			$(ui.draggable).remove().hide();
		}

		$(this).append(draggable);
		$('*[data-drop-role_id="' + draggable.data('role_id') + '"]').droppable({
			drop: handleDropEvent,
			hoverClass: 'hoverActive',
		});
		$('*[data-drop-role_id="' + draggable.data('role_id') + '"]').attr('data-drop-role_id', '');

		var oldItems = JSON.parse(localStorage.getItem('tutorlmsMapArray')) || [];
		$.each(oldItems, function (key, val) {
			if (val) {
				var arrayofval = val.split(',');
				if (arrayofval[0] == 'course_id_' + draggable.data('course_id') && arrayofval[1] == draggable.data('role_id')) {
					delete oldItems[key];
				}
			}
		});

		var jsonStart = "{";
		$.each(oldItems, function (key, val) {
			if (val) {
				var arrayofval = val.split(',');
				if (arrayofval[0] != 'course_id_' + draggable.data('course_id') || arrayofval[1] != draggable.data('role_id')) {
					jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
				}
			}
		});
		localStorage.setItem('tutorlmsMapArray', JSON.stringify(oldItems));
		var lastChar = jsonStart.slice(-1);
		if (lastChar == ',') {
			jsonStart = jsonStart.slice(0, -1);
		}

		var TutorlmsMappingjson = jsonStart + '}';
		$("#ets_tutor_lms_mapping_json_val").html(TutorlmsMappingjson);
		localStorage.setItem('TutorlmsMappingjson', TutorlmsMappingjson);
		draggable.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '10px' });
		}


		function handleDropEvent(event, ui) {
			var draggable = ui.draggable;
			var newItem = [];

			var newClone = $(ui.helper).clone();
			if($(this).find(".makeMeDraggable").length >= 1){
				return false;
			}
			$('*[data-drop-role_id="' + newClone.data('role_id') + '"]').droppable({
				drop: handleDropEvent,
				hoverClass: 'hoverActive',
			});
			$('*[data-drop-role_id="' + newClone.data('role_id') + '"]').attr('data-drop-role_id', '');
			if ($(this).data('drop-role_id') != newClone.data('role_id')) {
				var oldItems = JSON.parse(localStorage.getItem('tutorlmsMapArray')) || [];
				$(this).attr('data-drop-role_id', newClone.data('role_id'));
				newClone.attr('data-course_id', $(this).data('course_id'));

				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'course_id_' + $(this).data('course_id') ) {
							delete oldItems[key];
						}
					}
				});

				var newkey = 'course_id_' + $(this).data('course_id');
				oldItems.push(newkey + ',' + newClone.data('role_id'));
				var jsonStart = "{";
				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'course_id_' + $(this).data('course_id') || arrayofval[1] != newClone.data('role_id') && arrayofval[0] != 'course_id_' + $(this).data('course_id') || arrayofval[1] == newClone.data('role_id')) {
							jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
						}
					}
				});

				localStorage.setItem('tutorlmsMapArray', JSON.stringify(oldItems));
				var lastChar = jsonStart.slice(-1);
				if (lastChar == ',') {
					jsonStart = jsonStart.slice(0, -1);
				}

				var TutorlmsMappingjson = jsonStart + '}';
				localStorage.setItem('TutorlmsMappingjson', TutorlmsMappingjson);
				$("#ets_tutor_lms_mapping_json_val").html(TutorlmsMappingjson);
			}

			$(this).append(newClone);
			$(this).find('span').css({ 'order': '2' });
			if (jQuery(this).find('.makeMeDraggable').length >= 1) {
				$(this).droppable("destroy");
			}
			makeDrag($('.makeMeDraggable'));

			newClone.css({ 'width': '100%','margin-bottom': '0px', 'left': '0', 'position':'unset', 'order': '1' });

		}	
		/*Flush settings from local storage*/
		$("#tutorlmsRevertMapping").on('click', function () {
			localStorage.removeItem('tutorlmsMapArray');
			localStorage.removeItem('TutorlmsMappingjson');
			window.location.href = window.location.href;
		});
		$('#ets_tutor_lms_discord_connect_button_bg_color').wpColorPicker();
		$('#ets_tutor_lms_discord_disconnect_button_bg_color').wpColorPicker();

				/*Clear log log call-back*/
				$('#ets-tutor-lms-clrbtn').click(function (e) {
					e.preventDefault();
					$.ajax({
						url: etsTutorLms.admin_ajax,
							type: "POST",
							data: { 'action': 'ets_tutor_lms_discord_clear_logs', 'ets_tutor_lms_discord_nonce': etsTutorLms.ets_tutor_lms_discord_nonce },
							beforeSend: function () {
								$(".clr-log.spinner").addClass("is-active").show();
							},
							success: function (data) {
					 
								if (data.error) {
									// handle the error
									alert(data.error.msg);
								} else {
															
									$('.error-log').html("Clear logs Sucesssfully !");
								}
							},
							error: function (response, textStatus, errorThrown ) {
								console.log( textStatus + " :  " + response.status + " : " + errorThrown );
							},
							complete: function () {
								$(".clr-log.spinner").removeClass("is-active").hide();
							}
						});
					});
			
		}); // DOM ready 

	} // Admin
	$.skeletabs.setDefaults({
		keyboard: false,
	});

})( jQuery );
