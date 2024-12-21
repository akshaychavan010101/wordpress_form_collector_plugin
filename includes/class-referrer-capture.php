<?php

if (!defined('ABSPATH')) {
    exit;
}

class ReferrerCapture
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_store_referrer', [$this, 'store_referrer']);
        add_action('wp_ajax_nopriv_store_referrer', [$this, 'store_referrer']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('referrer-script', plugin_dir_url(__FILE__) . 'referrer-capture.js', [], null, true);

        wp_localize_script('referrer-script', 'myAjax', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);
    }

    public function store_referrer()
    {
        $_SESSION['referrer'] = $_POST['referrer'];
    }
}

new ReferrerCapture();
