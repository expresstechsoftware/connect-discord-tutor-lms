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
