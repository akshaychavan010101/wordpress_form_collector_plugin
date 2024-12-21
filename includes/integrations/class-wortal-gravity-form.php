<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Gravity_Form
{

    private function get_list_values($string)
    {
        preg_match_all('/"(.*?)"/', $string, $match);
        return $match[1];
    }



    private function format_fields_payload($entry, $data)
    {
        $body = array();
        foreach ($data as $field) {
            $inputs = $field->get_entry_inputs();
            $key = _wortal_get_form_key($field, 'label', array('type', 'id'));
            if (is_array($inputs)) {
                $temp = array();
                foreach ($inputs as $input) {
                    $value = rgar($entry, (string) $input['id']);
                    if (!empty($value)) {
                        $temp[] = $value;
                    }
                }
                if ($field->type == 'name') {
                    $body[$key] = implode(" ", $temp);
                } else {
                    $body[$key] = wp_json_encode(array_values($temp), JSON_UNESCAPED_SLASHES);
                }
            } else {
                $value = rgar($entry, (string) $field->id);

                if ($field->type == 'list') {
                    $body[$key] = wp_json_encode(array_values($this->get_list_values($value)), JSON_UNESCAPED_SLASHES);
                } else {
                    $body[$key] = $value;
                }
            }
        }
        return $body;
    }



    public function submit_to_wortal($entry, $form)
    {
        $form_name = $form["title"];
        $wortal_api = new Wortal_CRM_API('gravity_form', $form_name);
        $endpoint = $wortal_api->build_api_endpoint($form_name);
        $payload = $this->format_fields_payload($entry, $form['fields']);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'gravity_form';
        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::FORM_URLENCODED);
    }
}
