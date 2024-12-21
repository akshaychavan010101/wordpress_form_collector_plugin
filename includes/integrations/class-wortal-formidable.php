<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Formidable
{
    private function extract_value_by_type($item_meta, $field)
    {
        $base_value = $item_meta[$field->id];
        if ($field->type == 'name') {
            return trim("{$base_value['first']} {$base_value['last']}");
        }
        return $base_value;
    }

    private function extract_fields($form_id)
    {
        $item_meta = $_POST['item_meta'];
        $fields = WortalFrmField::get_all_for_form($form_id);

        $body = array();
        foreach ($fields as $field) {
            $key = $field->name;
            $body[$key] = $this->extract_value_by_type($item_meta, $field);
        }
        return $body;
    }


    public function submit_to_wortal($entry_id, $form_id)
    {
        $form = WortalFrmForm::getOne($form_id);
        $form_name = $form->name;

        $wortal_api = new Wortal_CRM_API('formidable_form', $form_name);
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->extract_fields($form_id);
        $payload['form_name'] = $form_name;
        $payload['integration_key'] = 'formidable_form';
        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
