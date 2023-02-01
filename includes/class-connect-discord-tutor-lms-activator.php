<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/includes
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Discord_Tutor_Lms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'ets_tutor_lms_discord_uuid_file_name', wp_generate_uuid4() );
		update_option( 'ets_tutor_lms_discord_send_welcome_dm', true );
		update_option( 'ets_tutor_lms_discord_welcome_message', 'Hi [TUTOR_LMS_STUDENT_NAME] ([TUTOR_LMS_STUDENT_EMAIL]), Welcome, Your courses [TUTOR_LMS_COURSES] at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_tutor_lms_discord_send_course_complete_dm', true );
		update_option( 'ets_tutor_lms_discord_course_complete_message', 'Hi [TUTOR_LMS_STUDENT_NAME] ([TUTOR_LMS_STUDENT_EMAIL]), You have completed the course  [TUTOR_LMS_COURSE_NAME] at [TUTOR_LMS_COURSE_DATE] on website [SITE_URL], [BLOG_NAME]' );
		update_option( 'ets_tutor_lms_discord_retry_failed_api', true );
		update_option( 'ets_tutor_lms_discord_kick_upon_disconnect', false );
		update_option( 'ets_tutor_lms_discord_retry_api_count', 5 );
		update_option( 'ets_tutor_lms_discord_job_queue_concurrency', 1 );
		update_option( 'ets_tutor_lms_discord_job_queue_batch_size', 6 );
		update_option( 'ets_tutor_lms_discord_log_api_response', false );

		update_option( 'ets_tutor_lms_discord_embed_messaging_feature', false );

	}

}
