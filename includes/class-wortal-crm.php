<?php

class Wortal_CRM
{
    protected $loader;


    protected $plugin_id;


    protected $version;


    public function __construct()
    {

        if (defined('WORTAL_CRM_VERSION')) {
            $this->version = WORTAL_CRM_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_id = WORTAL_CRM_PLUGIN_ID;

        $this->load_dependencies();
        $this->update_plugin();
        $this->define_admin_hooks();
        $this->define_integration_hooks();
    }



    private function load_dependencies()
    {


        // Load the classes
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wortal-crm-loader.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wortal-crm-updater.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wortal-crm-admin.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wortal-crm-options.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/common-utils.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wortal-crm-api.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-referrer-capture.php';


        // Load the Integrations
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-wpcf7.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-wpforms.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-elementor-form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-gravity-form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-houzez.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-divi.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-ninja-form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-forminator-form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-fluent-form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-formidable.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-everest-form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/integrations/class-wortal-metform.php';


        $this->loader = new Wortal_CRM_Loader();
    }

    private function define_admin_hooks()
    {
        $plugin_admin = new Wortal_CRM_Admin($this->get_plugin_id(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'wortal_plugin_menu', 10);



        $plugin_options = new Wortal_CRM_Options();
        $hook_name = 'wp_ajax_' . Wortal_CRM_Constants::WP_SAVE_HOOK_NAME;
        $this->loader->add_action($hook_name, $plugin_options, 'save_options_handler');
    }

    private function define_integration_hooks()
    {

        $wortal_wpcf7 = new Wortal_CRM_CF7();
        $this->loader->add_action('wpcf7_before_send_mail', $wortal_wpcf7, 'submit_cf7_to_wortal', 10);

        $wortal_wpforms = new  Wortal_CRM_Wpforms();
        $this->loader->add_action('wpforms_process_complete', $wortal_wpforms, 'submit_wpforms_to_wortal', 10, 4);

        $wortal_elementorform = new Wortal_CRM_Elementor_Form();
        $this->loader->add_action('elementor_pro/forms/new_record', $wortal_elementorform, 'submit_elementor_form_to_wortal', 10, 2);

        $wortal_gravity_form = new Wortal_CRM_Gravity_Form();
        $this->loader->add_action('gform_after_submission', $wortal_gravity_form, 'submit_to_wortal', 10, 2);

        $wortal_houzez = new Wortal_CRM_Houzez();
        $this->loader->add_action('houzez_after_agent_form_submission', $wortal_houzez, 'submit_to_wortal');
        $this->loader->add_action('houzez_after_contact_form_submission', $wortal_houzez, 'submit_to_wortal');
        $this->loader->add_action('houzez_after_estimation_form_submission', $wortal_houzez, 'submit_to_wortal');

        $wortal_divi_form = new Wortal_CRM_Divi();
        $this->loader->add_action('et_pb_contact_form_submit', $wortal_divi_form, 'submit_to_wortal', 100, 3);

        $wortal_formidable = new Wortal_CRM_Formidable();
        $this->loader->add_action('frm_after_create_entry', $wortal_formidable, 'submit_to_wortal', 30, 2);

        $wortal_everest_form = new Wortal_CRM_Everest_Form();
        $this->loader->add_action('everest_forms_complete_entry_save', $wortal_everest_form, 'submit_to_wortal', 40, 5);

        $wortal_metform = new Wortal_CRM_Metform();
        $this->loader->add_action('metform_after_store_form_data', $wortal_metform, 'submit_to_wortal', 50, 4);

        $wortal_ninja_form = new Wortal_CRM_Ninja_Form();
        $this->loader->add_action('ninja_forms_after_submission', $wortal_ninja_form, 'submit_to_wortal');

        $wortal_forminator = new Wortal_CRM_Forminator_Form();
        $this->loader->add_action('forminator_form_after_save_entry', $wortal_forminator, 'submit_to_wortal', 10, 2);

        $wortal_fluent_form = new Wortal_CRM_Fluent_Form();
        $this->loader->add_action('fluentform_submission_inserted', $wortal_fluent_form, 'submit_to_wortal', 20, 3);
    }

    private function update_plugin()
    {
        $updater = new Wortal_CRM_Updater();
        $updater->apply_any_update();
    }


    public function run()
    {
        $this->loader->run();
    }

    public function get_plugin_id()
    {
        return $this->plugin_id;
    }


    public function get_loader()
    {
        return $this->loader;
    }

    public function get_version()
    {
        return $this->version;
    }
}
