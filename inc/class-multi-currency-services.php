<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Multi_Currency_Services {

    public function __construct()
    {
        $this->init_post_type_hooks();
        $this->init_meta_boxes();
        $this->init_scripts_and_styles();
        $this->init_country_info();
    }

    private function init_post_type_hooks()
    {
        require_once MCS_PLUGIN_DIR . 'inc/class-mcs-post-types.php';
        $post_type_class = new MCS_Post_Types();
        add_action('init', array($post_type_class, 'create_post_types'));
    }

    private function init_meta_boxes()
    {
        require_once MCS_PLUGIN_DIR . 'inc/class-mcs-meta-boxes.php';
        $post_type_class = new MCS_Meta_Boxes();
        $post_type_class->init();
    }

    private function init_scripts_and_styles()
    {
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_and_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
    }

    private function init_country_info() {
        require_once MCS_PLUGIN_DIR . 'inc/class-mcs-country-info.php';
        $country_info = new MCS_Country_Info();
    }

    public function admin_enqueue_scripts_and_styles() {
        wp_enqueue_style('mcs-admin-css', plugins_url('assets/css/admin.css', MCS_PLUGIN_DIR . 'multi-currency-services.php'));

        wp_enqueue_script('mcs-admin-js', plugins_url('assets/js/admin.js', MCS_PLUGIN_DIR . 'multi-currency-services.php'), array('jquery'), '1.0.0', true);
        wp_localize_script('mcs-admin-js', 'mcs', array(
            'countries' => mcs_get_countries()
        ));
    }

    public function enqueue_scripts_and_styles() {
        wp_enqueue_style('mcs-front-css', plugins_url('assets/css/front.css', MCS_PLUGIN_DIR . 'multi-currency-services.php'));
    }

    public static function create_plugin_database_table()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'mcs_country_info';

        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip varchar(50) NOT NULL,
        ip_info text NOT NULL,
        date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public static function remove_plugin_database_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'mcs_country_info';

        $sql = "DROP TABLE IF EXISTS $table_name;";

        $wpdb->query($sql);
    }

    public static function activate()
    {
        require_once MCS_PLUGIN_DIR . 'inc/class-mcs-post-types.php';

        $post_type_class = new MCS_Post_Types();
        $post_type_class->create_post_types();

        self::create_plugin_database_table();

    }

    public static function deactivate()
    {
        flush_rewrite_rules();

//        self::remove_plugin_database_table();
    }
}
