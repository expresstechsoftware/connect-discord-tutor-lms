<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/admin
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Discord_Tutor_Lms_Admin {

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
	 * Static property to define log file name
	 *
	 * @param None
	 * @return string $log_file_name
	 */
	public static $log_file_name = 'tutor_lms_discord_api_logs.txt';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		$min_css = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . 'discord_tabs_css', plugin_dir_url( __FILE__ ) . 'css/skeletabs.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/connect-discord-tutor-lms-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_register_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), $this->version, false );

		wp_register_script( $this->plugin_name . '-tabs-js', plugin_dir_url( __FILE__ ) . 'js/skeletabs.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-discord-tutor-lms-admin.js', array( 'jquery' ), $this->version, false );
		$script_params = array(
			'admin_ajax'                  => admin_url( 'admin-ajax.php' ),
			'permissions_const'           => CONNECT_DISCORD_TUTOR_LMS_OAUTH_SCOPES,
			'is_admin'                    => is_admin(),
			'ets_tutor_lms_discord_nonce' => wp_create_nonce( 'ets-tutor-lms-discord-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etsTutorLms', $script_params );

	}

	/**
	 * Method to add Discord Setting sub-menu under top level menu of Tutor LMS.
	 *
	 * @since 1.0.0
	 */
	public function ets_tutor_discord_add_discord_menu() {
		add_submenu_page( 'tutor', esc_html__( 'Discord Settings', 'connect-discord-tutor-lms' ), esc_html__( 'Discord Settings', 'connect-discord-tutor-lms' ), 'manage_tutor', 'connect-discord-tutor-lms', array( $this, 'ets_tutor_discord_settings_page' ) );

	}

	/**
	 * Callback to display discord settings page.
	 *
	 * @since 1.0.0
	 */
	public function ets_tutor_discord_settings_page() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights 1', 403 );
			exit();
		}

		wp_enqueue_style( $this->plugin_name . '-select2' );
		wp_enqueue_style( $this->plugin_name . 'discord_tabs_css' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name . '-select2' );
		wp_enqueue_script( $this->plugin_name . '-tabs-js' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( $this->plugin_name );

		require_once CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH . 'admin/partials/connect-discord-tutor-lms-admin-display.php';

	}
	/**
	 * Update redirect url
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_tutor_lms_discord_update_redirect_url() {

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_tutor_lms_discord_nonce'], 'ets-tutor-lms-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$page_id = sanitize_text_field( $_POST['ets_tutor_lms_page_id'] );
		if ( isset( $page_id ) ) {
			$formated_discord_redirect_url = ets_get_tutor_lms_discord_formated_discord_redirect_url( $page_id );
			update_option( 'ets_tutor_lms_discord_redirect_page_id', $page_id );
			update_option( 'ets_tutor_lms_discord_redirect_url', $formated_discord_redirect_url );
			$res = array(
				'formated_discord_redirect_url' => $formated_discord_redirect_url,
			);
			wp_send_json( $res );

		}
		exit();

	}

	/**
	 * Save application details
	 *
	 * @since    1.0.0
	 * @return NONE
	 */
	public function ets_tutor_lms_discord_application_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_tutor_lms_discord_client_id = isset( $_POST['ets_tutor_lms_discord_client_id'] ) ? sanitize_text_field( trim( $_POST['ets_tutor_lms_discord_client_id'] ) ) : '';

		$ets_tutor_lms_discord_client_secret = isset( $_POST['ets_tutor_lms_discord_client_secret'] ) ? sanitize_text_field( trim( $_POST['ets_tutor_lms_discord_client_secret'] ) ) : '';

		$ets_tutor_lms_discord_bot_token = isset( $_POST['ets_tutor_lms_discord_bot_token'] ) ? sanitize_text_field( trim( $_POST['ets_tutor_lms_discord_bot_token'] ) ) : '';

		$ets_tutor_lms_discord_redirect_url = isset( $_POST['ets_tutor_lms_discord_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_tutor_lms_discord_redirect_url'] ) ) : '';

		$ets_tutor_lms_discord_admin_redirect_url = isset( $_POST['ets_tutor_lms_discord_admin_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_tutor_lms_discord_admin_redirect_url'] ) ) : '';

		$ets_tutor_lms_discord_server_id = isset( $_POST['ets_tutor_lms_discord_server_id'] ) ? sanitize_text_field( trim( $_POST['ets_tutor_lms_discord_server_id'] ) ) : '';

		$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) );

		if ( isset( $_POST['submit'] ) ) {
			if ( isset( $_POST['ets_tutor_lms_discord_save_settings'] ) && wp_verify_nonce( $_POST['ets_tutor_lms_discord_save_settings'], 'save_tutor_lms_discord_general_settings' ) ) {
				if ( $ets_tutor_lms_discord_client_id ) {
					update_option( 'ets_tutor_lms_discord_client_id', $ets_tutor_lms_discord_client_id );
				}

				if ( $ets_tutor_lms_discord_client_secret ) {
					update_option( 'ets_tutor_lms_discord_client_secret', $ets_tutor_lms_discord_client_secret );
				}

				if ( $ets_tutor_lms_discord_bot_token ) {
					update_option( 'ets_tutor_lms_discord_bot_token', $ets_tutor_lms_discord_bot_token );
				}

				if ( $ets_tutor_lms_discord_redirect_url ) {
					update_option( 'ets_tutor_lms_discord_redirect_page_id', $ets_tutor_lms_discord_redirect_url );
					$ets_tutor_lms_discord_redirect_url = ets_get_tutor_lms_discord_formated_discord_redirect_url( $ets_tutor_lms_discord_redirect_url );
					update_option( 'ets_tutor_lms_discord_redirect_url', $ets_tutor_lms_discord_redirect_url );

				}

				if ( $ets_tutor_lms_discord_server_id ) {
					update_option( 'ets_tutor_lms_discord_server_id', $ets_tutor_lms_discord_server_id );
				}
				if ( $ets_tutor_lms_discord_admin_redirect_url ) {
					update_option( 'ets_tutor_lms_discord_admin_redirect_url', $ets_tutor_lms_discord_admin_redirect_url );
				}
				/**
				* Call function to save bot name option
				 */
				ets_tutor_lms_discord_update_bot_name_option();

				$message = esc_html__( 'Your settings are saved successfully.', 'connect-discord-tutor-lms' );

				$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_tutor_lms_application_details';
				wp_safe_redirect( $pre_location );
			}
		}
	}

	/**
	 *
	 * GET OBJECT REST API response
	 */
	public function ets_tutor_lms_load_discord_roles() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security.
		if ( ! wp_verify_nonce( $_POST['ets_tutor_lms_discord_nonce'], 'ets-tutor-lms-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$user_id           = get_current_user_id();
		$server_id         = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_bot_token' ) ) );

		if ( $server_id && $discord_bot_token ) {
			$discod_server_roles_api = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'guilds/' . $server_id . '/roles';
			$guild_args              = array(
				'method'  => 'GET',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bot ' . $discord_bot_token,
				),
			);

			$guild_response = wp_remote_post( $discod_server_roles_api, $guild_args );
			$response_arr   = json_decode( wp_remote_retrieve_body( $guild_response ), true );

			if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
				if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
					ets_tutor_lms_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				} else {
					$response_arr['previous_mapping'] = sanitize_text_field( get_option( 'ets_tutor_lms_discord_role_mapping' ) );

					$discord_roles = array();
					foreach ( $response_arr as $key => $value ) {
						$isbot = false;
						if ( is_array( $value ) ) {
							if ( array_key_exists( 'tags', $value ) ) {
								if ( array_key_exists( 'bot_id', $value['tags'] ) ) {
									$isbot = true;
								}
							}
						}
						if ( 'previous_mapping' !== $key && false === $isbot && isset( $value['name'] ) && $value['name'] != '@everyone' ) {
							$discord_roles[ $value['id'] ]       = $value['name'];
							$discord_roles_color[ $value['id'] ] = $value['color'];
						}
					}
					update_option( 'ets_tutor_lms_discord_all_roles', serialize( $discord_roles ) );
					update_option( 'ets_tutor_lms_discord_roles_color', serialize( $discord_roles_color ) );
				}
			}

			return wp_send_json( $response_arr );
		}
	}

	/*
	Catch the Connect to Bot action from admin.
	*/
	public function ets_tutor_lms_discord_action_connect_bot() {

		if ( isset( $_GET['action'] ) && $_GET['action'] == 'tutor-lms-discord-connect-to-bot' ) {
			if ( ! current_user_can( 'administrator' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
			}

			$discord_authorise_api_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'oauth2/authorize';
			$params                    = array(
				'client_id'            => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_client_id' ) ) ),
				'permissions'          => CONNECT_DISCORD_TUTOR_LMS_BOT_PERMISSIONS,
				'scope'                => 'bot',
				'guild_id'             => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_server_id' ) ) ),
				'disable_guild_select' => 'true',
				'redirect_uri'         => sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_admin_redirect_url' ) ) ),
				'response_type'        => 'code',
			);

			$discord_authorise_api_url = CONNECT_DISCORD_TUTOR_LMS_API_URL . 'oauth2/authorize?' . http_build_query( $params );
			wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
			exit;
		}
	}

	/**
	 * Save Discord Roles Courses mapping.
	 */
	public function ets_tutor_lms_discord_role_mapping() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_discord_roles = isset( $_POST['ets_tutor_lms_discord_role_mapping'] ) ? sanitize_textarea_field( trim( $_POST['ets_tutor_lms_discord_role_mapping'] ) ) : '';

		$ets_tutor_lms_discord_default_role_id = isset( $_POST['defaultRole'] ) ? sanitize_textarea_field( trim( $_POST['defaultRole'] ) ) : '';

		$allow_none_member = isset( $_POST['allow_none_member'] ) ? sanitize_textarea_field( trim( $_POST['allow_none_member'] ) ) : '';

		$ets_discord_roles = stripslashes( $ets_discord_roles );

		$current_url_role    = isset( $_POST['current_url_role'] ) ? sanitize_text_field( trim( $_POST['current_url_role'] ) ) : '';
		$save_mapping_status = update_option( 'ets_tutor_lms_discord_role_mapping', $ets_discord_roles );

		if ( isset( $_POST['ets_tutor_lms_discord_role_mappings_nonce'] ) && wp_verify_nonce( $_POST['ets_tutor_lms_discord_role_mappings_nonce'], 'discord_role_mappings_nonce' ) ) {

			if ( ( $save_mapping_status || isset( $_POST['ets_tutor_lms_discord_role_mapping'] ) ) && ! isset( $_POST['flush'] ) ) {

				if ( $ets_tutor_lms_discord_default_role_id ) {
					update_option( 'ets_tutor_lms_discord_default_role_id', $ets_tutor_lms_discord_default_role_id );
				}

				if ( $allow_none_member ) {
					update_option( 'ets_tutor_lms_discord_allow_none_member', $allow_none_member );
				}

				$message = esc_html__( 'Your mappings are saved successfully.', 'connect-discord-tutor-lms' );
				if ( isset( $current_url_role ) ) {
					$pre_location = $current_url_role . '&save_settings_msg=' . $message . '#ets_tutor_lms_role_level';
					wp_safe_redirect( $pre_location );
				}
			}
			if ( isset( $_POST['flush'] ) ) {
				delete_option( 'ets_tutor_lms_discord_role_mapping' );
				delete_option( 'ets_tutor_lms_discord_default_role_id' );
				$message = esc_html__( 'Your settings are flushed successfully.', 'connect-discord-tutor-lms' );

				$pre_location = $current_url_role . '&save_settings_msg=' . $message . '#ets_tutor_lms_role_level';
				wp_safe_redirect( $pre_location );
			}
		}
	}

}
