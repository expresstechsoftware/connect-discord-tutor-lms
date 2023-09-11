<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://www.expresstechsoftwares.com
 * @since             1.0.0
 * @package           Connect_Discord_Tutor_Lms
 *
 * @wordpress-plugin
 * Plugin Name:       Connect TutorLMS to Discord
 * Plugin URI:        https://www.expresstechsoftwares.com/connect-discord-tutor-lms
 * Description:       The plugin seamlessly integrates with the Tutor LMS platform, allowing admins to easily link their courses to private Discord servers.
 * Version:           1.0.4
 * Author:            ExpressTech Software Solutions Pvt. Ltd.
 * Author URI:        https://https://www.expresstechsoftwares.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connect-discord-tutor-lms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONNECT_DISCORD_TUTOR_LMS_VERSION', '1.0.4' );
define( 'CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'CONNECT_DISCORD_TUTOR_LMS_API_URL', 'https://discord.com/api/v10/' );
define( 'CONNECT_DISCORD_TUTOR_LMS_BOT_PERMISSIONS', 8 );
define( 'CONNECT_DISCORD_TUTOR_LMS_OAUTH_SCOPES', 'identify email guilds guilds.join' );

/**
 * Define group name for action scheduler actions
 */
define( 'CONNECT_DISCORD_TUTOR_LMS_AS_GROUP_NAME', 'ets-tutor-lms-discord' );

/**
 * Follwing response codes not cosider for re-try API calls.
 */
define( 'CONNECT_DISCORD_TUTOR_LMS_DONOT_RETRY_THESE_API_CODES', array( 0, 10003, 50033, 10004, 50025, 10013, 10011 ) );

/**
 * Define plugin directory url
 */
define( 'CONNECT_DISCORD_TUTOR_LMS_DONOT_RETRY_HTTP_CODES', array( 400, 401, 403, 404, 405, 502 ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-connect-discord-tutor-lms-activator.php
 */
function activate_connect_discord_tutor_lms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-discord-tutor-lms-activator.php';
	Connect_Discord_Tutor_Lms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-connect-discord-tutor-lms-deactivator.php
 */
function deactivate_connect_discord_tutor_lms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-discord-tutor-lms-deactivator.php';
	Connect_Discord_Tutor_Lms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_connect_discord_tutor_lms' );
register_deactivation_hook( __FILE__, 'deactivate_connect_discord_tutor_lms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-connect-discord-tutor-lms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_connect_discord_tutor_lms() {

	$plugin = new Connect_Discord_Tutor_Lms();
	$plugin->run();

}
run_connect_discord_tutor_lms();
