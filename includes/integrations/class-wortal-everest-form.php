<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Everest_Form
{

    private function extract_value_by_type($field)
    {
        $field_type_with_raw_value = array("select", "radio", "checkbox");
        if (!in_array($field["type"], $field_type_with_raw_value)) {
            return $field["value"];
        }


        $raw = $field["value_raw"];
        if ($field["type"] == "select" && is_array($raw) && count($raw) == 1) {
            return $field["value_raw"][0];
        } else {
            return $field["value_raw"];
        }
    }

    private function extract_key($field)
    {
        if (isset($field["name"])) return $field["name"];
        elseif (isset($field["value"]["name"])) return $field["value"]["name"];
        return $field["id"];
    }


    private function extract_fields($fields)
    {
        $body = array();
        foreach ($fields as $index => $field) {
            $key = $this->extract_key($field);
            $body[$key] = $this->extract_value_by_type($field);
        }
        return $body;
    }

    public function submit_to_wortal($entry_id, $fields, $entry, $form_id, $form_data)
    {
        $form_name = $form_data["settings"]["form_title"];
        $wortal_api = new Wortal_CRM_API('everest_form', $form_name);
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->extract_fields($fields);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'everest_form';

        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
