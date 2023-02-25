<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/public
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Discord_Tutor_Lms_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Discord_Tutor_Lms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Discord_Tutor_Lms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/connect-discord-tutor-lms-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Discord_Tutor_Lms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Discord_Tutor_Lms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-discord-tutor-lms-public.js', array( 'jquery' ), $this->version, false );
		$script_params = array(
			'admin_ajax'                  => admin_url( 'admin-ajax.php' ),
			'permissions_const'           => CONNECT_DISCORD_TUTOR_LMS_OAUTH_SCOPES,
			'ets_tutor_lms_discord_nonce' => wp_create_nonce( 'ets-tutor-lms-discord-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etsTutorLms', $script_params );

	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ets_tutor_lms_add_discord_button() {

		$user_id = sanitize_text_field( trim( get_current_user_id() ) );

		$access_token                                     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_access_token', true ) ) );
		$_ets_tutor_lms_discord_user_id                   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
		$_ets_tutor_lms_discord_username                  = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_username', true ) ) );
		$discord_user_avatar                              = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_avatar', true ) ) );
		$allow_none_student                               = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_allow_none_student' ) ) );
		$ets_tutor_lms_discord_connect_button_bg_color    = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_connect_button_bg_color' ) ) );
		$ets_tutor_lms_discord_disconnect_button_bg_color = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_disconnect_button_bg_color' ) ) );
		$ets_tutor_lms_discord_disconnect_button_text     = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_disconnect_button_text' ) ) );
		$ets_tutor_lms_discord_loggedin_button_text       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_loggedin_button_text' ) ) );
		$default_role                                     = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_default_role_id' ) ) );
		$ets_tutor_lms_discord_role_mapping               = json_decode( get_option( 'ets_tutor_lms_discord_role_mapping' ), true );
		$all_roles                                        = unserialize( get_option( 'ets_tutor_lms_discord_all_roles' ) );
		$roles_color                                      = unserialize( get_option( 'ets_tutor_lms_discord_roles_color' ) );
		$enrolled_courses                                 = ets_tutor_lms_discord_get_student_courses_ids( $user_id );
		$mapped_role_name                                 = '';
		if ( is_array( $enrolled_courses ) && is_array( $all_roles ) && is_array( $ets_tutor_lms_discord_role_mapping ) ) {
			foreach ( $enrolled_courses as $key => $enrolled_course_id ) {
				if ( array_key_exists( 'course_id_' . $enrolled_course_id, $ets_tutor_lms_discord_role_mapping ) ) {

					$mapped_role_id = $ets_tutor_lms_discord_role_mapping[ 'course_id_' . $enrolled_course_id ];

					if ( array_key_exists( $mapped_role_id, $all_roles ) ) {
						$mapped_role_name .= '<span> <i style="background-color:#' . dechex( $roles_color[ $mapped_role_id ] ) . '"></i>' . $all_roles[ $mapped_role_id ] . '</span>';
					}
				}
			}
		}

		$default_role_name = '';
		if ( is_array( $all_roles ) ) {
			if ( $default_role != 'none' && array_key_exists( $default_role, $all_roles ) ) {
				$default_role_name = '<span><i style="background-color:#' . dechex( $roles_color[ $default_role ] ) . '"></i> ' . $all_roles[ $default_role ] . '</span>';
			}
		}

		$restrictcontent_discord = '';

		if ( ets_tutor_lms_discord_check_saved_settings_status() ) {

			if ( $access_token ) {
				$ets_tutor_lms_discord_connect_button_bg_color = 'style="background-color:' . $ets_tutor_lms_discord_disconnect_button_bg_color . '"';
				$restrictcontent_discord                      .= '<div class="ets-tutor-lms-discord-button-wrapper">';
				$restrictcontent_discord                      .= '<div>';
				$restrictcontent_discord                      .= '<label class="ets-connection-lbl">' . esc_html__( 'Discord connection', 'connect-discord-tutor-lms' ) . '</label>';
				$restrictcontent_discord                      .= '</div>';
				$restrictcontent_discord                      .= '<div>';
				$restrictcontent_discord                      .= '<a href="#" class="ets-btn tutor-lms-discord-btn-disconnect" ' . $ets_tutor_lms_discord_connect_button_bg_color . ' id="tutor-lms-discord-disconnect-discord" data-user-id="' . esc_attr( $user_id ) . '">' . esc_html( $ets_tutor_lms_discord_disconnect_button_text ) . Connect_Discord_Tutor_Lms::get_discord_logo_white() . '</a>';
				$restrictcontent_discord                      .= '<span class="ets-spinner"></span>';
				$restrictcontent_discord                      .= '<p>' . esc_html__( sprintf( 'Connected account: %s', $_ets_tutor_lms_discord_username ), 'connect-discord-tutor-lms' ) . '</p>';
				$restrictcontent_discord                       = ets_tutor_lms_discord_get_user_avatar( $_ets_tutor_lms_discord_user_id, $discord_user_avatar, $restrictcontent_discord );
				$restrictcontent_discord                       = ets_tutor_lms_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );
				$restrictcontent_discord                      .= '</div>';
				$restrictcontent_discord                      .= '</div>';

			} elseif ( ( ets_tutor_lms_discord_get_student_courses_ids( $user_id ) && $mapped_role_name )
								|| ( ets_tutor_lms_discord_get_student_courses_ids( $user_id ) && ! $mapped_role_name && $default_role_name )
								|| ( $allow_none_student == 'yes' ) ) {

				$connect_btn_bg_color     = 'style="background-color:' . $ets_tutor_lms_discord_connect_button_bg_color . '"';
				$restrictcontent_discord .= '<div class="ets-tutor-lms-discord-button-wrapper">';
				$restrictcontent_discord .= '<h3>' . esc_html__( 'Discord connection', 'connect-discord-tutor-lms' ) . '</h3>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<a href="?action=tutor-lms-discord-login" class="tutor-lms-discord-btn-connect ets-btn" ' . $connect_btn_bg_color . ' >' . esc_html( $ets_tutor_lms_discord_loggedin_button_text ) . Connect_Discord_Tutor_Lms::get_discord_logo_white() . '</a>';
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord  = ets_tutor_lms_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );

				$restrictcontent_discord .= '</div>';

			}
		}
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		return wp_kses( $restrictcontent_discord, ets_tutor_lms_discord_allowed_html() );

	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function ets_tutor_lms_display_discord_button() {

		echo do_shortcode( '[tutor_lms_discord]' );
	}

	/**
	 * Allow data protocol.
	 *
	 * @param array $protocols
	 * @since    1.0.0
	 * @return array
	 */
	public function ets_tutor_lms_discord_allow_data_protocol( $protocols ) {

		$protocols[] = 'data';
		return $protocols;
	}

	/**
	 * For authorization process call discord API
	 *
	 * @param NONE
	 * @return OBJECT REST API response
	 */
	public function ets_tutor_lms_discord_api_callback() {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'tutor-lms-discord-login' ) {
				$params                    = array(
					'client_id'     => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_id' ) ) ),
					'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_redirect_url' ) ) ),
					'response_type' => 'code',
					'scope'         => 'identify email connections guilds guilds.join',
				);
				$discord_authorise_api_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'oauth2/authorize?' . http_build_query( $params );

				wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
				exit;
			}

			if ( isset( $_GET['code'] ) && isset( $_GET['via'] ) && $_GET['via'] == 'connect-tutor-lms-discord-addon' ) {
				$code     = sanitize_text_field( trim( $_GET['code'] ) );
				$response = $this->create_discord_auth_token( $code, $user_id );

				if ( ! empty( $response ) && ! is_wp_error( $response ) ) {
					$res_body              = json_decode( wp_remote_retrieve_body( $response ), true );
					$discord_exist_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
					if ( is_array( $res_body ) ) {

						if ( array_key_exists( 'access_token', $res_body ) ) {

							$access_token = sanitize_text_field( trim( $res_body['access_token'] ) );
							update_user_meta( $user_id, '_ets_tutor_lms_discord_access_token', $access_token );
							if ( array_key_exists( 'refresh_token', $res_body ) ) {
								$refresh_token = sanitize_text_field( trim( $res_body['refresh_token'] ) );
								update_user_meta( $user_id, '_ets_tutor_lms_discord_refresh_token', $refresh_token );
							}
							if ( array_key_exists( 'expires_in', $res_body ) ) {
								$expires_in = $res_body['expires_in'];
								$date       = new DateTime();
								$date->add( DateInterval::createFromDateString( '' . $expires_in . ' seconds' ) );
								$token_expiry_time = $date->getTimestamp();
								update_user_meta( $user_id, '_ets_tutor_lms_discord_expires_in', $token_expiry_time );
							}
							$user_body = $this->get_discord_current_user( $access_token );

							if ( is_array( $user_body ) && array_key_exists( 'discriminator', $user_body ) ) {
								$discord_user_number           = $user_body['discriminator'];
								$discord_user_name             = $user_body['username'];
								$discord_user_name_with_number = $discord_user_name . '#' . $discord_user_number;
								$discord_user_avatar           = $user_body['avatar'];
								update_user_meta( $user_id, '_ets_tutor_lms_discord_username', $discord_user_name_with_number );
								update_user_meta( $user_id, '_ets_tutor_lms_discord_avatar', $discord_user_avatar );
							}
							if ( is_array( $user_body ) && array_key_exists( 'id', $user_body ) ) {
								$_ets_tutor_lms_discord_user_id = sanitize_text_field( trim( $user_body['id'] ) );
								if ( $discord_exist_user_id === $_ets_tutor_lms_discord_user_id ) {
									/**
									 * Check if user still or not has enrolled courses
									 * to update : assgin or delete roles
									 */
										// $this->delete_discord_role( $user_id, $_ets_tutor_lms_discord_role_id );

								}
								update_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', $_ets_tutor_lms_discord_user_id );
								$this->add_discord_member_in_guild( $_ets_tutor_lms_discord_user_id, $user_id, $access_token );
							}
						} else {

						}
					} else {

					}
				}
			}
		}
	}

	/**
	 * Create authentication token for discord API
	 *
	 * @param STRING $code
	 * @param INT    $user_id
	 * @return OBJECT API response
	 */
	public function create_discord_auth_token( $code, $user_id ) {
		if ( ! is_user_logged_in() ) {

			wp_send_json_error( 'Unauthorized user', 401 );
			exit();

		}

		$response              = '';
		$refresh_token         = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_refresh_token', true ) ) );
		$token_expiry_time     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_expires_in', true ) ) );
		$discord_token_api_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'oauth2/token';
		if ( $refresh_token ) {
			$date              = new DateTime();
			$current_timestamp = $date->getTimestamp();
			if ( $current_timestamp > $token_expiry_time ) {
				$args     = array(
					'method'  => 'POST',
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
					),
					'body'    => array(
						'client_id'     => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_id' ) ) ),
						'client_secret' => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_secret' ) ) ),
						'grant_type'    => 'refresh_token',
						'refresh_token' => $refresh_token,
						'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_redirect_url' ) ) ),
						'scope'         => CONNECT_DISCORD_TUTOR_LMS_OAUTH_SCOPES,
					),
				);
				$response = wp_remote_post( $discord_token_api_url, $args );
				ets_tutor_lms_discord_log_api_response( $user_id, $discord_token_api_url, $args, $response );
				// if ( ets_tutor_lms_discord_check_api_errors( $response ) ) {
				// $response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				// Connect_Tutor_Lms_Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				// }
			}
		} else {
			$args     = array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded',
				),
				'body'    => array(
					'client_id'     => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_id' ) ) ),
					'client_secret' => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_secret' ) ) ),
					'grant_type'    => 'authorization_code',
					'code'          => $code,
					'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_redirect_url' ) ) ),
					'scope'         => CONNECT_DISCORD_TUTOR_LMS_OAUTH_SCOPES,
				),
			);
			$response = wp_remote_post( $discord_token_api_url, $args );
			ets_tutor_lms_discord_log_api_response( $user_id, $discord_token_api_url, $args, $response );
			// if ( ets_tutor_lms_discord_check_api_errors( $response ) ) {
			// $response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
			// Connect_Tutor_Lms_Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			// }
		}
		return $response;
	}

	/**
	 * Get Discord user details from API
	 *
	 * @param STRING $access_token
	 * @return OBJECT REST API response
	 */
	public function get_discord_current_user( $access_token ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$user_id = get_current_user_id();

		$discord_cuser_api_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'users/@me';
		$param                 = array(
			'headers' => array(
				'Content-Type'  => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer ' . $access_token,
			),
		);
		$user_response         = wp_remote_get( $discord_cuser_api_url, $param );
		ets_tutor_lms_discord_log_api_response( $user_id, $discord_cuser_api_url, $param, $user_response );

		$response_arr = json_decode( wp_remote_retrieve_body( $user_response ), true );
		// Connect_Tutor_Lms_Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
		$user_body = json_decode( wp_remote_retrieve_body( $user_response ), true );
		return $user_body;

	}

	/**
	 * Add new member into discord guild
	 *
	 * @param INT    $_ets_tutor_lms_discord_user_id
	 * @param INT    $user_id
	 * @param STRING $access_token
	 * @return NONE
	 */
	public function add_discord_member_in_guild( $_ets_tutor_lms_discord_user_id, $user_id, $access_token ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$enrolled_courses = map_deep( ets_tutor_lms_discord_get_student_courses_ids( $user_id ), 'sanitize_text_field' );
		if ( $enrolled_courses !== null ) {
			// It is possible that we may exhaust API rate limit while adding members to guild, so handling off the job to queue.
			as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), 'ets_tutor_lms_discord_as_handle_add_member_to_guild', array( $_ets_tutor_lms_discord_user_id, $user_id, $access_token ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
		}
	}

	/**
	 * Method to add new members to discord guild.
	 *
	 * @param INT    $_ets_tutor_lms_discord_user_id
	 * @param INT    $user_id
	 * @param STRING $access_token
	 * @return NONE
	 */
	public function ets_tutor_lms_discord_as_handler_add_member_to_guild( $_ets_tutor_lms_discord_user_id, $user_id, $access_token ) {
		// Since we using a queue to delay the API call, there may be a condition when a member is delete from DB. so put a check.
		if ( get_userdata( $user_id ) === false ) {
			return;
		}
		$guild_id                           = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
		$discord_bot_token                  = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
		$default_role                       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_default_role_id' ) ) );
		$ets_tutor_lms_discord_role_mapping = json_decode( get_option( 'ets_tutor_lms_discord_role_mapping' ), true );
		$discord_role                       = '';
		$discord_roles                      = array();
		$enrolled_courses                   = map_deep( ets_tutor_lms_discord_get_student_courses_ids( $user_id ), 'sanitize_text_field' );

		$ets_tutor_lms_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_send_welcome_dm' ) ) );
		if ( is_array( $enrolled_courses ) ) {
			foreach ( $enrolled_courses as $key => $enrolled_course_id ) {

				if ( is_array( $ets_tutor_lms_discord_role_mapping ) && array_key_exists( 'course_id_' . $enrolled_course_id, $ets_tutor_lms_discord_role_mapping ) ) {
					$discord_role = sanitize_text_field( trim( $ets_tutor_lms_discord_role_mapping[ 'course_id_' . $enrolled_course_id ] ) );
					array_push( $discord_roles, $discord_role );
					update_user_meta( $user_id, '_ets_tutor_lms_discord_role_id_for_' . $enrolled_course_id, $discord_role );
				}
			}
		}

		$guilds_memeber_api_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_tutor_lms_discord_user_id;
		$guild_args             = array(
			'method'  => 'PUT',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => json_encode(
				array(
					'access_token' => $access_token,
				)
			),
		);
		$guild_response         = wp_remote_post( $guilds_memeber_api_url, $guild_args );

		ets_tutor_lms_discord_log_api_response( $user_id, $guilds_memeber_api_url, $guild_args, $guild_response );
		if ( ets_tutor_lms_discord_check_api_errors( $guild_response ) ) {

			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );
			// Connect_Tutor_Lms__Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			// this should be catch by Action schedule failed action.
			throw new Exception( 'Failed in function ets_tutor_lms_discord_as_handler_add_member_to_guild' );
		}

		foreach ( $discord_roles as $discord_role ) {

			if ( $discord_role && $discord_role != 'none' && isset( $user_id ) ) {
				$this->put_discord_role_api( $user_id, $discord_role );

			}
		}

		if ( $default_role && $default_role != 'none' && isset( $user_id ) ) {
			update_user_meta( $user_id, '_ets_tutor_lms_discord_last_default_role', $default_role );
			$this->put_discord_role_api( $user_id, $default_role );
		}
		if ( empty( get_user_meta( $user_id, '_ets_tutor_lms_discord_join_date', true ) ) ) {
			update_user_meta( $user_id, '_ets_tutor_lms_discord_join_date', current_time( 'Y-m-d H:i:s' ) );
		}

		// Send welcome message.
		if ( $ets_tutor_lms_discord_send_welcome_dm == true ) {
			as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), 'ets_tutor_lms_discord_as_send_dm', array( $user_id, $enrolled_courses, 'welcome' ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
		}
	}

	/**
	 * API call to change discord user role
	 *
	 * @param INT  $user_id
	 * @param INT  $role_id
	 * @param BOOL $is_schedule
	 * @return object API response
	 */
	public function put_discord_role_api( $user_id, $role_id, $is_schedule = true ) {
		if ( $is_schedule ) {
			as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), 'ets_tutor_lms_discord_as_schedule_member_put_role', array( $user_id, $role_id, $is_schedule ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
		} else {
			$this->ets_tutor_lms_discord_as_handler_put_member_role( $user_id, $role_id, $is_schedule );
		}
	}

	/**
	 * Action Schedule handler for mmeber change role discord.
	 *
	 * @param INT  $user_id
	 * @param INT  $role_id
	 * @param BOOL $is_schedule
	 * @return object API response
	 */
	public function ets_tutor_lms_discord_as_handler_put_member_role( $user_id, $role_id, $is_schedule ) {
		$access_token                   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_access_token', true ) ) );
		$guild_id                       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
		$_ets_tutor_lms_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
		$discord_bot_token              = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
		$discord_change_role_api_url    = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_tutor_lms_discord_user_id . '/roles/' . $role_id;

		if ( $access_token && $_ets_tutor_lms_discord_user_id ) {
			$param = array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'   => 'application/json',
					'Authorization'  => 'Bot ' . $discord_bot_token,
					'Content-Length' => 0,
				),
			);

			$response = wp_remote_get( $discord_change_role_api_url, $param );

			ets_tutor_lms_discord_log_api_response( $user_id, $discord_change_role_api_url, $param, $response );
			if ( ets_tutor_lms_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				// Connect_Tutor_Lms__Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( $is_schedule ) {
					// this exception should be catch by action scheduler.
					throw new Exception( 'Failed in function ets_tutor_lms_discord_as_handler_put_member_role' );
				}
			}
		}
	}

	/**
	 * Discord DM a member using bot.
	 *
	 * @param INT    $user_id Student's id.
	 * @param MIXED  $course
	 * @param STRING $type (warning|expired).
	 * @param INT    $related (quiz_attempt|Realted achievment post)
	 */
	public function ets_tutor_lms_discord_discord_handler_send_dm( $user_id, $courses, $type = 'warning', $related = '' ) {
		$discord_user_id   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );

		$ets_tutor_lms_discord_welcome_message = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_welcome_message' ) ) );
		$embed_messaging_feature               = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_embed_messaging_feature' ) ) );

		// Check if DM channel is already created for the user.
		$user_dm = get_user_meta( $user_id, '_ets_tutor_lms_discord_dm_channel', true );

		if ( ! isset( $user_dm['id'] ) || $user_dm === false || empty( $user_dm ) ) {
			$this->ets_tutor_lms_discord_create_member_dm_channel( $user_id );
			$user_dm       = get_user_meta( $user_id, '_ets_tutor_lms_discord_dm_channel', true );
			$dm_channel_id = $user_dm['id'];
		} else {
			$dm_channel_id = $user_dm['id'];
		}

		if ( $type == 'welcome' ) {

			$message = ets_tutor_lms_discord_get_formatted_welcome_dm( $user_id, $courses, $ets_tutor_lms_discord_welcome_message );
		}

		if ( $type == 'encroll_course' ){
			$ets_tutor_lms_discord_course_enrolled_message = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_course_enrolled_message' ) ) );
			$message = ets_tutor_lms_discord_get_formatted_enrolled_dm( $user_id, $courses, $ets_tutor_lms_discord_course_enrolled_message );

		}

		$creat_dm_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . '/channels/' . $dm_channel_id . '/messages';

		/**
		 *
		 * Send rich embed message for $type == 'achievement_earned' ( support Badge  achievement)
		 */

		if ( $embed_messaging_feature ) {
			$dm_args = array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bot ' . $discord_bot_token,
				),
				'body'    => ets_tutor_lms_discord_get_rich_embed_message( trim( $message ) ),
			);
		} else {
			$dm_args = array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bot ' . $discord_bot_token,
				),
				'body'    => wp_json_encode(
					array(
						'content' => sanitize_text_field( trim( wp_unslash( $message ) ) ),
					)
				),
			);
		}

		$dm_response = wp_remote_post( $creat_dm_url, $dm_args );
		ets_tutor_lms_discord_log_api_response( $user_id, $creat_dm_url, $dm_args, $dm_response );
		$dm_response_body = json_decode( wp_remote_retrieve_body( $dm_response ), true );
		if ( ets_tutor_lms_discord_check_api_errors( $dm_response ) ) {
			// Tutro_Discord_Addon_Logs::write_api_response_logs( $dm_response_body, $user_id, debug_backtrace()[0] );
			// this should be catch by Action schedule failed action.
			throw new Exception( 'Failed in function ets_tutor_lms_discord_handler_send_dm' );
		}
	}

	/**
	 * Create DM channel for a give user_id
	 *
	 * @param INT $user_id
	 * @return MIXED
	 */
	public function ets_tutor_lms_discord_create_member_dm_channel( $user_id ) {
		$discord_user_id       = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
		$discord_bot_token     = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
		$create_channel_dm_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . '/users/@me/channels';
		$dm_channel_args       = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => json_encode(
				array(
					'recipient_id' => $discord_user_id,
				)
			),
		);

		$created_dm_response = wp_remote_post( $create_channel_dm_url, $dm_channel_args );
		ets_tutor_lms_discord_log_api_response( $user_id, $create_channel_dm_url, $dm_channel_args, $created_dm_response );
		$response_arr = json_decode( wp_remote_retrieve_body( $created_dm_response ), true );

		if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
			// check if there is error in create dm response
			if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
				// Tutor_Discord_Addon_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( ets_tutor_lms_discord_check_api_errors( $created_dm_response ) ) {
					// this should be catch by Action schedule failed action.
					throw new Exception( 'Failed in function ets_tutor_lms_discord_create_member_dm_channel' );
				}
			} else {
				update_user_meta( $user_id, '_ets_tutor_lms_discord_dm_channel', $response_arr );
			}
		}
		return $response_arr;
	}

	/**
	 *
	 */
	public function ets_tutor_lms_disconnect_from_discord() {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}

		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_tutor_lms_discord_nonce'], 'ets-tutor-lms-discord-ajax-nonce' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
		}
		$user_id              = sanitize_text_field( trim( $_POST['user_id'] ) );
		$kick_upon_disconnect = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_kick_upon_disconnect' ) ) );
		if ( $user_id ) {
			delete_user_meta( $user_id, '_ets_tutor_lms_discord_access_token' );
			delete_user_meta( $user_id, '_ets_tutor_lms_discord_refresh_token' );
			$user_roles = ets_tutor_lms_discord_get_user_roles( $user_id );
			if ( $kick_upon_disconnect ) {

				if ( is_array( $user_roles ) ) {
					foreach ( $user_roles as $user_role ) {
						$this->delete_discord_role( $user_id, $user_role );
					}
				}
			} else {
				$this->delete_member_from_guild( $user_id, false );
			}
		}
		$event_res = array(
			'status'  => 1,
			'message' => 'Successfully disconnected',
		);
		wp_send_json( $event_res );
		exit();
	}

	/**
	 * Schedule delete discord role for a student
	 *
	 * @param INT  $user_id
	 * @param INT  $ets_tutor_lms_discord_role_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function delete_discord_role( $user_id, $ets_tutor_lms_discord_role_id, $is_schedule = true ) {
		if ( $is_schedule ) {
			as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), 'ets_tutor_lms_discord_as_schedule_delete_role', array( $user_id, $ets_tutor_lms_discord_role_id, $is_schedule ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
		} else {
			$this->ets_tutor_lms_discord_as_handler_delete_memberrole( $user_id, $ets_tutor_lms_discord_role_id, $is_schedule );
		}
	}

	/**
	 * Action Schedule handler to process delete role of a student.
	 *
	 * @param INT  $user_id
	 * @param INT  $ets_tutor_lms_discord_role_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function ets_tutor_lms_discord_as_handler_delete_memberrole( $user_id, $ets_tutor_lms_discord_role_id, $is_schedule = true ) {

		$guild_id                       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
		$_ets_tutor_lms_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
		$discord_bot_token              = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
		$discord_delete_role_api_url    = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_tutor_lms_discord_user_id . '/roles/' . $ets_tutor_lms_discord_role_id;
		if ( $_ets_tutor_lms_discord_user_id ) {
			$param = array(
				'method'  => 'DELETE',
				'headers' => array(
					'Content-Type'   => 'application/json',
					'Authorization'  => 'Bot ' . $discord_bot_token,
					'Content-Length' => 0,
				),
			);

			$response = wp_remote_request( $discord_delete_role_api_url, $param );
			ets_tutor_lms_discord_log_api_response( $user_id, $discord_delete_role_api_url, $param, $response );
			if ( ets_tutor_lms_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				// Tutor_Discord_Addon_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( $is_schedule ) {
					// this exception should be catch by action scheduler.
					throw new Exception( 'Failed in function ets_tutor_lms_discord_as_handler_delete_memberrole' );
				}
			}
			return $response;
		}
	}

	/**
	 * Schedule delete existing user from guild
	 *
	 * @param INT  $user_id
	 * @param BOOL $is_schedule
	 * @param NONE
	 */
	public function delete_member_from_guild( $user_id, $is_schedule = true ) {
		if ( $is_schedule && isset( $user_id ) ) {

			as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), 'ets_tutor_lms_discord_as_schedule_delete_member', array( $user_id, $is_schedule ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
		} else {
			if ( isset( $user_id ) ) {
				$this->ets_tutor_lms_discord_as_handler_delete_member_from_guild( $user_id, $is_schedule );
			}
		}
	}

	/**
	 * AS Handling member delete from huild
	 *
	 * @param INT  $user_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function ets_tutor_lms_discord_as_handler_delete_member_from_guild( $user_id, $is_schedule ) {
		$guild_id                       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
		$discord_bot_token              = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );
		$_ets_tutor_lms_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_user_id', true ) ) );
		$guilds_delete_memeber_api_url  = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_tutor_lms_discord_user_id;
		$guild_args                     = array(
			'method'  => 'DELETE',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);
		$guild_response                 = wp_remote_post( $guilds_delete_memeber_api_url, $guild_args );

		ets_tutor_lms_discord_log_api_response( $user_id, $guilds_delete_memeber_api_url, $guild_args, $guild_response );
		if ( ets_tutor_lms_discord_check_api_errors( $guild_response ) ) {
			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );
			// Tutor_Discord_Addon_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			if ( $is_schedule ) {
				// this exception should be catch by action scheduler.
				throw new Exception( 'Failed in function ets_tutor_lms_discord_as_handler_delete_member_from_guild' );
			}
		}

		/*Delete all usermeta related to discord connection*/
		ets_tutor_lms_discord_remove_usermeta( $user_id );

	}

	/**
	 * Assign discod role when a student enrolls in a course.
	 *
	 * @param INT $course_id
	 * @param INT $user_id
	 * @param INT $isEnrolled
	 * @return void
	 */
	public function ets_tutor_lms_discord_enrolled_course( $course_id, $user_id, $isEnrolled ) {

		$access_token                       = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_access_token', true ) ) );
		$refresh_token                      = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_refresh_token', true ) ) );
		$ets_tutor_lms_discord_role_mapping = json_decode( get_option( 'ets_tutor_lms_discord_role_mapping' ), true );
		$default_role                       = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_default_role_id' ) ) );

		if ( is_array( $ets_tutor_lms_discord_role_mapping ) && array_key_exists( 'course_id_' . $course_id, $ets_tutor_lms_discord_role_mapping ) ) {
			$discord_role = sanitize_text_field( trim( $ets_tutor_lms_discord_role_mapping[ 'course_id_' . $course_id ] ) );
			if ( $discord_role && $discord_role != 'none' ) {
				if ( $access_token && $refresh_token ) {

					update_user_meta( $user_id, '_ets_tutor_lms_discord_role_id_for_' . $course_id, $discord_role );
					$this->put_discord_role_api( $user_id, $discord_role );
					// Sent a notification about the enrolled course
					$ets_tutor_lms_discord_send_course_enrolled_dm = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_send_course_enrolled_dm' ) ) );
					if ( $ets_tutor_lms_discord_send_course_enrolled_dm == true ){
						as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), 'ets_tutor_lms_discord_as_send_dm', array( $user_id, $course_id, 'encroll_course' ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
					}
				}
			}
		}
		if ( $access_token && $refresh_token ) {
			if ( $default_role && $default_role != 'none' && isset( $user_id ) ) {
				update_user_meta( $user_id, '_ets_tutor_lms_discord_last_default_role', $default_role );
				$this->put_discord_role_api( $user_id, $default_role );
			} else {
				$default_role = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_tutor_lms_discord_last_default_role', true ) ) );
				$this->delete_discord_role( $user_id, $default_role );
			}
		}
	}
}
