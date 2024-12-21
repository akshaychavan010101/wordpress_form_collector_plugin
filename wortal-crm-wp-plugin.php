<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 * 
 * @link              https://www.wortal.co
 * @since             0.1.0
 * @package           Wortal_Crm
 * 
 * @wordpress-plugin
 * Plugin Name:       Wortal CRM - Lead Collector for Contact Forms
 * Description:       Connect the WordPress forms with the Wortal API and get the leads directly in your CRM business.
 * Version:           1.0.1
 * Author:            Wortal
 * Author URI:        https://wortal.co/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wortal-crm-wp-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

function start_session()
{
    if (!session_id()) {
        session_start();
    }
}

add_action('init', 'start_session');


define('WORTAL_CRM_VERSION', '1.0.1');
define('WORTAL_CRM_PLUGIN_ID', 'wortal-crm');



global $wpdb;
require_once plugin_dir_path(__FILE__) . 'includes/class-wortal-crm-constants.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wortal-crm-integration-status.php';
require plugin_dir_path(__FILE__) . 'includes/class-wortal-crm.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-referrer-capture.php';



function run_wortal_crm()
{
    $plugin = new Wortal_CRM();
    $plugin->run();
}
run_wortal_crm();
