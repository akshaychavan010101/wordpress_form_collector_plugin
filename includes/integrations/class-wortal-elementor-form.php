<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Elementor_Form
{
    private function extract_value_by_type($field)
    {
        if ($field['type'] === 'password') return '********';
        return $field['value'];
    }


    private function format_fields_payload($fields)
    {
        $body = array();
        foreach ($fields as $field) {
            $key = _wortal_get_form_key($field, 'title', array('type', 'id'));
            $body[$key] = $this->extract_value_by_type($field);
        }
        return $body;
    }


    public function submit_elementor_form_to_wortal($record, $handler)
    {
        $web_reference = Wortal_CRM_Options::get_single_value('web_reference');

        $form_name = $record->get_form_settings('form_name');
        $raw_fields = $record->get('fields');

        $wortal_api = new Wortal_CRM_API('elementor_form', $form_name);
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->format_fields_payload($raw_fields);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'elementor_form';
        $payload['web_reference'] = $web_reference;


        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::FORM_URLENCODED);
    }
}
