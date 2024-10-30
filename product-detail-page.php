<?php
if (!function_exists('wcdp_file_choose_form')) {

    function wcdp_file_choose_form() {
        ?>
        <form method="post" enctype='multipart/form-data' action="<?php echo get_permalink(); ?>?product_design=do">
            <div class="" style="margin:25px;">
                <input type="file" name="custom_img" />
            </div>
            <div>
                <input name="accept_img_copyright" type="checkbox"/>
                I own the rights or have permission to use this design, and I agree to the Spoonflower Terms of Service.
            </div>
            <div class="" style="margin:25px;">
                <input class="button" name="submit_img" disabled="disabled" type="submit"/>
            </div>
        </form>
        <script>
            jQuery(document).ready(function () {
                jQuery('input[name="submit_img"]').attr('disabled', 'disabled');
                jQuery('input[name="accept_img_copyright"]').change(function () {
                    if (jQuery(this).is(':checked')) {
                        jQuery('input[name="submit_img"]').removeAttr('disabled');
                    } else {
                        jQuery('input[name="submit_img"]').attr('disabled', 'disabled');
                    }
                });
            });
        </script>
        <?php
    }

}

//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
if (!function_exists('wcdp_single_product_first_page')) {

    function wcdp_single_product_first_page() {
        $product_meta = get_post_meta(get_the_ID());
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        if ($product_meta['WCDP_is_customizable'][0] == 'on' && empty($_GET['product_design'])) {
            add_action('woocommerce_single_product_summary', 'wcdp_file_choose_form', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        }
    }

}
add_action('woocommerce_single_product_summary', 'wcdp_single_product_first_page', 8);

//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
if (!function_exists('wcdp_upload_custom_img')) {

    function wcdp_upload_custom_img() {
        if (!empty($_FILES['custom_img'])) {
            wcdp_register_session();
            if (!function_exists('wp_handle_upload')) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            $uploadedfile = $_FILES['custom_img'];
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
            if ($movefile && !isset($movefile['error']) && filter_var($movefile['url'], FILTER_VALIDATE_URL)) {
                $_SESSION['custom_img_file'] = $movefile['file'];
                $_SESSION['custom_img_url'] = $movefile['url'];
            } else {
                /**
                 * Error generated by _wp_handle_upload()
                 * @see _wp_handle_upload() in wp-admin/includes/file.php
                 */
                echo $movefile['error'];
                echo 'Please try again';
            }
        }
    }

}
add_action('woocommerce_before_single_product_summary', 'wcdp_upload_custom_img', 5);

//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
if (!function_exists('wcdp_image_preview')) {

    function wcdp_image_preview() {
        wcdp_register_session();
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $custom_img_url = filter_var(@$_SESSION['custom_img_url'], FILTER_SANITIZE_URL);
        $product_meta = get_post_meta(get_the_ID());
        if ($product_meta['WCDP_is_customizable'][0] == 'on' && !empty($_GET['product_design']) && $_GET['product_design'] == 'do') {
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
            if (!empty($custom_img_url)) {
                ?>
                <div style="width:50%;float: left;">
                    <div id="design_preview" ></div>
                    <style>
                        #design_preview{
                            /*width: 250px;*/
                            height: 400px;
                        }
                    </style>
                </div>
                <?php
            } else {
                echo 'no preview available';
            }
        }
    }

}
add_action('woocommerce_before_single_product_summary', 'wcdp_image_preview', 10);


//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
if (!function_exists('wcdp_choose_design_form')) {

    function wcdp_choose_design_form() {
//        echo 'hi';
//                die();
        $product_meta = get_post_meta(get_the_ID());
        if ($product_meta['WCDP_is_customizable'][0] == 'on') {
            wcdp_register_session();
            $custom_img_url = filter_var($_SESSION['custom_img_url'], FILTER_SANITIZE_URL);
            ?>
            <fieldset style="padding: 10px;">
                <legend style="    font-size: 0.8em;">Choose a print design</legend>
                <div class="clearfix">
                    <div>
                        <table style="width:301px;    margin: 0;" class="custom_design_table">
                            <tr>
                                <td style="text-align: center" vertical-align="middle">
                                    <span id="design_basic" class="custom_design selected">
                                        <img src="<?php echo plugin_dir_url(__FILE__) ?>img/Basic_OFF.png"/>
                                    </span>
                                </td>
                                <td style="text-align: center" vertical-align="middle">
                                    <span id="design_center" class="custom_design selected">
                                        <img src="<?php echo plugin_dir_url(__FILE__) ?>img/Center_OFF.png"/>
                                    </span>
                                </td>
                                <td style="text-align: center" vertical-align="middle">
                                    <span id="design_mirror" class="custom_design selected">
                                        <img src="<?php echo plugin_dir_url(__FILE__) ?>img/Mirror_OFF.png"/>
                                    </span>
                                </td>
                                <td style="text-align: center" vertical-align="middle">
                                    <span id="design_halfbrick" class="custom_design selected">
                                        <img src="<?php echo plugin_dir_url(__FILE__) ?>img/Brick_OFF.png"/>
                                    </span>
                                </td>
                                <td style="text-align: center" vertical-align="middle">
                                    <span id="design_halfdrop" class="custom_design selected">
                                        <img src="<?php echo plugin_dir_url(__FILE__) ?>img/half-drop.png"/>
                                    </span>
                                </td>
                            </tr>
                            <tr style="font-size: 0.6em;">
                                <td >Basic</td>
                                <td>Center</td>
                                <td>Mirror</td>
                                <td>HALFBRICK</td>
                                <td>half-drop</td>
                            </tr>
                        </table>
                        <input type="hidden" id="desing_value" name="desing_value" value=""/>
                    </div>
                </div>
            </fieldset>
            <style>
                .selected_design{
                    background-color:#ccc;
                }
                .custom_design_table td{
                    padding:5px !important;
                    text-align:center;
                }
            </style>
            <script>
                // This is just an example URL.
                // A public URL to an image couldn't be used here, see http://stackoverflow.com/questions/2390232
                var exampleImageUrl = '<?php echo $custom_img_url; ?>';
                /**
                 * @param HTMLElement element  Element that should get the background
                 * @param string      imageSrc URL to the original image
                 * @param string      mode     normal-repeat, half-drop, half-brick, mirror or center
                 * @param integer     width    Width of the background-image
                 * @param integer     height   Height of the background-image
                 */
                var setBackground = function (element, imageSrc, mode, width, height) {

                    // Creating an image object to load the image
                    var image = new Image;
                    // Listen to the load event of the image object
                    image.addEventListener('load', function () {

                        // The image is now successfully loaded

                        // Store the dimensions for the canvas
                        var canvasWidth = width;
                        var canvasHeight = height;
                        if (mode === 'half-drop' || mode === 'mirror') {
                            // For half-drop and mirror mode we need a wider canvas
                            canvasWidth *= 2;
                        }
                        if (mode === 'half-brick' || mode === 'mirror') {
                            // For half-brick and mirror mode we need a higher canvas
                            canvasHeight *= 2;
                        }

                        // Create a canvas element
                        var canvas = document.createElement('canvas');
                        // Set the dimensions
                        canvas.width = canvasWidth;
                        canvas.height = canvasHeight;
                        // Get the 2d context to be able to draw on the canvas
                        var context = canvas.getContext('2d');
                        if (mode === 'half-drop') {
                            // Draw the image in the left half of the canvas
                            context.drawImage(image, 0, 0, width, height);
                            // Draw the image in the right top quarter of the canvas
                            context.drawImage(image, width, height / -2, width, height);
                            // Draw the image in the right bottom quarter of the canvas
                            context.drawImage(image, width, height / 2, width, height);
                            // Now the canvas looks like this:
                            // ---------------------------------------
                            // |                  |        ||        |
                            // |                  |      ======      |
                            // |      ======      |                  |
                            // |        ||        |                  |
                            // |        ||        |------------------|
                            // |        ||        |                  |
                            // |      ======      |                  |
                            // |                  |      ======      |
                            // |                  |        ||        |
                            // ---------------------------------------
                        } else if (mode === 'half-brick') {
                            // Draw the image in the top half of the canvas
                            context.drawImage(image, 0, 0, width, height);
                            // Draw the image in the bottom left quarter of the canvas
                            context.drawImage(image, width / -2, height, width, height);
                            // Draw the image in the bottom right quarter of the canvas
                            context.drawImage(image, width / 2, height, width, height);
                            // Now the canvas looks like this:
                            // ---------------------
                            // |                   |
                            // |                   |
                            // |      =======      |
                            // |        | |        |
                            // |        | |        |
                            // |        | |        |
                            // |      =======      |
                            // |                   |
                            // |                   |
                            // ---------------------
                            // |         |         |
                            // |         |         |
                            // |===      |      ===|
                            // ||        |        ||
                            // ||        |        ||
                            // ||        |        ||
                            // |===      |      ===|
                            // |         |         |
                            // |         |         |
                            // ---------------------
                        } else if (mode === 'mirror') {
                            // Draw the image in the top left quarter of the canvas
                            context.drawImage(image, 0, 0, width, height);
                            // Save the transformation of the canvas context
                            context.save();
                            // Flip the canvas vertical
                            context.scale(-1, 1);
                            // Draw the image in the top right quarter of the canvas
                            context.drawImage(image, -width * 2, 0, width, height);
                            // Flip the canvas horizonal
                            context.scale(1, -1);
                            // Draw the image in the bottom right quarter of the canvas
                            context.drawImage(image, -width * 2, -height * 2, width, height);
                            // Flip the canvas vertical
                            context.scale(-1, 1);
                            // Draw the image in the bottom left quarter of the canvas
                            context.drawImage(image, 0, -height * 2, width, height);
                            // Restore the transformation of the canvas context to the original state
                            context.restore();
                            // Now the canvas looks like this:
                            // -----------------------------------------
                            // |                   |                   |
                            // |                   |                   |
                            // |      =======      |      =======      |
                            // |      ||           |           ||      |
                            // |      ||===        |        ===||      |
                            // |      ||           |           ||      |
                            // |      ||           |           ||      |
                            // |                   |                   |
                            // |                   |                   |
                            // -----------------------------------------
                            // |                   |                   |
                            // |                   |                   |
                            // |      ||           |           ||      |
                            // |      ||           |           ||      |
                            // |      ||===        |        ===||      |
                            // |      ||           |           ||      |
                            // |      =======      |      =======      |
                            // |                   |                   |
                            // |                   |                   |
                            // -----------------------------------------
                        }

                        // "center" and "normal-repeat" mode
                        else {
                            // Draw the image to the full size of the canvas
                            context.drawImage(image, 0, 0, width, height);
                        }

                        // Set the contents of the canvas as background-image
                        element.style.backgroundImage = 'url("' + canvas.toDataURL('image/png') + '")';
                        if (mode === 'center') {
                            // Add CSS needed for the mode "center"
                            element.style.backgroundPosition = '50% 50%';
                            element.style.backgroundRepeat = 'no-repeat';
                        } else {
                            // Add CSS needed for the mode other than "center"
                            element.style.backgroundPosition = 'inherit';
                            element.style.backgroundRepeat = 'inherit';
                        }

                    }, false);
                    // Load the image
                    image.src = imageSrc;
                };

                jQuery(document).ready(function () {
                    set_width = 150;
                    set_height = 150;
                    img_tag = document.createElement('img');
                    img_tag.src = exampleImageUrl;
                    img_tag.addEventListener('load', function () {
                        img_width = img_tag.naturalWidth;
                        img_height = img_tag.naturalHeight;
                        console.log(img_width);
                        console.log(img_height);
            //                        set_width = img_width;
                        set_height = (img_height / img_width) * set_width;
//                        set_height = img_height;
                        jQuery('#design_basic').trigger('click');
                    });

            //                    jQuery('#design_basic').trigger('click');
            //                                setTimeout(function () {
            //                                    jQuery('#design_basic').trigger('click');
            //                                }, 2000);
                    preview_element = document.getElementById('design_preview');
                    jQuery('#design_mirror').click(function () {
                        setBackground(preview_element, exampleImageUrl, 'mirror', set_width, set_height);
                        jQuery('.custom_design img').removeClass('selected_design');
                        jQuery(this).find('img').addClass('selected_design');
                        jQuery('#desing_value').val('design_mirror');
                    });
                    jQuery('#design_center').click(function () {
                        setBackground(preview_element, exampleImageUrl, 'center', set_width, set_height);
                        jQuery('.custom_design img').removeClass('selected_design');
                        jQuery(this).find('img').addClass('selected_design');
                        jQuery('#desing_value').val('design_center');
                    });
                    jQuery('#design_basic').click(function () {
                        setBackground(preview_element, exampleImageUrl, 'normal-repeat', set_width, set_height);
                        jQuery('.custom_design img').removeClass('selected_design');
                        jQuery(this).find('img').addClass('selected_design');
                        jQuery('#desing_value').val('design_basic');
                    });
                    jQuery('#design_halfbrick').click(function () {
                        setBackground(preview_element, exampleImageUrl, 'half-brick', set_width, set_height);
                        jQuery('.custom_design img').removeClass('selected_design');
                        jQuery(this).find('img').addClass('selected_design');
                        jQuery('#desing_value').val('design_halfbrick');
                    });
                    jQuery('#design_halfdrop').click(function () {
                        setBackground(preview_element, exampleImageUrl, 'half-drop', set_width, set_height);
                        jQuery('.custom_design img').removeClass('selected_design');
                        jQuery(this).find('img').addClass('selected_design');
                        jQuery('#desing_value').val('design_halfdrop');
                    });
                });
            </script>
            <?php
        }
    }

}

//add_action('woocommerce_before_variations_form', 'wcdp_choose_design_form', 10);
add_action('woocommerce_before_add_to_cart_button', 'wcdp_choose_design_form', 10);