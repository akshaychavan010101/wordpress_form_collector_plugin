<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-wortal-crm-constants.php';
delete_option(Wortal_CRM_Constants::WP_OPTION_NAME);
delete_option(Wortal_CRM_Constants::WP_OPTION_VERSION_NAME);
