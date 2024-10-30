<?php

/*
 * Plugin Name: Woocommerce Custom Print
 * Plugin URI: https://wordpress.org/plugins/custom-design-product/
 * Description: A plugin for woocommerce product needs to be printed with custom image, and custom placement of the image on the print.
 * Version: 1.5
 * Author: Ahmad Asjad
 * Author URI: https://profiles.wordpress.org/ahmadasjad
 * Required at least:
 * Tested upto:
 * Tags: Woocommerce, Custom Print, Custom Design
 */

function wcdp_register_session() {
    if(version_compare(PHP_VERSION, '5.4.0', '>=') && session_status() === PHP_SESSION_NONE){
        session_start();
    }elseif (version_compare(PHP_VERSION, '5.4.0', '<') && !session_id()) {
        session_start();
    }
}

add_action('init', 'wcdp_register_session');

require_once dirname(__FILE__) . '/meta_box.php';
require_once dirname(__FILE__) . '/product-detail-page.php';
require_once dirname(__FILE__) . '/cart-n-order.php';

//==============================================================================
