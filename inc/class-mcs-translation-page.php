<?php

if (!defined('ABSPATH')) {
    exit;
}


class MCS_Translations_Page {

    private $option_name = 'mcs_translations_option';
    private $languages = array('uk', 'ru', 'pl', 'en');
    private $fields = array(
        'mcs_text_before_price',
        'mcs_text_after_price',
        'mcs_more_button_text',
        'mcs_order_button_text'
    );

    public function __construct() {
        add_action('admin_menu', array($this, 'register_translation_page'));
        add_action('admin_init', array($this, 'handle_translation_submission'));
    }

    public function register_translation_page() {
        add_menu_page(
            __('Translations', 'mcs'),
            __('Translations', 'mcs'),
            'manage_options',
            'mcs_translations_menu',
            array($this, 'render_translation_page'),
            '',
            7
        );

        add_submenu_page(
            'mcs_main_menu',
            __('Translations', 'mcs'),
            __('Translations', 'mcs'),
            'manage_options',
            'mcs_translations_menu',
            array($this, 'render_translation_page')
        );

        add_action('admin_menu', function() {
            remove_menu_page('mcs_translations_menu');
        }, 999);
    }

    public function register_translation_settings() {
        register_setting('mcs_translations_group', $this->option_name, array($this, 'sanitize_translations'));
    }

    public function render_translation_page() {
        $stored_translations = get_option($this->option_name, "{}");
        $translations = json_decode($stored_translations, true);

        echo '<div class="wrap">';
        echo '<h1>'.__('Translations', 'mcs').'</h1>';
        echo '<form id="mcs_translations_page" method="post" action="">';
        echo '<input type="hidden" name="mcs_translations_nonce" value="' . wp_create_nonce('mcs_translations_save') . '">';

        foreach ($this->languages as $lang) {
            echo '<h2>' . strtoupper($lang) . '</h2>';
            foreach ($this->fields as $field) {
                $value = isset($translations[$field][$lang]) ? $translations[$field][$lang] : '';
                $pretty_label = ucwords(str_replace('_', ' ', str_replace('mcs_', '', $field)));

                echo '<div class="input-box">';
                echo '<label for="' . $field . '_' . $lang . '">' . __($pretty_label, 'mcs') . '</label>';
                echo '<input type="text" id="' . $field . '_' . $lang . '" name="' . $field . '[' . $lang . ']" value="' . esc_attr($value) . '"/>';
                echo '</div>';
            }
        }

        submit_button();
        echo '</form>';
        echo '</div>';
    }

    public function handle_translation_submission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mcs_translations_nonce']) && wp_verify_nonce($_POST['mcs_translations_nonce'], 'mcs_translations_save')) {
            $result = array();

            foreach ($this->fields as $field) {
                foreach ($this->languages as $lang) {
                    if (isset($_POST[$field][$lang])) {
                        $result[$field][$lang] = sanitize_text_field($_POST[$field][$lang]);
                    }
                }
            }

            update_option($this->option_name, json_encode($result));
        }
    }
}

new MCS_Translations_Page();
