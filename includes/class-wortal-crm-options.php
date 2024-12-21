<?php
class Wortal_CRM_Options
{

    public static function set_values($fields)
    {
        $allowed_keys = array_column(Wortal_CRM_Constants::SUPPORTED_INTEGRATIONS, 'key');
        array_push($allowed_keys, 'web_reference', 'wortal_key');
        $filtered_values = array_intersect_key($fields, array_flip($allowed_keys));
        update_option(Wortal_CRM_Constants::WP_OPTION_NAME, $filtered_values);
    }


    public static function get_values()
    {
        $options = get_option(Wortal_CRM_Constants::WP_OPTION_NAME);
        return $options ? $options : array();
    }


    public static function get_single_value($key)
    {
        $options = self::get_values();
        return isset($options[$key]) ? $options[$key] : null;
    }


    public static function get_allowed_integrations()
    {
        function compare_active_status($left, $right)
        {
            return $right['status'] - $left['status'];
        }

        function add_detail($integration)
        {
            $key = $integration['key'];
            $iconFile = $integration['iconFile'];
            $integration['enabled'] = Wortal_CRM_Options::get_single_value($key);
            $integration['status'] = Wortal_CRM_Integration_Status::get_status($integration);
            $integration['iconUrl'] = Wortal_CRM_Constants::get_plugin_icon_url($iconFile);

            $is_connected = $integration['status'] > Wortal_CRM_Integration_Status::ACTIVATED;
            $integration['enabled'] = $integration['enabled'] && $is_connected;
            return $integration;
        }

        $result = array_map('add_detail', Wortal_CRM_Constants::SUPPORTED_INTEGRATIONS);
        usort($result, 'compare_active_status');
        return $result;
    }

    public function save_options_handler()
    {
        $submission = $_POST;
        $existing_token = self::get_single_value('wortal_key');
        if ($existing_token) return self::set_values($submission);


        $integrations = self::get_allowed_integrations();
        $installed = array_filter($integrations, function ($integration) {
            return $integration['status'] >= Wortal_CRM_Integration_Status::INSTALLED;
        });

        foreach ($installed as $integration) {
            $key = $integration['key'];
            $submission[$key] = true;
        }

        self::set_values($submission);
    }
}
