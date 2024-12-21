<?php
class Wortal_CRM_Updater
{
    private function version_upgrader()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wortal_user_info';
        $result = $wpdb->get_row("SELECT * FROM {$table_name}");
        if (!$result) return null;

        $existing_options = array(
            'cf7' => true,
            'wortal_key' => $result->wortal_key,
            'web_reference' => $result->web_reference
        );
        Wortal_CRM_Options::set_values($existing_options);
        $wpdb->query("DROP TABLE {$table_name}");
    }

    public function apply_any_update()
    {
        $previous_version =  self::get_plugin_version_from_db();

        if (!$previous_version) {
            $this->version_upgrader();
        }
        self::save_plugin_version_to_db();
    }


    public static function save_plugin_version_to_db()
    {
        update_option(Wortal_CRM_Constants::WP_OPTION_VERSION_NAME, WORTAL_CRM_VERSION);
    }

    public static function get_plugin_version_from_db()
    {
        return get_option(Wortal_CRM_Constants::WP_OPTION_VERSION_NAME);
    }
}
