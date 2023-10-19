<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('mcs_load_template')) {
    function mcs_load_template($template_name, $vars = array()) {
        if(!empty($vars)) {
            extract($vars);
        }

        $template_path = MCS_PLUGIN_DIR . 'templates/' . $template_name . '.php';

        if(file_exists($template_path)) {
            include $template_path;
        }
    }
}

if (!function_exists('mcs_get_countries')) {
    function mcs_get_countries() {
        $args = array(
            'post_type' => 'mcs_country',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        $countries_posts = get_posts($args);

        $countries = array();

        foreach ($countries_posts as $country_post) {
            $countries[] = $country_post->post_title;
        }

        return $countries;
    }
}

if (!function_exists('mcs_render_service_table')) {
    function mcs_render_service_table($service_id , $template_name = 'service-table') {
        $all_countries = mcs_get_countries();
        $country = 'US';

        if(isset($_COOKIE['mcs_info'])) {
            $mcs_info = json_decode(stripslashes($_COOKIE['mcs_info']), true);
            if(in_array($mcs_info['countryCode'], $all_countries)) {
                $country = $mcs_info['countryCode'];
            }
        }
        
        $currency = get_post_meta($service_id, 'mcs_country_currency_' . sanitize_title($country), true);
        if (!$currency) {
            $currency = get_post_meta($service_id, 'mcs_country_currency_us', true);
        }

        $meta_values = mcs_get_meta_values($service_id, $country, $currency);

        $table_fields_data = get_post_meta($service_id, 'mcs_table_fields_', true);
        $table_fields_data = json_decode($table_fields_data, true);

        $column_count = get_post_meta($service_id, 'mcs_form_column_count', true);

        if (!empty($table_fields_data) || !empty($meta_values['header_values'])) {
            $template_args = array_merge($meta_values, array(
                'table_fields_data' => $table_fields_data,
                'country' => $country,
                'column_count' => $column_count,
                'currency' => $currency,
                'service_id' => $service_id
            ));

            mcs_load_template('frontend/'.$template_name, $template_args);
        }
    }
}

if (!function_exists('mcs_get_meta_values')) {
    function mcs_get_meta_values($service_id, $country, $currency) {
        $meta_values = array(
            'header_values' => array(),
            'sub_header_values' => array(),
            'sub_header_single_values' => array(),
            'description_single_values' => array()
        );

        for ($i = 0; $i < 5; $i++) {
            $header_value = get_post_meta($service_id, 'mcs_header_' . $i, true);
            $header_value = str_replace('[currency]', $currency, $header_value);

            if (!empty($header_value)) {
                $meta_values['header_values'][] = $header_value;
            }

            if ($i > 0) {
                $sub_header_value = get_post_meta($service_id, 'mcs_sub_header_' . $i, true);
                $sub_header_single_value = get_post_meta($service_id, 'mcs_sub_header_single_' . $i, true);
                $description_single_value = get_post_meta($service_id, 'mcs_description_single_' . $i, true);

                $sub_header_value = str_replace('[currency]', $currency, $sub_header_value);
                $sub_header_single_value = str_replace('[currency]', $currency, $sub_header_single_value);
                $description_single_value = str_replace('[currency]', $currency, $description_single_value);

                if (!empty($sub_header_value)) {
                    $meta_values['sub_header_values'][$i] = $sub_header_value;
                }

                if (!empty($sub_header_single_value)) {
                    $meta_values['sub_header_single_values'][$i] = $sub_header_single_value;
                }

                if (!empty($description_single_value)) {
                    $meta_values['description_single_values'][$i] = $description_single_value;
                }

                $more_link_value = get_post_meta($service_id, 'mcs_more_link_' . $i, true);
                $order_link_value = get_post_meta($service_id, 'mcs_order_link_' . $i, true);

                if (!empty($more_link_value)) {
                    $meta_values['more_link_values'][$i] = $more_link_value;
                }

                if (!empty($order_link_value)) {
                    $meta_values['order_link_values'][$i] = $order_link_value;
                }

                $country_price_value = get_post_meta($service_id, 'mcs_country_price_' . $i . '_' . $country, true);
                $meta_values['country_price_values'][$i] = $country_price_value;

            }
        }

        return $meta_values;
    }
}

if (!function_exists('get_mcs_translation')) {
    function get_mcs_translation($field_name, $language = null) {
        $option_name = 'mcs_translations_option';
        $stored_translations = get_option($option_name, "{}");
        $translations = json_decode($stored_translations, true);

        if (!$language && defined('ICL_LANGUAGE_CODE')) {
            $language = ICL_LANGUAGE_CODE;
        }

        if (!$language) {
            return '';
        }

        if (isset($translations[$field_name][$language]) && !empty($translations[$field_name][$language])) {
            return $translations[$field_name][$language];
        }

        if (isset($translations[$field_name]['en']) && !empty($translations[$field_name]['en'])) {
            return $translations[$field_name]['en'];
        }

        return '';
    }
}
