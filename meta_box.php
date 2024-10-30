<?php

function WCDP_meta_content($product) {
    wp_enqueue_media();
    $custom = get_post_custom($product->ID);
    if (isset($custom["WCDP_is_customizable"][0]) && $custom["WCDP_is_customizable"][0] == 'on') {
        $WCDP_is_customizable = true;
    } else {
        $WCDP_is_customizable = FALSE;
    }
    ?>
    <table id="WCDP_is_customizable_box">
        <tr>
            <td>Product design is customizable</td>
            <td>
                <input type="hidden" name="WCDP_is_customizable" value="off" />
                <input type="checkbox" name="WCDP_is_customizable" id="WCDP_is_customizable"  <?php if ($WCDP_is_customizable == true) { ?>checked="checked"<?php } ?>/>
            </td>
        </tr>
    </table>    <?php
}

function WCDP_metabox() {
    add_meta_box('WCDP_metabox_custom', 'Product Custom Design', 'WCDP_meta_content', 'product', 'normal', 'high');
}

add_action('admin_init', 'WCDP_metabox');

add_action('save_post', 'WCDP_meta_content_save', 10, 2);

function WCDP_meta_content_save($post_id, $product) {
    //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $WCDP_is_customizable = filter_var($_POST['WCDP_is_customizable'], FILTER_SANITIZE_STRING);
    if ($product->post_type == 'product') {
        if (!empty($WCDP_is_customizable)) {
            update_post_meta($post_id, 'WCDP_is_customizable', $WCDP_is_customizable);
        }
    }
}
