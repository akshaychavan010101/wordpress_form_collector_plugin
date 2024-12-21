<?php

if (!function_exists('_wortal_get_form_key')) {

    function _wortal_get_form_key($field, $primary_field_name, $alternative_field_names = array())
    {
        $is_field_array = is_array($field);
        $primary_key = $is_field_array ? $field[$primary_field_name] : $field->{$primary_field_name};
        $extracted_alternative_key = array();
        foreach ($alternative_field_names as $name) {
            $key_value = $is_field_array ? $field[$name] : $field->{$name};
            array_push($extracted_alternative_key, $key_value);
        }
        $alternative_key = join('_', $extracted_alternative_key);
        return $primary_key != "" ? $primary_key : $alternative_key;
    }
}
