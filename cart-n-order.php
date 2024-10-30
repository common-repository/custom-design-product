<?php

// define the woocommerce_add_cart_item_data callback
add_filter('woocommerce_add_cart_item_data', 'filter_woocommerce_add_cart_item_data', 10, 3);
if (!function_exists('filter_woocommerce_add_cart_item_data')) {

    function filter_woocommerce_add_cart_item_data($cart_item_data, $product_id, $variation_id) {
        global $woocommerce;
        if (!empty($_POST['desing_value'])) {
            wcdp_register_session();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $desing_value = filter_var(@$_POST['desing_value'], FILTER_SANITIZE_STRING);
            $custom_img_url = filter_var($_SESSION['custom_img_url'], FILTER_VALIDATE_URL);
            $cart_item_data['desing_value'] = $desing_value;
            $cart_item_data['custom_img_url'] = $custom_img_url;
        }

        return $cart_item_data;
    }

}

add_action('woocommerce_add_order_item_meta', 'wcdp_add_values_to_order_item_meta', 1, 2);
if (!function_exists('wcdp_add_values_to_order_item_meta')) {

    function wcdp_add_values_to_order_item_meta($item_id, $values) {
        global $woocommerce, $wpdb;
        $custom_img_url = $values['custom_img_url'];
        $desing_value = $values['desing_value'];
        if (!empty($desing_value) && !empty($desing_value)) {
            wc_add_order_item_meta($item_id, 'custom_img_url', $custom_img_url);
            wc_add_order_item_meta($item_id, 'desing_value', $desing_value);
        }
    }

}
