<?php
$ets_tutor_lms_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_send_welcome_dm' ) ) );
$ets_tutor_lms_discord_welcome_message = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_welcome_message' ) ) );

$ets_tutor_lms_discord_send_course_complete_dm = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_send_course_complete_dm' ) ) );
$ets_tutor_lms_discord_course_complete_message = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_course_complete_message' ) ) );


$embed_messaging_feature = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_embed_messaging_feature' ) ) );
$retry_failed_api        = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_retry_failed_api' ) ) );
$kick_upon_disconnect    = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_kick_upon_disconnect' ) ) );
$retry_api_count         = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_retry_api_count' ) ) );
$set_job_cnrc            = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_job_queue_concurrency' ) ) );
$set_job_q_batch_size    = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_job_queue_batch_size' ) ) );
$log_api_res             = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_log_api_response' ) ) );

?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="tutor_lms_discord_advance_settings">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_tutor_lms_discord_get_current_screen_url() ); ?>">   
<?php wp_nonce_field( 'save_tutor_lms_discord_general_advance_settings', 'ets_tutor_lms_discord_save_advance_settings' ); ?>
  <table class="form-table" role="presentation">
	<tbody>
	<tr>
		<th scope="row"><?php esc_html_e( 'Shortcode:', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		[tutor_lms_discord]
		<br/>
		<small><?php esc_html_e( 'Use this shortcode [tutor_lms_discord] to display connect to discord button on any page.', 'connect-discord-tutor-lms' ); ?></small>
		</fieldset></td>
	</tr> 
	<tr>
		<th scope="row"><?php esc_html_e( 'Use rich embed messaging feature?', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		<input name="embed_messaging_feature" type="checkbox" id="embed_messaging_feature" 
		<?php
		if ( $embed_messaging_feature == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
				<br/>
				<small>Use [LINEBREAK] to split lines.</small>                
		</fieldset></td>
	  </tr> 	           
	<tr>
		<th scope="row"><?php esc_html_e( 'Send welcome message', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		<input name="ets_tutor_lms_discord_send_welcome_dm" type="checkbox" id="ets_tutor_lms_discord_send_welcome_dm" 
		<?php
		if ( $ets_tutor_lms_discord_send_welcome_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Welcome message', 'connect-discord-tutor-lms' ); ?></th>
		<td> 
			<fieldset>
				<?php $ets_tutor_lms_discord_welcome_message_value = isset( $ets_tutor_lms_discord_welcome_message ) ? wp_unslash( $ets_tutor_lms_discord_welcome_message ) : ''; ?>
		<textarea class="ets_tutor_lms_discord_dm_textarea" name="ets_tutor_lms_discord_welcome_message" id="ets_tutor_lms_discord_welcome_message" row="25" cols="50"><?php echo esc_textarea( $ets_tutor_lms_discord_welcome_message_value ); ?></textarea> 
	<br/>
	<small>Merge fields: [TUTOR_LMS_STUDENT_NAME], [TUTOR_LMS_STUDENT_EMAIL], [TUTOR_LMS_COURSES], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>  
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Course Complete message', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		<input name="ets_tutor_lms_discord_send_course_complete_dm" type="checkbox" id="ets_tutor_lms_discord_send_course_complete_dm" 
		<?php
		if ( $ets_tutor_lms_discord_send_course_complete_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Course Complete message', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
			<?php $ets_tutor_lms_discord_course_complete_message_value = isset( $ets_tutor_lms_discord_course_complete_message ) ? wp_unslash( $ets_tutor_lms_discord_course_complete_message ) : ''; ?>
		<textarea class="ets_tutor_lms_discord_course_complete_message" name="ets_tutor_lms_discord_course_complete_message" id="ets_tutor_lms_discord_course_complete_message" row="25" cols="50"><?php echo esc_textarea( $ets_tutor_lms_discord_course_complete_message_value ); ?></textarea> 
	<br/>
	<small>Merge fields: [TUTOR_LMS_STUDENT_NAME], [TUTOR_LMS_STUDENT_EMAIL], [TUTOR_LMS_COURSE_NAME], [TUTOR_LMS_COURSE_DATE], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	  </tr>	       
  <tr>
  <tr>	
	  <tr>
		<th scope="row"><?php esc_html_e( 'Retry Failed API calls', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		<input name="retry_failed_api" type="checkbox" id="retry_failed_api" 
		<?php
		if ( $retry_failed_api == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Don\'t kick students upon disconnect', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		<input name="kick_upon_disconnect" type="checkbox" id="kick_upon_disconnect" 
		<?php
		if ( $kick_upon_disconnect == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'How many times a failed API call should get re-try', 'connect-discord-tutor-lms' ); ?></th>
		<td> 
			<fieldset>
				<?php $retry_api_count_value = isset( $retry_api_count ) ? intval( $retry_api_count ) : 1; ?>
		<input name="ets_tutor_lms_retry_api_count" type="number" min="1" id="ets_tutor_lms_retry_api_count" value="<?php echo esc_attr( $retry_api_count_value ); ?>">
		</fieldset>
	</td>
	  </tr> 
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue concurrency', 'connect-discord-tutor-lms' ); ?></th>
		<td> 
			<fieldset>
				<?php $set_job_cnrc_value = isset( $set_job_cnrc ) ? intval( $set_job_cnrc ) : 1; ?>
		<input name="set_job_cnrc" type="number" min="1" id="set_job_cnrc" value="<?php echo esc_attr( $set_job_cnrc_value ); ?>">
		</fieldset>
	</td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue batch size', 'connect-discord-tutor-lms' ); ?></th>
		<td> 
			<fieldset>
				<?php $set_job_q_batch_size_value = isset( $set_job_q_batch_size ) ? intval( $set_job_q_batch_size ) : 10; ?>
		<input name="set_job_q_batch_size" type="number" min="1" id="set_job_q_batch_size" value="<?php echo esc_attr( $set_job_q_batch_size_value ); ?>">
		</fieldset>
	</td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Log API calls response (For debugging purpose)', 'connect-discord-tutor-lms' ); ?></th>
		<td> <fieldset>
		<input name="log_api_res" type="checkbox" id="log_api_res" 
		<?php
		if ( $log_api_res == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="adv_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-discord-tutor-lms' ); ?>
	</button>
  </div>
</form>
