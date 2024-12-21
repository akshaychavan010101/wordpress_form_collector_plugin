<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('Un-authorized access!');
}

class Wortal_CRM_CF7
{
    private function get_posted_data($cf7)
    {
        $wortal_key = Wortal_CRM_Options::get_single_value('wortal_key');
        $web_reference = Wortal_CRM_Options::get_single_value('web_reference');
        if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission') && !empty($wortal_key)) {
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                return array(
                    'title' => $cf7->title(),
                    'form_data' => $submission->get_posted_data(),
                    'wortal_key' => $wortal_key,
                    'web_reference' => $web_reference,
                    'wp_cf_type' => "contact_form7"

                );
            }
            return (array)$cf7;
        }
        return null;
    }


    public function submit_cf7_to_wortal($contact_form)
    {
        $Wortal_CRM_API = new Wortal_CRM_API('cf7');
        $webhook_url = Wortal_CRM_Constants::get_webhook_url('cf7');
        $payload = $this->get_posted_data($contact_form);
        $payload['integration_key'] = 'cf7';
        $Wortal_CRM_API->submit_lead_to_wortal($webhook_url, $payload, Request_Content_Type::JSON);
    }
}
