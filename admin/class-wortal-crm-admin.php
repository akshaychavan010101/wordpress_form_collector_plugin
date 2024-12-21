<?php

class Wortal_CRM_Admin
{
    private $plugin_id;
    private $version;

    public function __construct($plugin_id, $version)
    {
        $this->plugin_id = $plugin_id;
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        if ($this->is_on_plugin_page()) {
            $path = plugin_dir_url(__FILE__) . 'css/libs/tailwind.min.css';
            wp_enqueue_style("{$this->plugin_id}_tailwind", $path, array(), $this->version, 'all');
        }
    }

    public function enqueue_scripts()
    {
        if ($this->is_on_plugin_page()) {
            $path = plugin_dir_url(__FILE__) . 'js/libs/petite-vue.min.js';
            wp_enqueue_script("{$this->plugin_id}_petite-vue", $path, array(), $this->version, false);
        }

        require_once plugin_dir_path(__FILE__) . 'partials/wortal-crm-admin-display.php';
    }

    public function wortal_plugin_menu()
    {
        add_menu_page("Wortal CRM", "Wortal CRM", 'manage_options', "wortal-crm-admin-display", "render_wortal_admin_page", plugin_dir_url(__FILE__) . 'images/wortal.svg');
    }

    private function is_on_plugin_page()
    {
        $screen = get_current_screen();
        return 'toplevel_page_wortal-crm-admin-display' === $screen->base;
    }
}
