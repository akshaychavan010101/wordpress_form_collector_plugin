<?php

class Wortal_CRM_Constants
{

    public const API_BASE_URL = 'https://api.wortal.co';
    public const WEB_BASE_URL = 'https://app.wortal.co';
    public const WEBAPP_INTEGRATION_PAGE_LINK = self::WEB_BASE_URL . '/business';
    public const INTEGRATE_WORTAL_KEY_ENDPOINT = '/webhook/api/integration/wp_integrate';
    public const NEW_LEAD_API_ENDPOINT = '/webhook/api/integration/new_wp_integration_lead';
    public const WP_OPTION_NAME = 'wortal_options';
    public const WP_OPTION_VERSION_NAME = 'wortal_version';
    public const WP_SAVE_HOOK_NAME = 'wortal_save_options';

    public const SUPPORTED_INTEGRATIONS = array(
        array(
            'key' => 'cf7',
            'name' => 'Contact Form 7',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('contact-form-7/wp-contact-form-7.php'),
            'iconFile' =>  'cf7-logo.png',
            'bgColor' => 'rgba(52, 198, 244, 0.10)',
            'borderColor' => '#34C6F4',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'wpforms',
            'name' => 'WPForms',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('wpforms/wpforms.php', 'wpforms-lite/wpforms.php'),
            'iconFile' =>  'wpforms-logo.png',
            'bgColor' => 'rgba(185, 90, 26, 0.10)',
            'borderColor' => '#B95A1A',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'elementor_form',
            'name' => 'Elementor (Pro Form & Pro Elements)',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('elementor/elementor.php', 'elementor-pro/elementor-pro.php', 'pro-elements/pro-elements.php'),
            'iconFile' =>  'elementor-form-logo.png',
            'bgColor' => 'rgba(214, 51, 98, 0.10)',
            'borderColor' => '#e64a19',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'gravity_form',
            'name' => 'Gravity Forms',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('gravityforms/gravityforms.php'),
            'iconFile' =>  'gravity-form-logo.png',
            'bgColor' => 'rgba(241, 90, 43, 0.10)',
            'borderColor' => '#F15A2B',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'houzez',
            'name' => 'Houzez',
            'type' => WP_Wortal_Integration_Type::THEME,
            'identifiers' => array('houzez'),
            'iconFile' =>  'houzez-logo.png',
            'bgColor' => 'rgba(52, 198, 244, 0.10)',
            'borderColor' => '#34C6F4',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'divi',
            'name' => 'Divi',
            'type' => WP_Wortal_Integration_Type::THEME,
            'identifiers' => array('Divi'),
            'iconFile' =>  'divi-form-logo.png',
            'bgColor' => 'rgba(152, 52, 239, 0.10)',
            'borderColor' => '#cc9900',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'ninja_form',
            'name' => 'Ninja Forms',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('ninja-forms/ninja-forms.php'),
            'iconFile' =>  'ninja-form-logo.png',
            'bgColor' => 'rgba(240, 71, 73, 0.10)',
            'borderColor' => '#F04749',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'forminator_form',
            'name' => 'Forminator',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('forminator/forminator.php'),
            'iconFile' =>  'forminator-form-logo.png',
            'bgColor' => 'rgba(31, 40, 82, 0.10)',
            'borderColor' => '#1F2852',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'fluent_form',
            'name' => 'Fluent Forms',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('fluentform/fluentform.php'),
            'iconFile' =>  'fluent-form-logo.png',
            'bgColor' => 'rgba(0, 120, 255, 0.10)',
            'borderColor' => '#0078FF',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'formidable_form',
            'name' => 'Formidable Forms',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('formidable/formidable.php'),
            'iconFile' =>  'formidable-form-logo.png',
            'bgColor' => 'rgba(53, 75, 91, 0.10)',
            'borderColor' => '#354B5B',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'everest_form',
            'name' => 'Everest Forms',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('everest-forms/everest-forms.php'),
            'iconFile' =>  'everest-form-logo.png',
            'bgColor' => 'rgba(126, 59, 208, 0.10)',
            'borderColor' => '#7E3BD0',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
        array(
            'key' => 'metform',
            'name' => 'MetForm',
            'type' => WP_Wortal_Integration_Type::PLUGIN,
            'identifiers' => array('metform/metform.php'),
            'iconFile' =>  'metform-logo.png',
            'bgColor' => 'rgba(252, 70, 58, 0.10)',
            'borderColor' => '#FC463A',
            'webhookUrl' => self::API_BASE_URL . self::NEW_LEAD_API_ENDPOINT
        ),
    );


    public static function get_webhook_integration_url()
    {
        return self::API_BASE_URL . self::INTEGRATE_WORTAL_KEY_ENDPOINT;
    }

    public static function get_webhook_url($integration_key)
    {
        $supported_keys = array_column(self::SUPPORTED_INTEGRATIONS, 'key');
        $found_index = array_search($integration_key, $supported_keys);
        return self::SUPPORTED_INTEGRATIONS[$found_index]['webhookUrl'];
    }

    public static function get_logo_url()
    {
        return array(
            'wortalLogo' => plugins_url('admin/images/wortalLogo.png', dirname(__FILE__)),
            'wordpressLogo' => plugins_url('admin/images/wordpressLogo.png', dirname(__FILE__)),
            'linkLogo' => plugins_url('admin/images/linkLogo.png', dirname(__FILE__)),
            'saveVector' => plugins_url('admin/images/saveVector.png', dirname(__FILE__)),
        );
    }

    public static function get_plugin_icon_url($file_path)
    {
        return plugins_url('admin/images/plugins/' . $file_path, dirname(__FILE__));
    }
}


class WP_Wortal_Integration_Type
{
    public const THEME = 'theme';
    public const PLUGIN = 'plugin';
}
