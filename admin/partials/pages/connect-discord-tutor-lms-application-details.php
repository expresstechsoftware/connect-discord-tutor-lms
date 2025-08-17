<?php
$ets_tutor_lms_discord_client_id          = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_id' ) ) );
$ets_tutor_lms_discord_client_secret      = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_secret' ) ) );
$ets_tutor_lms_discord_bot_token          = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
$ets_tutor_lms_discord_redirect_url       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_redirect_url' ) ) );
$ets_tutor_lms_discord_roles              = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_role_mapping' ) ) );
$ets_tutor_lms_discord_server_id          = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
$ets_tutor_lms_discord_connected_bot_name = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_connected_bot_name' ) ) );
$ets_tutor_lms_discord_redirect_page_id   = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_redirect_page_id' ) ) );
?>
<form method="post" action="<?php echo esc_url( get_site_url() ) . '/wp-admin/admin-post.php'; ?>">
  <input type="hidden" name="action" value="tutor_lms_discord_application_settings">
  <input type="hidden" name="current_url" value="<?php echo esc_url( ets_tutor_lms_discord_get_current_screen_url() ); ?>">   
	<?php wp_nonce_field( 'save_tutor_lms_discord_general_settings', 'ets_tutor_lms_discord_save_settings' ); ?>
  <div class="ets-input-group">
	<?php $ets_tutor_lms_discord_client_id_value = isset( $ets_tutor_lms_discord_client_id ) ? $ets_tutor_lms_discord_client_id : ''; ?>
	<label><?php esc_html_e( 'Client ID', 'connect-discord-tutor-lms' ); ?> :</label>
	<input type="text" class="ets-input" name="ets_tutor_lms_discord_client_id" value="<?php echo esc_attr( $ets_tutor_lms_discord_client_id_value ); ?>" required placeholder="Discord Client ID">
  </div>
	<div class="ets-input-group">
		<?php $ets_tutor_lms_discord_client_secret_value = isset( $ets_tutor_lms_discord_client_secret ) ? $ets_tutor_lms_discord_client_secret : ''; ?>
	  <label><?php esc_html_e( 'Client Secret', 'connect-discord-tutor-lms' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_tutor_lms_discord_client_secret" value="<?php echo esc_attr( $ets_tutor_lms_discord_client_secret_value ); ?>" required placeholder="Discord Client Secret">
	</div>
	<div class="ets-input-group">
	<label><?php esc_html_e( 'Redirect URL', 'connect-discord-tutor-lms' ); ?> :</label>

	<p class="redirect-url"><b><?php echo esc_url( $ets_tutor_lms_discord_redirect_url ); ?></b></p>
		<select class= "ets-input ets_tutor_lms_discord_redirect_url" id="ets_tutor_lms_discord_redirect_url" name="ets_tutor_lms_discord_redirect_url" style="width: 100%" required>
		<?php _e( ets_tutor_lms_discord_pages_list( wp_kses( $ets_tutor_lms_discord_redirect_page_id, array( 'option' => array( 'data-page-url' => array() ) ) ) ) ); ?>
		</select>                
				
		<p class="description"><?php esc_html_e( 'Registered discord app url', 'connect-discord-tutor-lms' ); ?><span class="spinner"></span></p>
				<p class="description ets-discord-update-message"><?php _e( sprintf( wp_kses( __( 'Redirect URL updated, kindly add/update the same in your discord.com application link <a href="https://discord.com/developers/applications/%s/oauth2/general">https://discord.com/developers</a>', 'connect-discord-tutor-lms' ), array( 'a' => array( 'href' => array() ) ) ), $ets_tutor_lms_discord_client_id ) ); ?></p>                
	</div>
	<div class="ets-input-group">
			<label><?php esc_html_e( 'Admin Redirect URL Connect to bot', 'connect-discord-tutor-lms' ); ?> :</label>
			<input type="text" class="ets-input" name="ets_tutor_lms_discord_admin_redirect_url" value="<?php echo esc_url( get_admin_url( '', 'admin.php' ) ) . '?page=connect-discord-tutor-lms&via=tutor-lms-discord-bot'; ?>" readonly required />
			<p class="description msg-green"><b><?php esc_html_e( 'Copy this URL and paste inside your https://discord.com/developers/applications -> 0Auth2 -> Redirects', 'connect-discord-tutor-lms' ); ?></b></p>
		</div>   
	<div class="ets-input-group">
			<?php
			if ( isset( $ets_tutor_lms_discord_connected_bot_name ) && ! empty( $ets_tutor_lms_discord_connected_bot_name ) ) {
				_e(
					sprintf(
						wp_kses(
							__( '<p class="description">Make sure the Bot %1$s <span class="discord-bot"><b>BOT</b></span>have the high priority than the roles it has to manage. Open <a href="https://discord.com/channels/%2$s">Discord Server</a></p>', 'connect-discord-tutor-lms' ),
							array(
								'p'    => array( 'class' => array() ),
								'a'    => array( 'href' => array() ),
								'span' => array( 'class' => array() ),
								'b'    => array(),
							)
						),
						$ets_tutor_lms_discord_connected_bot_name,
						$ets_tutor_lms_discord_server_id
					)
				);
			}
			?>
			<?php $ets_tutor_lms_discord_bot_token_value = isset( $ets_tutor_lms_discord_bot_token ) ? $ets_tutor_lms_discord_bot_token : ''; ?>
	  <label><?php esc_html_e( 'Bot Token', 'connect-discord-tutor-lms' ); ?> :</label>
		  <input type="password" class="ets-input" name="ets_tutor_lms_discord_bot_token" value="<?php echo esc_attr( $ets_tutor_lms_discord_bot_token_value ); ?>" required placeholder="Discord Bot Token">
	</div>
	<div class="ets-input-group">
		<?php $ets_tutor_lms_discord_server_id_value = isset( $ets_tutor_lms_discord_server_id ) ? $ets_tutor_lms_discord_server_id : ''; ?>
	  <label><?php esc_html_e( 'Server ID', 'connect-discord-tutor-lms' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_tutor_lms_discord_server_id"
		placeholder="Discord Server Id" value="<?php echo esc_attr( $ets_tutor_lms_discord_server_id_value ); ?>" required>
	</div>
	<?php if ( empty( $ets_tutor_lms_discord_client_id ) || empty( $ets_tutor_lms_discord_client_secret ) || empty( $ets_tutor_lms_discord_bot_token ) || empty( $ets_tutor_lms_discord_redirect_url ) || empty( $ets_tutor_lms_discord_server_id ) ) { ?>
	  <p class="ets-danger-text description">
		<?php esc_html_e( 'Please save your form', 'connect-discord-tutor-lms' ); ?>
	  </p>
	<?php } ?>
	<p>
	  <button type="submit" name="submit" value="ets_discord_submit" class="ets-submit ets-btn-submit ets-bg-green">
		<?php esc_html_e( 'Save Settings', 'connect-discord-tutor-lms' ); ?>
	  </button>
	  <?php if ( sanitize_text_field( get_option( 'ets_tutor_lms_discord_client_id' ) ) ) : ?>
			<a href="?action=tutor-lms-discord-connect-to-bot" class="ets-btn tutor-lms-btn-connect-to-bot" id="tutor-lms-connect-discord-bot"><?php esc_html_e( 'Connect your Bot', 'connect-discord-tutor-lms' ) . Connect_Discord_Tutor_Lms::get_discord_logo_white(); ?> </a>
	  <?php endif; ?>
	</p>
</form>
