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

}
