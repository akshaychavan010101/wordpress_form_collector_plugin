<?php


class Wortal_CRM_Integration_Status
{
    public const NOT_EXIST = 0;
    public const INSTALLED = 1;
    public const ACTIVATED = 2;
    public const CONNECTED = 3;


    public static function to_array()
    {
        return array(
            "NotExist" => self::NOT_EXIST,
            "Installed" => self::INSTALLED,
            "Activated" => self::ACTIVATED,
            "Connected" => self::CONNECTED,
        );
    }

    private static function is_plugin_active($plugin_slugs)
    {
        foreach ($plugin_slugs as $slug) {
            if (is_plugin_active($slug)) {
                return true;
            }
        }
        return false;
    }

    private static function is_plugin_installed($plugin_slugs)
    {
        $installed_plugins = get_plugins();
        foreach ($plugin_slugs as $slug) {
            if (
                array_key_exists($slug, $installed_plugins)
                || in_array($slug, $installed_plugins, true)
            ) {
                return true;
            }
        }
        return false;
    }


    private static function is_theme_installed($theme_names)
    {
        foreach ($theme_names as $name) {
            $theme = wp_get_theme($name);
            if ($theme->exists()) {
                return true;
            }
        }
        return false;
    }


    private static function is_theme_active($theme_names)
    {
        foreach ($theme_names as $name) {
            $current_theme = wp_get_theme();
            if ($name == $current_theme->get_template()) {
                return true;
            }
        }
        return false;
    }


    private static function get_plugin_status($plugin_slugs, $enabled = false)
    {
        $is_active = self::is_plugin_active($plugin_slugs);
        $is_installed = self::is_plugin_installed($plugin_slugs);
        if (!$is_installed) return self::NOT_EXIST;
        if (!$is_active) return self::INSTALLED;
        if (!$enabled) return self::ACTIVATED;
        return self::CONNECTED;
    }


    private static function get_theme_status($theme_names, $enabled = false)
    {
        $is_active = self::is_theme_active($theme_names);
        $is_installed = self::is_theme_installed($theme_names);
        if (!$is_installed) return self::NOT_EXIST;
        if (!$is_active) return self::INSTALLED;
        if (!$enabled) return self::ACTIVATED;
        return self::CONNECTED;
    }


    public static function get_status($integration)
    {
        $enabled = $integration['enabled'];
        $identifiers = $integration['identifiers'];
        $type = $integration['type'];
        if (WP_Wortal_Integration_Type::THEME == $type) {
            return self::get_theme_status($identifiers, $enabled);
        } elseif (WP_Wortal_Integration_Type::PLUGIN == $type) {
            return self::get_plugin_status($identifiers, $enabled);
        }
        return self::NOT_EXIST;
    }
}
