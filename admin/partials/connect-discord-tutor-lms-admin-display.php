<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/admin/partials
 */
?>
<?php
if ( isset( $_GET['save_settings_msg'] ) ) {
	?>
	<div class="notice notice-success is-dismissible support-success-msg">
		<p><?php echo esc_html( $_GET['save_settings_msg'] ); ?></p>
	</div>
	<?php
}
?>
<h1><?php esc_html_e( 'TUTOR LMS Discord Add On Settings', 'connect-discord-tutor-lms' ); ?></h1>
		<div id="tutor-lms-discord-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="settings" ><?php esc_html_e( 'Application Details', 'connect-discord-tutor-lms' ); ?><span class="initialtab spinner"></span></button>
				</li>				                            
			</ul>
			<div class="skltbs-panel-group">
				<div id="ets_tutor_lms_application_details" class="tutor-lms-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-discord-tutor-lms-application-details.php'; ?>
				</div>
											
			</div>  
		</div>


