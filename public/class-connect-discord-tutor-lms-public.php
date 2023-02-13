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
		$enrolled_courses                                 = tutor_utils()->get_enrolled_courses_ids_by_user( $user_id );
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
				$disconnect_btn_bg_color  = 'style="background-color:' . $ets_tutor_lms_discord_disconnect_button_bg_color . '"';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<label class="ets-connection-lbl">' . esc_html__( 'Discord connection', 'connect-discord-tutor-lms' ) . '</label>';
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<a href="#" class="ets-btn tutor-lms-discord-btn-disconnect" ' . $disconnect_btn_bg_color . ' id="tutor-lms-discord-disconnect-discord" data-user-id="' . esc_attr( $user_id ) . '">' . esc_html( $ets_tutor_lms_discord_disconnect_button_text ) . Connect_Discord_Tutor_Lms::get_discord_logo_white() . '</a>';
				$restrictcontent_discord .= '<span class="ets-spinner"></span>';
				$restrictcontent_discord .= '<p>' . esc_html__( sprintf( 'Connected account: %s', $_ets_tutor_lms_discord_username ), 'connect-discord-tutor-lms' ) . '</p>';
				$restrictcontent_discord  = ets_tutor_lms_discord_get_user_avatar( $_ets_tutor_lms_discord_user_id, $discord_user_avatar, $restrictcontent_discord );
				$restrictcontent_discord  = ets_tutor_lms_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord .= '</div>';

			} elseif ( ( ets_tutor_lms_discord_get_student_courses_id( $user_id ) && $mapped_role_name )
								|| ( ets_tutor_lms_discord_get_student_courses_id( $user_id ) && ! $mapped_role_name && $default_role_name )
								|| ( $allow_none_student == 'yes' ) ) {

				$connect_btn_bg_color     = 'style="background-color:' . $ets_tutor_lms_discord_connect_button_bg_color . '"';
				$restrictcontent_discord .= '<div>';
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

}
