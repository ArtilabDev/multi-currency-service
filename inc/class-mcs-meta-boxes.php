<?php

if (!defined('ABSPATH')) {
    exit;
}

class MCS_Meta_Boxes {

    public function init() {
        add_action('add_meta_boxes', array($this, 'add_mcs_meta_box'));
        add_action('save_post', array($this, 'save_mcs_meta_box_data'));
    }

    public function add_mcs_meta_box() {
        $screen = get_current_screen();
        $post_type = $screen->post_type;

        if ($post_type === 'mcs_service' || $post_type === 'mcs_p_service') {
            add_meta_box('mcs_meta_box', __('Multi Currency Service Form', 'mcs'), array($this, 'render_mcs_meta_box'), $post_type, 'normal', 'high');
            add_meta_box('mcs_form_columns_meta_box', __('Form Columns', 'mcs'), array($this, 'render_form_columns_meta_box'), $post_type, 'side');
            add_meta_box('mcs_countries_currency_meta_box', __('Countries Currency', 'mcs'), array($this, 'render_countries_currency_meta_box'), $post_type, 'side');
        }

        if ($post_type === 'mcs_p_service') {
            add_meta_box('mcs_p_service_texts', __('Additional Texts', 'mcs'), array($this, 'render_mcs_p_service_texts_meta_box'), $post_type, 'side');
        }
    }

    public function render_mcs_meta_box($post) {
        $post_type = $post->post_type;

        if ($post_type === 'mcs_service') {
            mcs_load_template('mcs-form', array('countries' => mcs_get_countries()));
        } else if ($post_type === 'mcs_p_service') {
            mcs_load_template('mcs-package-form', array('countries' => mcs_get_countries()));
        }
    }

    public function render_form_columns_meta_box($post) {
        $value = get_post_meta($post->ID, 'mcs_form_column_count', true);
        if(empty($value)) {
            $value = 5;
        }
        echo '<select name="mcs_form_column_count" id="mcs_column_count" class="mcs-select-column">';
        for ($i = 2; $i <= 5; $i++) {
            echo '<option value="' . $i . '" ' . selected($value, $i, false) . '>' . ($i - 1) . '</option>';
        }
        echo '</select>';
    }

    public function render_countries_currency_meta_box($post) {
        $countries = mcs_get_countries();
        foreach ($countries as $country) {
            $currency = get_post_meta($post->ID, 'mcs_country_currency_' . sanitize_title($country), true);
            echo '<div class="input-box">';
            echo '<label for="mcs_country_currency_' . sanitize_title($country) . '">' . $country . '</label>';
            echo '<input type="text" id="mcs_country_currency_' . sanitize_title($country) . '" name="mcs_country_currency_' . sanitize_title($country) . '" value="' . esc_attr($currency) . '"/>';
            echo '</div>';
        }
        echo '<div class="country-info">'.__("Add the currency for each country and use the [currency] shortcode to display the user's country currency. 
        (The country is determined automatically based on the user's IP address).", 'mcs').'</div>';
    }

    public function render_mcs_p_service_texts_meta_box($post) {
        $fields = array(
            'mcs_text_before_price' => __('Text before price', 'mcs'),
            'mcs_text_after_price' => __('Text after price', 'mcs'),
            'mcs_more_button_text' => __('"More" button text', 'mcs'),
            'mcs_order_button_text' => __('"Order" button text', 'mcs')
        );

        foreach ($fields as $meta_key => $label) {
            $meta_value = get_post_meta($post->ID, $meta_key, true);

            echo '<div class="input-box">';
            echo '<label for="' . $meta_key . '">' . $label . '</label>';
            echo '<input type="text" id="' . $meta_key . '" name="' . $meta_key . '" value="' . esc_attr($meta_value) . '"/>';
            echo '</div>';
        }
    }
    
    public function save_mcs_meta_box_data($post_id) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['mcs_form_column_count'])) {
            update_post_meta($post_id, 'mcs_form_column_count', intval($_POST['mcs_form_column_count']));
        }

        for ($i = 0; $i < 8; $i++) {
            if (isset($_POST['mcs_header'][$i]) && isset($_POST['mcs_header'][$i])) {
                update_post_meta($post_id, 'mcs_header_' . $i, $_POST['mcs_header'][$i]);
            }

            $j = $i + 1;
            if (isset($_POST['mcs_sub_header'][$i])) {
                update_post_meta($post_id, 'mcs_sub_header_' . $j, $_POST['mcs_sub_header'][$i]);
            }

            if (isset($_POST['mcs_sub_header_single'][$i])) {
                update_post_meta($post_id, 'mcs_sub_header_single_' . $j, $_POST['mcs_sub_header_single'][$i]);
            }

            if (isset($_POST['mcs_description_single'][$i])) {
                update_post_meta($post_id, 'mcs_description_single_' . $j, $_POST['mcs_description_single'][$i]);
            }

            if (isset($_POST['mcs_more_link'][$i])) {
                update_post_meta($post_id, 'mcs_more_link_' . $j, $_POST['mcs_more_link'][$i]);
            }

            if (isset($_POST['mcs_order_link'][$i])) {
                update_post_meta($post_id, 'mcs_order_link_' . $j, $_POST['mcs_order_link'][$i]);
            }
            if (isset($_POST['mcs_country_price']) && !empty($_POST['mcs_country_price'])) {
                foreach ($_POST['mcs_country_price'] as $header_index => $country_prices) {
                    foreach ($country_prices as $country => $price) {
                        update_post_meta($post_id, 'mcs_country_price_'.$header_index.'_'.$country, sanitize_text_field($price));
                    }
                }
            }
        }

        if (isset($_POST['mcs_table_fields_'])) {
            $fields_data = $_POST['mcs_table_fields_'];

            $fields_data = json_encode($fields_data, JSON_UNESCAPED_UNICODE);
            update_post_meta($post_id, 'mcs_table_fields_', $fields_data);
        }

        $countries = mcs_get_countries();
        foreach ($countries as $country) {
            if (isset($_POST['mcs_country_currency_' . sanitize_title($country)])) {
                update_post_meta($post_id, 'mcs_country_currency_' . sanitize_title($country), sanitize_text_field($_POST['mcs_country_currency_' . sanitize_title($country)]));
            }
        }

        update_post_meta($post_id, 'mcs_text_before_price', sanitize_text_field($_POST['mcs_text_before_price']));
        update_post_meta($post_id, 'mcs_text_after_price', sanitize_text_field($_POST['mcs_text_after_price']));
        update_post_meta($post_id, 'mcs_more_button_text', sanitize_text_field($_POST['mcs_more_button_text']));
        update_post_meta($post_id, 'mcs_order_button_text', sanitize_text_field($_POST['mcs_order_button_text']));

    }
}