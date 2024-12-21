<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class  Wortal_CRM_Ninja_Form
{


    private function format_fields_payload($fields)
    {
        $body = array();
        foreach ($fields as $field) {
            $body[$field['label']] = $field['value'];
        }
        return $body;
    }


    public function submit_to_wortal($form_data)
    {
        $form_title = $form_data['settings']['title'];
        $form_fields =  $form_data['fields'];
        $wortal_api = new Wortal_CRM_API('ninja_form', $form_title);
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->format_fields_payload($form_fields);
        $payload['form_name'] = $form_title;
        $payload['integration_key'] = 'ninja_form';
        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
