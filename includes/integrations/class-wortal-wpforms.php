<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Wpforms
{
    private function extract_value_by_type($field)
    {
        if ($field['type'] === 'password') return '********';

        if ($field['type'] === 'likert_scale') {
            return preg_replace('/(.+:)\n/', '$1 ', $field['value']);
        }

        return $field['value'];
    }

    private function extract_fields($fields)
    {
        $body = array();
        foreach ($fields as $field) {
            $key = _wortal_get_form_key($field, 'name', array('type', 'id'));
            $body[$key] = $this->extract_value_by_type($field);
        }
        return $body;
    }


    public function submit_wpforms_to_wortal($fields, $__entry, $form_data, $__entry_id)
    {

        $web_reference = Wortal_CRM_Options::get_single_value('web_reference');
        $form_name = $form_data['settings']['form_title'];
        $Wortal_CRM_API = new Wortal_CRM_API('wpforms', $form_name);
        $endpoint = $Wortal_CRM_API->build_api_endpoint();
        $payload['form_data'] = $this->extract_fields($fields);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'wpforms';
        $payload['web_reference'] = $web_reference;
        $Wortal_CRM_API->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
