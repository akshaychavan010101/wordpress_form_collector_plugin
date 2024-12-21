<?php
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}


class Request_Content_Type
{
    public const JSON = 'application/json';
    public const FORM_URLENCODED = 'application/x-www-form-urlencoded';
}


class Wortal_CRM_API
{
    private $plugin_key;
    private $form_name;

    function __construct($plugin_key, $form_name = null)
    {
        $this->plugin_key = $plugin_key;
        $this->form_name = $form_name;
    }


    public function build_api_endpoint()
    {
        $wortal_key = Wortal_CRM_Options::get_single_value('wortal_key');
        if (empty($wortal_key)) return null;

        $endpoint = Wortal_CRM_Constants::get_webhook_url($this->plugin_key);
        $web_reference = Wortal_CRM_Options::get_single_value('web_reference');

        $queries = array(
            'wortal_key' => $wortal_key,
            'web_reference' => $web_reference
        );
        $querystring = http_build_query($queries);

        return "{$endpoint}?{$querystring}";
    }


    private function format_payload($payload, $type)
    {
        if ($type == Request_Content_Type::JSON) return wp_json_encode($payload);
        if ($type == Request_Content_Type::FORM_URLENCODED) return http_build_query($payload);
        return $payload;
    }


    private function is_integration_enabled()
    {
        $is_enabled = Wortal_CRM_Options::get_single_value($this->plugin_key);
        return 'true' == $is_enabled;
    }

    public function submit_lead_to_wortal($endpoint, $payload, $type)
    {
        $is_enabled = $this->is_integration_enabled();
        if (!$endpoint || !$payload || !$is_enabled) return;
        $payload["form_name"] = $this->form_name;
        $payload["referrer"] = $_SESSION['referrer'];
        $options = [
            'body' => self::format_payload($payload, $type),
            'headers' => ['Content-Type' => $type],
            'blocking' => false,
            'data_format' => 'body',
        ];
        wp_remote_post($endpoint, $options);
        $_SESSION['referrer'] = null;
    }
}
