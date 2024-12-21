<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_Houzez
{

    private function clean_payload($form_data)
    {

        $exclude_fields = array(
            'action',
            'webhook',
            'webhook_url',
            'redirect_to',
            'email_to',
            'email_subject',
            'email_to_cc',
            'email_to_bcc',
            'houzez_contact_form',
            'target_email',
            'property_nonce',
            'prop_payment',
            'property_agent_contact_security',
            'contact_realtor_ajax',
            'is_listing_form',
            'submit',
            'realtor_page',
        );

        if (!empty($form_data) && is_array($form_data)) {
            foreach ($exclude_fields as $field) {
                if (isset($form_data[$field])) {
                    unset($form_data[$field]);
                }
            }
        }

        return $form_data;
    }


    public function submit_to_wortal()
    {
        $wortal_api = new Wortal_CRM_API('houzez');
        $endpoint = $wortal_api->build_api_endpoint();
        $payload = $this->clean_payload($_POST);
        $payload['integration_key'] = 'houzez';
        $wortal_api->submit_lead_to_wortal($endpoint, $payload, Request_Content_Type::JSON);
    }
}
