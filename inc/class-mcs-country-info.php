<?php

if (!defined('ABSPATH')) {
    exit;
}

class MCS_Country_Info {

    public function __construct()
    {
        add_action('init', array($this, 'mcs_process_country_info'));
    }

    public function mcs_process_country_info()
    {
        if( !isset( $_COOKIE['mcs_info'] ) ) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            if(strripos($ip, ":") === false) {
                global $wpdb;
                $ip_info = $wpdb->get_var( "SELECT ip_info FROM ".$wpdb->prefix."mcs_country_info WHERE ip = '".$ip."'");
                if($ip_info){
                    $output = $ip_info;
                } else {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://cstat.nextel.com.ua:8443/tracking/registration/ipData?ip=".$ip);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    $wpdb->insert( $wpdb->prefix."mcs_country_info", [ 'ip' => $ip, 'ip_info' => $output, 'date' => time() ] );
                }
            } else {
                $output = '{"currency":"USD","countryCode":"US"}';
            }
            if(isset($output)) setcookie( "mcs_info", $output, strtotime( '+90 days' ), "/" );
        }
    }

}
