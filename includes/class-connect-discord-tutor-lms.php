<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Connect_Discord_Tutor_Lms
 * @subpackage Connect_Discord_Tutor_Lms/includes
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Discord_Tutor_Lms {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Connect_Discord_Tutor_Lms_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CONNECT_DISCORD_TUTOR_LMS_VERSION' ) ) {
			$this->version = CONNECT_DISCORD_TUTOR_LMS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'connect-discord-tutor-lms';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_common_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Connect_Discord_Tutor_Lms_Loader. Orchestrates the hooks of the plugin.
	 * - Connect_Discord_Tutor_Lms_i18n. Defines internationalization functionality.
	 * - Connect_Discord_Tutor_Lms_Admin. Defines all hooks for the admin area.
	 * - Connect_Discord_Tutor_Lms_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all methods that help to schedule actions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libraries/action-scheduler/action-scheduler.php';

		/**
		 * Common functions file.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-discord-tutor-lms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-discord-tutor-lms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-connect-discord-tutor-lms-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-connect-discord-tutor-lms-public.php';

		$this->loader = new Connect_Discord_Tutor_Lms_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Connect_Discord_Tutor_Lms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Connect_Discord_Tutor_Lms_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Connect_Discord_Tutor_Lms_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ets_tutor_discord_add_discord_menu', 99 );
		$this->loader->add_action( 'wp_ajax_ets_tutor_lms_discord_update_redirect_url', $plugin_admin, 'ets_tutor_lms_discord_update_redirect_url' );
		$this->loader->add_action( 'admin_post_tutor_lms_discord_application_settings', $plugin_admin, 'ets_tutor_lms_discord_application_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'ets_tutor_lms_discord_action_connect_bot' );
		$this->loader->add_action( 'wp_ajax_ets_tutor_lms_load_discord_roles', $plugin_admin, 'ets_tutor_lms_load_discord_roles' );
		$this->loader->add_action( 'admin_post_tutor_lms_discord_role_mapping', $plugin_admin, 'ets_tutor_lms_discord_role_mapping' );
		$this->loader->add_action( 'admin_post_tutor_lms_discord_advance_settings', $plugin_admin, 'ets_tutor_lms_discord_advance_settings' );
		$this->loader->add_action( 'admin_post_tutor_lms_discord_save_appearance_settings', $plugin_admin, 'ets_tutor_lms_discord_save_appearance_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Connect_Discord_Tutor_Lms_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode( 'tutor_lms_discord', $plugin_public, 'ets_tutor_lms_add_discord_button' );
		$this->loader->add_filter( 'kses_allowed_protocols', $plugin_public, 'ets_tutor_lms_discord_allow_data_protocol' );
		/** If load from page dashboard */
		$this->loader->add_action( 'tutor_load_dashboard_template_before', $plugin_public, 'ets_tutor_lms_display_discord_button' );
		$this->loader->add_action( 'tutor_dashboard/before_header_button', $plugin_public, 'ets_tutor_lms_display_discord_button' );
		$this->loader->add_action( 'init', $plugin_public, 'ets_tutor_lms_discord_api_callback' );
		$this->loader->add_action( 'ets_tutor_lms_discord_as_handle_add_member_to_guild', $plugin_public, 'ets_tutor_lms_discord_as_handler_add_member_to_guild', 10, 3 );
		$this->loader->add_action( 'ets_tutor_lms_discord_as_schedule_member_put_role', $plugin_public, 'ets_tutor_lms_discord_as_handler_put_member_role', 10, 3 );
		$this->loader->add_action( 'ets_tutor_lms_discord_as_send_dm', $plugin_public, 'ets_tutor_lms_discord_discord_handler_send_dm', 10, 4 );
		$this->loader->add_action( 'wp_ajax_tutor_lms_disconnect_from_discord', $plugin_public, 'ets_tutor_lms_disconnect_from_discord' );
		$this->loader->add_action( 'ets_tutor_lms_discord_as_schedule_delete_role', $plugin_public, 'ets_tutor_lms_discord_as_handler_delete_memberrole', 10, 3 );
		$this->loader->add_action( 'ets_tutor_lms_discord_as_schedule_delete_member', $plugin_public, 'ets_tutor_lms_discord_as_handler_delete_member_from_guild', 10, 3 );
		$this->loader->add_action( 'tutor_after_enrolled', $plugin_public, 'ets_tutor_lms_discord_enrolled_course', 99, 3 );
		$this->loader->add_action( 'tutor_lesson_completed_after', $plugin_public, 'ets_tutor_lms_discord_lesson_completed_after', 99, 2 );

	}

	/**
	 * Discord Logo
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return STRING
	 */
	public static function get_discord_logo_white() {
		$img  = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'public/images/discord-logo-white.svg' );
		$data = base64_encode( $img );

		return '<img class="ets-discord-logo-white" src="data:image/svg+xml;base64,' . $data . '" />';
	}

	/**
	 * Define actions which are not in admin or not public
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {
		$this->loader->add_action( 'action_scheduler_failed_execution', $this, 'ets_tutor_lms_discord_reschedule_failed_action' );
		$this->loader->add_filter( 'action_scheduler_queue_runner_batch_size', $this, 'ets_tutor_lms_discord_queue_batch_size' );
		$this->loader->add_filter( 'action_scheduler_queue_runner_concurrent_batches', $this, 'ets_tutor_lms_discord_concurrent_batches' );

	}

	/**
	 * Re-schedule  failed action
	 *
	 * @param INT            $action_id
	 * @param OBJECT         $e
	 * @param OBJECT context
	 * @return NONE
	 */
	public function ets_tutor_lms_discord_reschedule_failed_action( $action_id ) {
		// First check if the action is for tutor lms discord.
		$action_data = ets_tutor_lms_discord_as_get_action_data( $action_id );
		if ( $action_data !== false ) {
			$hook              = $action_data['hook'];
			$args              = json_decode( $action_data['args'] );
			$retry_failed_api  = sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_retry_failed_api' ) ) );
			$hook_failed_count = ets_tutor_lms_discord_count_of_hooks_failures( $hook );
			$retry_api_count   = absint( sanitize_text_field( trim( get_option( 'ets_tutor_lms_discord_retry_api_count' ) ) ) );
			if ( $hook_failed_count < $retry_api_count && $retry_failed_api == true && $action_data['as_group'] == CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME && $action_data['status'] === 'failed' ) {
				as_schedule_single_action( ets_tutor_lms_discord_get_random_timestamp( ets_tutor_lms_discord_get_highest_last_attempt_timestamp() ), $hook, array_values( $args ), CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME );
			}
		}
	}

	/**
	 * Set action scheuduler batch size.
	 *
	 * @param INT $batch_size
	 * @return INT $concurrent_batches
	 */
	public function ets_tutor_lms_discord_queue_batch_size( $batch_size ) {
		if ( ets_tutor_lms_discord_get_all_pending_actions() !== false ) {
			return absint( get_option( 'ets_tutor_lms_discord_job_queue_batch_size' ) );
		} else {
			return $batch_size;
		}
	}

	/**
	 * Set action scheuduler concurrent batches.
	 *
	 * @param INT $concurrent_batches
	 * @return INT $concurrent_batches
	 */
	public function ets_tutor_lms_discord_concurrent_batches( $concurrent_batches ) {
		if ( ets_tutor_lms_discord_get_all_pending_actions() !== false ) {
			return absint( get_option( 'ets_tutor_lms_discord_job_queue_concurrency' ) );
		} else {
			return $concurrent_batches;
		}
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Connect_Discord_Tutor_Lms_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
