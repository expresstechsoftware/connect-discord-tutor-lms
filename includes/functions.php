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
 * Get student's courses.
 *
 * @param INT $user_id
 * @return ARRAY|NULL
 */
function ets_tutor_lms_discord_get_student_courses_ids( $user_id ) {
	$enrolled_courses = tutor_utils()->get_enrolled_courses_ids_by_user( $user_id );
	if ( is_array( $enrolled_courses ) && count( $enrolled_courses ) > 0 ) {

		return $enrolled_courses;
	} else {
		return null;
	}
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

/**
 * Get Action data from table `actionscheduler_actions`.
 *
 * @param INT $action_id Action id.
 *
 * @return ARRAY|BOOL
 */
function ets_tutor_lms_discord_as_get_action_data( $action_id ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.hook, aa.status, aa.args, ag.slug AS as_group FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id=ag.group_id WHERE `action_id`=%d AND ag.slug=%s', $action_id, ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result[0];
	} else {
		return false;
	}
}

/**
 * Get how many times a hook is failed in a particular day.
 *
 * @param STRING $hook
 *
 * @return INT|BOOL
 */
function ets_tutor_lms_discord_count_of_hooks_failures( $hook ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT count(last_attempt_gmt) as hook_failed_count FROM ' . $wpdb->prefix . 'actionscheduler_actions WHERE `hook`=%s AND status="failed" AND DATE(last_attempt_gmt) = %s', $hook, date( 'Y-m-d' ) ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result['0']['hook_failed_count'];
	} else {
		return false;
	}
}

/**
 * Get randon integer between a predefined range.
 *
 * @param INT $add_upon
 *
 * @return INT
 */
function ets_tutor_lms_discord_get_random_timestamp( $add_upon = '' ) {
	if ( $add_upon != '' && $add_upon !== false ) {
		return $add_upon + random_int( 5, 15 );
	} else {
		return strtotime( 'now' ) + random_int( 5, 15 );
	}
}

/**
 * Get the highest available last attempt schedule time.
 *
 * @return INT|FALSE
 */
function ets_tutor_lms_discord_get_highest_last_attempt_timestamp() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.last_attempt_gmt FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s ORDER BY aa.last_attempt_gmt DESC limit 1', CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return strtotime( $result['0']['last_attempt_gmt'] );
	} else {
		return false;
	}
}

/**
 * Get pending jobs.
 *
 * @return ARRAY|FALSE
 */
function ets_tutor_lms_discord_get_all_pending_actions() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.* FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s AND aa.status="pending" ', CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result['0'];
	} else {
		return false;
	}
}

/**
 * Log API call response.
 *
 * @param INT          $user_id
 * @param STRING       $api_url
 * @param ARRAY        $api_args
 * @param ARRAY|OBJECT $api_response
 */
function ets_tutor_lms_discord_log_api_response( $user_id, $api_url = '', $api_args = array(), $api_response = '' ) {
	$log_api_response = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_log_api_response' ) ) );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );

		// $logs = new Connect_Tutor_Lms_Discord_Add_On_Logs();
		// $logs->write_api_response_logs( $log_string, $user_id );
	}
}

/**
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response The API resposne.
 * @param BOOLEAN
 */
function ets_tutor_lms_discord_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

	// First Check if response contain codes which should not get re-try.
	$body = json_decode( wp_remote_retrieve_body( $api_response ), true );
	if ( isset( $body['code'] ) && in_array( $body['code'], CONNECT_DISCORD_TUTOR_LMS_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	$response_code = strval( $api_response['response']['code'] );
	if ( isset( $api_response['response']['code'] ) && in_array( $response_code, CONNECT_DISCORD_TUTOR_LMS_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	// check if response code is in the range of HTTP error.
	if ( ( 400 <= absint( $response_code ) ) && ( absint( $response_code ) <= 599 ) ) {
		return true;
	}
}

/**
 * Return the discord user avatar.
 *
 * @param INT    $discord_user_id The discord usr ID.
 * @param STRING $user_avatar Discord avatar hash value.
 * @param STRING $restrictcontent_discord The html.
 *
 * @return STRING
 */
function ets_tutor_lms_discord_get_user_avatar( $discord_user_id, $user_avatar, $restrictcontent_discord ) {
	if ( $user_avatar ) {
		$avatar_url               = '<img class="ets-tutor-lms-user-avatar" src="https://cdn.discordapp.com/avatars/' . $discord_user_id . '/' . $user_avatar . '.png" />';
		$restrictcontent_discord .= $avatar_url;
	}
	return $restrictcontent_discord;
}

/**
 * Send DM message Rich Embed .
 *
 * @param string $message The message to send.
 */
function ets_tutor_lms_discord_get_rich_embed_message( $message ) {

	$blog_logo_full      = is_array( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) ) ? esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] ) : '';
	$blog_logo_thumbnail = is_array( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'thumbnail' ) ) ? esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'thumbnail' )[0] ) : '';

	$SITE_URL         = get_bloginfo( 'url' );
	$BLOG_NAME        = get_bloginfo( 'name' );
	$BLOG_DESCRIPTION = get_bloginfo( 'description' );

	$timestamp     = date( 'c', strtotime( 'now' ) );
	$convert_lines = preg_split( '/\[LINEBREAK\]/', $message );
	$fields        = array();
	if ( is_array( $convert_lines ) ) {
		for ( $i = 0; $i < count( $convert_lines ); $i++ ) {
			array_push(
				$fields,
				array(
					'name'   => '.',
					'value'  => $convert_lines[ $i ],
					'inline' => false,
				)
			);
		}
	}

	$rich_embed_message = json_encode(
		array(
			'content'    => '',
			'username'   => $BLOG_NAME,
			'avatar_url' => $blog_logo_thumbnail,
			'tts'        => false,
			'embeds'     => array(
				array(
					'title'       => '',
					'type'        => 'rich',
					'description' => $BLOG_DESCRIPTION,
					'url'         => '',
					'timestamp'   => $timestamp,
					'color'       => hexdec( '3366ff' ),
					'footer'      => array(
						'text'     => $BLOG_NAME,
						'icon_url' => $blog_logo_thumbnail,
					),
					'image'       => array(
						'url' => $blog_logo_full,
					),
					'thumbnail'   => array(
						'url' => $blog_logo_thumbnail,
					),
					'author'      => array(
						'name' => $BLOG_NAME,
						'url'  => $SITE_URL,
					),
					'fields'      => $fields,

				),
			),

		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	);

	return $rich_embed_message;
}

/**
 * Get formatted message to send in DM
 *
 * @param INT   $user_id
 * @param ARRAY $courses the student's list of sources
 * Merge fields: [TUTOR_LMS_STUDENT_NAME], [TUTOR_LMS_COURSES], [LLMS_STUDENT_NAME], [TUTOR_LMS_STUDENT_EMAIL], [SITE_URL], [BLOG_NAME]
 */
function ets_tutor_lms_discord_get_formatted_welcome_dm( $user_id, $courses, $message ) {

	$user_obj         = get_user_by( 'id', $user_id );
	$STUDENT_USERNAME = sanitize_text_field( $user_obj->user_login );
	$STUDENT_EMAIL    = sanitize_email( $user_obj->user_email );
	$SITE_URL         = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME        = sanitize_text_field( get_bloginfo( 'name' ) );

	$COURSES = '';
	if ( is_array( $courses ) ) {
		$args_courses = array(
			'orderby'     => 'title',
			'order'       => 'ASC',
			'numberposts' => count( $courses ),
			'post_type'   => 'courses',
			'post__in'    => $courses,
		);

		$enrolled_courses = get_posts( $args_courses );
		$lastKeyCourse    = array_key_last( $enrolled_courses );
		$commas           = ', ';
		foreach ( $enrolled_courses as $key => $course ) {
			if ( $lastKeyCourse === $key ) {
				$commas = ' ';
			}
			$COURSES .= esc_html( $course->post_title ) . $commas;
		}
	} else {
		$enrolled_course = get_post( $courses );
		$COURSES        .= ( ! empty( ( $enrolled_course->post_title ) ) ) ? esc_html( $enrolled_course->post_title ) : '';
	}

		$find    = array(
			'[TUTOR_LMS_COURSES]',
			'[TUTOR_LMS_STUDENT_NAME]',
			'[TUTOR_LMS_STUDENT_EMAIL]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
		$replace = array(
			$COURSES,
			$STUDENT_USERNAME,
			$STUDENT_EMAIL,
			$SITE_URL,
			$BLOG_NAME,
		);

		return str_replace( $find, $replace, $message );

}

/**
 * Get formatted message to send in DM
 *
 * @param INT   $user_id
 * @param ARRAY $courses the student's list of sources
 * Merge fields: [TUTOR_LMS_STUDENT_NAME], [TUTOR_LMS_COURSE_NAME], [SITE_URL], [BLOG_NAME]
 */
function ets_tutor_lms_discord_get_formatted_enrolled_dm( $user_id, $courses, $message ) {

	$user_obj         = get_user_by( 'id', $user_id );
	$STUDENT_USERNAME = sanitize_text_field( $user_obj->user_login );
	$SITE_URL         = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME        = sanitize_text_field( get_bloginfo( 'name' ) );

	$COURSES = '';
	if ( is_array( $courses ) ) {
		$args_courses = array(
			'orderby'     => 'title',
			'order'       => 'ASC',
			'numberposts' => count( $courses ),
			'post_type'   => 'courses',
			'post__in'    => $courses,
		);

		$enrolled_courses = get_posts( $args_courses );
		$lastKeyCourse    = array_key_last( $enrolled_courses );
		$commas           = ', ';
		foreach ( $enrolled_courses as $key => $course ) {
			if ( $lastKeyCourse === $key ) {
				$commas = ' ';
			}
			$COURSES .= esc_html( $course->post_title ) . $commas;
		}
	} else {
		$enrolled_course = get_post( $courses );
		$COURSES        .= ( ! empty( ( $enrolled_course->post_title ) ) ) ? esc_html( $enrolled_course->post_title ) : '';
	}

		$find    = array(
			'[TUTOR_LMS_COURSE_NAME]',
			'[TUTOR_LMS_STUDENT_NAME]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
		$replace = array(
			$COURSES,
			$STUDENT_USERNAME,
			$SITE_URL,
			$BLOG_NAME,
		);

		return str_replace( $find, $replace, $message );

}

/**
 * Get formatted message to send in lesson complete DM
 *
 * @param INT $user_id
 * @param INT $lesson_id The lesson id.
 * Merge fields:  [TUTOR_LMS_STUDENT_NAME], [TUTOR_LMS_LESSON_NAME], [TUTOR_LMS_LESSON_DATE], [SITE_URL], [BLOG_NAME]
 */
function ets_tutor_lms_discord_get_formatted_lesson_dm( $user_id, $lesson_id, $message ) {

	$user_obj         = get_user_by( 'id', $user_id );
	$STUDENT_USERNAME = sanitize_text_field( $user_obj->user_login );
	$SITE_URL         = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME        = sanitize_text_field( get_bloginfo( 'name' ) );
	$CURRENT_DATE = wp_date( sanitize_text_field( get_option('date_format' ) ) );

	$completed_lesson = get_post( $lesson_id );
	$LESSON = '';
	$LESSON          .= ( ! empty( ( $completed_lesson->post_title ) ) ) ? esc_html( $completed_lesson->post_title ) : '';

		$find    = array(
			'[TUTOR_LMS_LESSON_NAME]',
			'[TUTOR_LMS_STUDENT_NAME]',
			'[TUTOR_LMS_LESSON_DATE]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
		$replace = array(
			$LESSON,
			$STUDENT_USERNAME,
			$CURRENT_DATE,
			$SITE_URL,
			$BLOG_NAME,
		);

		return str_replace( $find, $replace, $message );

}

/**
 * Get student's roles ids
 *
 * @param INT $user_id
 * @return ARRAY|NULL $roles
 */
function ets_tutor_lms_discord_get_user_roles( $user_id ) {
	global $wpdb;

	$usermeta_table     = $wpdb->prefix . 'usermeta';
	$user_roles_sql     = 'SELECT * FROM ' . $usermeta_table . " WHERE `user_id` = %d AND ( `meta_key` like '_ets_tutor_lms_discord_role_id_for_%' OR `meta_key` = '_ets_tutor_lms_discord_last_default_role' OR `meta_key` = '_ets_tutor_lms_discord_last_default_role' ); ";
	$user_roles_prepare = $wpdb->prepare( $user_roles_sql, $user_id );

	$user_roles = $wpdb->get_results( $user_roles_prepare, ARRAY_A );

	if ( is_array( $user_roles ) && count( $user_roles ) ) {
		$roles = array();
		foreach ( $user_roles as  $role ) {

			array_push( $roles, $role['meta_value'] );
		}

				return $roles;

	} else {

		return null;
	}

}

/**
 * Remove user meta.
 *
 * @param INT $user_id
 */
function ets_tutor_lms_discord_remove_usermeta( $user_id ) {

	global $wpdb;

	$usermeta_table      = $wpdb->prefix . 'usermeta';
	$usermeta_sql        = 'DELETE FROM ' . $usermeta_table . " WHERE `user_id` = %d AND  `meta_key` LIKE '_ets_tutor_lms_discord%'; ";
	$delete_usermeta_sql = $wpdb->prepare( $usermeta_sql, $user_id );
	$wpdb->query( $delete_usermeta_sql );

}
