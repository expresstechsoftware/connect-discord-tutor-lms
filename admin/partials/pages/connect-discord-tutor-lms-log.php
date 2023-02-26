<div class="error-log">
<?php
	$uuid     = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_uuid_file_name' ) ) );
	$filename = $uuid . Connect_Discord_Tutor_Lms_Logs::$log_file_name;
	$handle   = fopen( WP_CONTENT_DIR . '/' . $filename, 'a+' );
if ( $handle ) {
	while ( ! feof( $handle ) ) {
		echo esc_html( fgets( $handle ) ) . '<br />';
	}
	fclose( $handle );
}
?>
</div>
<div class="tutor-lms-clrbtndiv">
	<div class="form-group">
		<input type="button" class="ets-tutor-lms-clrbtn ets-submit ets-bg-red" id="ets-tutor-lms-clrbtn" name="tutor_lms_clrbtn" value="Clear Logs !">
		<span class="clr-log spinner" ></span>
	</div>
	<div class="form-group">
		<input type="button" class="ets-submit ets-bg-green" value="Refresh" onClick="window.location.reload()">
	</div>
	<div class="form-group">
		<a href="<?php echo esc_url( content_url( '/' ) . $filename ); ?>" class="ets-submit ets-tutor-lms-bg-download" download><?php esc_html_e( 'Download', 'connect-discord-tutor-lms' ); ?></a>
	</div>
	<div class="form-group">
			<a href="<?php echo esc_url( get_admin_url( '', 'tools.php' ) ) . '?page=action-scheduler&status=pending&s=tutor_lms'; ?>" class="ets-submit ets-tutor-lms-bg-scheduled-actions"><?php esc_html_e( 'Scheduled Actions', 'connect-discord-tutor-lms' ); ?></a>
	</div>    
</div>
