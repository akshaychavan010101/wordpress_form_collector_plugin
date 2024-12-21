<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Divi
{

    private function format_fields_payload($fields)
    {
        $body = array();
        foreach ($fields as $key => $value) {
            $body[$key] = $value['value'];
        }
        return $body;
    }


    public function submit_to_wortal($processed_fields_values, $et_contact_error, $contact_form_info)
    {
        global $post;

        $form_name = $post->post_title;
        $wortal_api = new Wortal_CRM_API('divi', $form_name);
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->format_fields_payload($processed_fields_values);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'divi';

        $wortal_api->submit_lead_to_wortal($endpoint,  $payload, Request_Content_Type::FORM_URLENCODED);
    }
}
