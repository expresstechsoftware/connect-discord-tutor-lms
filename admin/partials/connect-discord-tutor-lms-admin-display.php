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
				<button class="skltbs-tab" data-identity="tutorlms_application" ><?php esc_html_e( 'Application Details', 'connect-discord-tutor-lms' ); ?><span class="initialtab spinner"></span></button>
				</li>
				<li class="skltbs-tab-item">
					<?php if ( ets_tutor_lms_discord_check_saved_settings_status() ) : ?>
						<button class="skltbs-tab" data-identity="level-mapping" ><?php esc_html_e( 'Role Mapping', 'connect-discord-tutor-lms' ); ?></button>
					<?php endif; ?>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" ><?php esc_html_e( 'Advanced', 'connect-discord-tutor-lms' ); ?>	
				</button>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="appearance" ><?php esc_html_e( 'Appearance', 'connect-discord-tutor-lms' ); ?>	
				</button>				
				</li>	
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" ><?php esc_html_e( 'Logs', 'connect-discord-tutor-lms' ); ?>	
				</button>
				</li> 														                            
			</ul>
			<div class="skltbs-panel-group">
				<div id="ets_tutor_lms_application_details" class="tutor-lms-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-discord-tutor-lms-application-details.php'; ?>
				</div>
				<?php if ( ets_tutor_lms_discord_check_saved_settings_status() ) : ?>  
				<div id='ets_tutor_lms_role_level' class="skltbs-panel">
					<?php require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-discord-tutor-lms-roles-mapping.php'; ?>
				</div>
				<?php endif; ?>	
				<div id='ets_tutor_lms_discord_advanced' class="skltbs-panel">
				<?php require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-discord-tutor-lms-advanced.php'; ?>
				</div>	
				<div id='ets_tutor_lms_discord_appearance' class="tutor-lms-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-discord-tutor-lms-appearance.php'; ?>
				</div>	
				<div id='ets_tutor_lms_discord_logs' class="tutor-lms-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-discord-tutor-lms-log.php'; ?>
				</div>																								
			</div>  
		</div>


