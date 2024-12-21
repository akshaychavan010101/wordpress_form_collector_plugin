<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Metform
{

    private function extract_value_by_type($field, $form_value)
    {
        if ($field["widgetType"] === 'mf-password') return '********';
        if ($field["widgetType"] === 'mf-switch') {
            if (!$form_value) return $field["mf_swtich_disable_text"];
            return $form_value;
        }

        $field_type_with_list = array("mf-select", "mf-multi-select", "mf-radio", "mf-checkbox");
        if (!in_array($field["widgetType"], $field_type_with_list)) {
            return $form_value;
        }


        $value_list = explode(",", $form_value);
        $readable_values = array();

        foreach ($value_list as $raw_value) {
            foreach ($field["mf_input_list"] as $input_ref) {
                if (isset($input_ref["mf_input_option_value"]) && $input_ref["mf_input_option_value"] == $raw_value) {
                    array_push($readable_values, $input_ref["mf_input_option_text"]);
                } elseif (isset($input_ref["value"]) && $input_ref["value"] == $raw_value) {
                    array_push($readable_values, $input_ref["label"]);
                }
            }
        }
        return implode(", ", $readable_values);
    }


    private function extract_fields($form_id, $form_values)
    {
        $map_data = '\MetForm\Core\Entries\Action'::instance()->get_fields($form_id);
        $form_data = json_decode(wp_json_encode($map_data), true);

        $body = array();
        foreach ($form_data as $index => $field) {
            $key = $field["mf_input_label"];
            $raw_value = isset($form_values[$index]) ? $form_values[$index] : null;
            $value = $this->extract_value_by_type($field, $raw_value);

            if ($value === null) continue;

            $body[$key] = $value;
        }
        return $body;
    }


    public function submit_to_wortal($form_id, $form_values, $form_settings, $attributes)
    {
        $form_name = $form_settings["form_title"];
        $wortal_api = new Wortal_CRM_API('metform', $form_name);

        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->extract_fields($form_id, $form_values);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'metform';

        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
