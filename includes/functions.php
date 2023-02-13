<?php
/**
 * Get current screen URL
 *
 * @param NONE
 * @return STRING $url
 */
function ets_tutor_lms_discord_get_current_screen_url() {
	$parts       = parse_url( home_url() );
	$current_uri = "{$parts['scheme']}://{$parts['host']}" . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) . add_query_arg( null, null );

		return $current_uri;
}

/**
 * Get WP pages list.
 *
 * @param INT $ets_tutor_lms_discord_redirect_page_id The Page ID.
 *
 * @return STRING $options Html select options.
 */
function ets_tutor_lms_discord_pages_list( $ets_tutor_lms_discord_redirect_page_id ) {
	$args    = array(
		'sort_order'   => 'asc',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
	);
	$pages   = get_pages( $args );
	$options = '<option value="">-</option>';
	foreach ( $pages as $page ) {
		$selected = ( esc_attr( $page->ID ) === $ets_tutor_lms_discord_redirect_page_id ) ? ' selected="selected"' : '';
		$options .= '<option data-page-url="' . ets_get_tutor_lms_discord_formated_discord_redirect_url( $page->ID ) . '" value="' . esc_attr( $page->ID ) . '" ' . $selected . '> ' . sanitize_text_field( $page->post_title ) . ' </option>';
	}

	return $options;
}

/**
 * Get formated redirect url.
 *
 * @param INT $page_id
 *
 * @return STRING $url
 */
function ets_get_tutor_lms_discord_formated_discord_redirect_url( $page_id ) {
	$url = esc_url( get_permalink( $page_id ) );

	$parsed = parse_url( $url, PHP_URL_QUERY );
	if ( $parsed === null ) {
		return $url .= '?via=connect-tutor-lms-discord-addon';
	} else {
		if ( stristr( $url, 'via=connect-tutor-lms-discord-addon' ) !== false ) {
			return $url;
		} else {
			return $url .= '&via=connect-tutor-lms-discord-addon';
		}
	}
}

/**
 * Save the BOT name in options table.
 */
function ets_tutor_lms_discord_update_bot_name_option() {

	$guild_id          = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
	$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
	if ( $guild_id && $discord_bot_token ) {

		$discod_current_user_api = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'users/@me';

		$app_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);

		$app_response = wp_remote_post( $discod_current_user_api, $app_args );

		$response_arr = json_decode( wp_remote_retrieve_body( $app_response ), true );

		if ( is_array( $response_arr ) && array_key_exists( 'username', $response_arr ) ) {

			update_option( 'ets_tutor_lms_discord_connected_bot_name', $response_arr ['username'] );
		} else {
			delete_option( 'ets_tutor_lms_discord_connected_bot_name' );
		}
	}

}

/**
 * To check settings values saved or not
 *
 * @param NONE
 * @return BOOL $status
 */
function ets_tutor_lms_discord_check_saved_settings_status() {
	$ets_tutor_lms_discord_client_id     = get_option( 'ets_tutor_lms_discord_client_id' );
	$ets_tutor_lms_discord_client_secret = get_option( 'ets_tutor_lms_discord_client_secret' );
	$ets_tutor_lms_discord_bot_token     = get_option( 'ets_tutor_lms_discord_bot_token' );
	$ets_tutor_lms_discord_redirect_url  = get_option( 'ets_tutor_lms_discord_redirect_url' );
	$ets_tutor_lms_discord_server_id     = get_option( 'ets_tutor_lms_discord_server_id' );

	if ( $ets_tutor_lms_discord_client_id && $ets_tutor_lms_discord_client_secret && $ets_tutor_lms_discord_bot_token && $ets_tutor_lms_discord_redirect_url && $ets_tutor_lms_discord_server_id ) {
			$status = true;
	} else {
			 $status = false;
	}

		 return $status;
}

/**
 * Add API error logs into log file
 *
 * @param ARRAY  $response_arr
 * @param INT    $user_id
 * @param ARRAY  $backtrace_arr
 * @param string $error_type
 *
 * @return None
 */
function ets_tutor_lms_write_api_response_logs( $response_arr, $user_id, $backtrace_arr = array() ) {
	$error        = current_time( 'mysql' );
	$user_details = '';
	if ( $user_id ) {
		$user_details = '::User Id:' . $user_id;
	}
	$log_api_response = get_option( 'ets_tutor_lms_discord_log_api_response' );
	$uuid             = get_option( 'ets_tutor_lms_discord_uuid_file_name' );
	$log_file_name    = $uuid . Connect_Discord_Tutor_Lms_Admin::$log_file_name;

	if ( is_array( $response_arr ) && array_key_exists( 'code', $response_arr ) ) {
		$error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['code'] . ':' . $response_arr['message'];
		file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
	} elseif ( is_array( $response_arr ) && array_key_exists( 'error', $response_arr ) ) {
		$error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['error'];
		file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
	} elseif ( $log_api_response == true ) {
		$error .= json_encode( $response_arr ) . '::' . $user_id;
		file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
	}

}

/**
 * Undocumented function
 *
 * @param INT $user_id
 * @return void
 */
function ets_tutor_lms_discord_get_student_courses_id( $user_id ) {

}

/**
 * Undocumented function
 *
 * @param [type] $mapped_role_name
 * @param [type] $default_role_name
 * @param [type] $restrictcontent_discord
 * @return void
 */
function ets_tutor_lms_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord ) {

	if ( $mapped_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Roles will be assigned to you in Discord: ', 'connect-discord-tutor-lms' );
		$restrictcontent_discord .= $mapped_role_name;
		if ( $default_role_name ) {
			$restrictcontent_discord .= $default_role_name;

		}

		$restrictcontent_discord .= '</p>';
	} elseif ( $default_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Role will be assigned to you in Discord: ', 'connect-discord-tutor-lms' );
		$restrictcontent_discord .= $default_role_name;

		$restrictcontent_discord .= '</p>';

	}
	return $restrictcontent_discord;
}

/**
 * Get allowed html using WordPress API function wp_kses
 *
 * @param STRING $html_message
 * @return STRING $html_message
 */

function ets_tutor_lms_discord_allowed_html() {
	$allowed_html = array(
		'div'    => array(
			'class' => array(),
		),
		'p'      => array(
			'class' => array(),
		),
		'a'      => array(
			'id'           => array(),
			'data-user-id' => array(),
			'href'         => array(),
			'class'        => array(),
			'style'        => array(),
		),
		'label'  => array(
			'class' => array(),
		),
		'h3'     => array(),
		'span'   => array(
			'class' => array(),
		),
		'i'      => array(
			'style' => array(),
			'class' => array(),
		),
		'button' => array(
			'class'        => array(),
			'data-user-id' => array(),
			'id'           => array(),
		),
		'img'    => array(
			'src'   => array(),
			'class' => array(),
		),
	);

	return $allowed_html;
}
