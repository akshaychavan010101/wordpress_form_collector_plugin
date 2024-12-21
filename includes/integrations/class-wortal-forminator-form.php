<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Forminator_Form
{

    private function map_field_labels($form_field_data)
    {
        $form_field_map = array();
        foreach ($form_field_data as $key => $value) {
            $element_id = $value->element_id;
            $field_label = $value->field_label;
            $form_field_map[$element_id] = $field_label;
        }
        return $form_field_map;
    }


    private function format_fields($fields, $form_field_data)
    {
        $form_field_map = $this->map_field_labels($form_field_data);
        $body = array();
        foreach ($fields as $key => $field) {
            if (strpos($key, 'forminator_addon') === 0) continue;
            $field_title = isset($form_field_map[$key]) ? $form_field_map[$key] : $key;
            $body[$field_title] = $field['value'];
        }
        return $body;
    }



    public function submit_to_wortal($form_id, $response)
    {
        if (!$response) return;
        if (!is_array($response)) return;
        if (!$response['success']) return;

        $entry_response = Wortal_Forminator_Form_Entry_Model::get_latest_entry_by_form_id($form_id);

        $entry_data = $entry_response->{'meta_data'};
        $form_data = Wortal_Forminator_API::get_form($form_id);
        $form_field_data = Wortal_Forminator_API::get_form_fields($form_id);

        $form_settings = $form_data->{'settings'};
        $form_name = $form_settings["formName"];

        $wortal_api = new Wortal_CRM_API('forminator_form', $form_name);
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->format_fields($entry_data, $form_field_data);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'forminator_form';
        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
