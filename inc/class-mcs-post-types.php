<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class MCS_Post_Types {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('template_redirect', array($this, 'redirect_custom_post_types'));
        add_action('wp_head', array($this, 'noindex_custom_post_types'));
    }

    public function create_post_types() {
        $this->register_services_post_type();
        $this->register_package_services_post_type();
        $this->register_countries_post_type();

        flush_rewrite_rules();
    }

    private function register_services_post_type() {
        $args = array(
            'public' => true,
            'label'  => __('Services', 'mcs'),
            'show_in_menu' => false,
            'supports' => array('title', 'thumbnail', 'custom-fields'),
            'rewrite' => array('slug' => 'mcs-service'),
        );
        register_post_type('mcs_service', $args);
    }

    private function register_package_services_post_type() {
        $args = array(
            'public' => true,
            'label'  => __('Package Services', 'mcs'),
            'show_in_menu' => false,
            'supports' => array('title', 'thumbnail', 'custom-fields'),
            'rewrite' => array('slug' => 'mcs-p-service'),
        );
        register_post_type('mcs_p_service', $args);
    }

    private function register_countries_post_type() {
        $args = array(
            'public' => true,
            'label'  => __('Countries', 'mcs'),
            'show_in_menu' => false,
            'supports' => array('title', 'thumbnail', 'custom-fields'),
            'rewrite' => array('slug' => 'mcs-country'),
        );
        register_post_type('mcs_country', $args);
    }

    public function add_plugin_page() {
        global $submenu;

        add_submenu_page(
            'mcs_main_menu',
            __('Services', 'mcs'),
            __('Services', 'mcs'),
            'manage_options',
            'edit.php?post_type=mcs_service'
        );

        add_submenu_page(
            'mcs_main_menu',
            __('Package Services', 'mcs'),
            __('Package Services', 'mcs'),
            'manage_options',
            'edit.php?post_type=mcs_p_service'
        );

        add_submenu_page(
            'mcs_main_menu',
            __('Countries', 'mcs'),
            __('Countries', 'mcs'),
            'manage_options',
            'edit.php?post_type=mcs_country'
        );

        add_menu_page(
            __( 'Multi Currency Services $', 'mcs' ),
            __( 'Multi Currency Services $', 'mcs' ),
            'manage_options',
            'mcs_main_menu',
            '',
            'dashicons-tickets',
            6
        );

        if (isset($_GET['page']) && $_GET['page'] == 'mcs_main_menu') {
            wp_redirect(admin_url('edit.php?post_type=mcs_service'));
            exit;
        }
    }

    public function redirect_custom_post_types() {
        if (is_singular('mcs_service') || is_singular('mcs_p_service') || is_singular('mcs_country')) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit;
        }
    }

    public function noindex_custom_post_types() {
        if (is_singular('mcs_service') || is_singular('mcs_p_service') || is_singular('mcs_country')) {
            echo '<meta name="robots" content="noindex">';
        }
    }
}