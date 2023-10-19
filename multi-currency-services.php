<?php
/*
Plugin Name: Multi Currency Services $
Description: Easy to use management system for Multi Currency Services
Version: 1.0.1
Author: ArtiLab
Author URI: https://artilab.pro/
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('MCS_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once MCS_PLUGIN_DIR . 'inc/class-multi-currency-services.php';
require_once MCS_PLUGIN_DIR . 'inc/class-mcs-translation-page.php';
require_once MCS_PLUGIN_DIR . 'inc/helpers/mcs-helper.php';

// Hook to 'plugins_loaded' action.
add_action('plugins_loaded', function () {
    new Multi_Currency_Services();
});

register_activation_hook(__FILE__, array('Multi_Currency_Services', 'activate'));
register_deactivation_hook(__FILE__, array('Multi_Currency_Services', 'deactivate'));