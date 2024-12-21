<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Fluent_Form
{

    private function map_field_labels($form_field_data)
    {
        $fields = isset($form_field_data["fields"]) ? $form_field_data["fields"] : null;
        if (!$fields) return [];

        $form_field_map = array();
        foreach ($fields as $key => $value) {
            $multi_fields = isset($value["fields"]) ? $value["fields"] : null;
            if ($multi_fields) {
                foreach ($multi_fields as $multi_field_key => $multi_field_value) {
                    $form_field_map[$multi_field_key] = $multi_field_value["settings"]["label"];
                }
            } else {
                $label = isset($value["settings"]["label"]) ? $value["settings"]["label"] : $value["settings"]["admin_field_label"];
                $field_key = $value["attributes"]["name"];
                $form_field_map[$field_key] = $label;
            }
        }
        return $form_field_map;
    }



    private function format_name_field($names)
    {
        if (!is_array($names)) return $names;
        $result = "";
        foreach ($names as $value) {
            $result .= $value . " ";
        }
        $result = rtrim($result);
        return $result;
    }


    private function format_fields($form_data, $form_fields)
    {
        $unwanted_fields = array(
            "__fluent_form_embded_post_id",
            "_fluentform_3_fluentformnonce",
            "_fluentform_5_fluentformnonce",
            "_wp_http_referer"
        );

        $form_field_map = $this->map_field_labels($form_fields);
        $body = array();
        foreach ($form_data as $key => $value) {
            if (in_array($key, $unwanted_fields)) continue;

            if (is_array($value)) {
                $sub_body = array();
                foreach ($value as $_key => $_value) {
                    $field_title = isset($form_field_map[$_key]) ? $form_field_map[$_key] : $_key;
                    $sub_body[$field_title] = $_value;
                }
                $field_title = isset($form_field_map[$key]) ? $form_field_map[$key] : $key;
                $body[$field_title] = $sub_body;
            } else {
                $field_title = isset($form_field_map[$key]) ? $form_field_map[$key] : $key;
                $body[$field_title] = $value;
            }
        }
        return $body;
    }



    public function submit_to_wortal($entryId, $formData, $form)
    {

        $form_fields = json_decode($form->form_fields, true);
        $payload = $this->format_fields($formData, $form_fields);

        $form_title = $form->title;
        $payload['form_name'] = $form_title;
        $payload['integration_key'] = 'fluent_form';

        if (array_key_exists("names", $payload)) {
            $payload['names'] = $this->format_name_field($payload['names']);
        };

        $wortal_api = new Wortal_CRM_API('fluent_form', $form_title);
        $endpoint = $wortal_api->build_api_endpoint();

        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
