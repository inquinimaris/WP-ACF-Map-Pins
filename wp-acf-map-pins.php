<?php
/*
Plugin Name: WP ACF Map Pins
Description: QoL plugin for creating markers on maps
Version: 1.0
Author: Inquinimaris
Author URI: https://t.me/inqiunimaris
Text Domain: wp-acf-map-pins
Domain Path: /languages
License: MIT
License URI: https://opensource.org/licenses/MIT
Requires at least: 5.0
Requires PHP: 7.2
Tags: acf, maps, markers, custom fields
*/

/*
MIT License

Copyright (c) 2024 Inquinimaris

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

add_action('admin_menu', 'wp_acf_map_pins_create_settings_page');

function wp_acf_map_pins_create_settings_page() {
    add_options_page(
        'Map Settings',           
        'Map Settings',           
        'manage_options',         
        'acf-map-settings',       
        'wp_acf_map_pins_settings_page' 
    );
}

function wp_acf_map_pins_settings_page() {
    ?>
    <div class="wrap">
        <h1>Map Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wp_acf_map_pins_settings_group');
            do_settings_sections('acf-map-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', 'wp_acf_map_pins_scripts');

function wp_acf_map_pins_scripts($hook) {
    if ($hook !== 'settings_page_acf-map-settings') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('wp-acf-map-pins-script', plugin_dir_url(__FILE__) . 'media-uploader.js', array('jquery'), null, true);
}

add_action('admin_init',
 'wp_acf_map_pins_register_settings');

function wp_acf_map_pins_register_settings() {
    register_setting(
     'wp_acf_map_pins_settings_group',
     'parent_acf_field'
    );
    register_setting(
     'wp_acf_map_pins_settings_group',
     'repeater_acf_field'
    );
    register_setting(
     'wp_acf_map_pins_settings_group',
     'coordinates_x_field'
    );
    register_setting(
     'wp_acf_map_pins_settings_group',
     'coordinates_y_field'
    );

    register_setting('wp_acf_map_pins_settings_group', 'map_image_url');
    register_setting('wp_acf_map_pins_settings_group', 'existing_pins_image_url');
    register_setting('wp_acf_map_pins_settings_group', 'new_pin_image_url');

    add_settings_section(
        'wp_acf_map_pins_section',
        'WP AFC Map Pins Settings',
        null,
        'acf-map-settings'
    );

    // add_settings_field(
    //     'parent_acf_field',
    //     'Parent ACF Field Name',
    //     'wp_acf_map_pins_text_callback',
    //     'acf-map-settings',
    //     'wp_acf_map_pins_section',
    //     ['label_for' => 'parent_acf_field']
    // );
    add_settings_field(
        'repeater_acf_field',
        'Repeater ACF Field Name',
        'wp_acf_map_pins_text_callback',
        'acf-map-settings',
        'wp_acf_map_pins_section',
        ['label_for' => 'repeater_acf_field']
    );
    add_settings_field(
        'coordinates_x_field',
        'Coordinates X Field Name',
        'wp_acf_map_pins_text_callback',
        'acf-map-settings',
        'wp_acf_map_pins_section',
        ['label_for' => 'coordinates_x_field']
    );
    add_settings_field(
        'coordinates_y_field',
        'Coordinates Y Field Name',
        'wp_acf_map_pins_text_callback',
        'acf-map-settings',
        'wp_acf_map_pins_section',
        ['label_for' => 'coordinates_y_field']
    );

    add_settings_field(
        'map_image_url',
        'Map Image',
        'wp_afc_map_pins_images_callback', 
        'acf-map-settings', 
        'wp_acf_map_pins_section', 
        ['label_for' => 'map_image_url']
    );
    add_settings_field(
        'existing_pins_image_url', 'Existing Pins Image', 
        'wp_afc_map_pins_images_callback', 
        'acf-map-settings', 
        'wp_acf_map_pins_section', 
        ['label_for' => 'existing_pins_image_url']
    );
    add_settings_field('new_pin_image_url', 
        'New Pin Image', 
        'wp_afc_map_pins_images_callback', 
        'acf-map-settings', 
        'wp_acf_map_pins_section', 
        ['label_for' => 'new_pin_image_url']
    );
}

function wp_acf_map_pins_text_callback($args) {
    $option = get_option($args['label_for']);
    echo "<input type='text' id='" . esc_attr($args['label_for']) . "' name='" . esc_attr($args['label_for']) . "' value='" . esc_attr($option) . "' />";
}

function wp_afc_map_pins_images_callback($args) {
    $option = get_option($args['label_for']);
    ?>
    <div class="image-preview" id="preview-<?php echo esc_attr($args['label_for']); ?>" style="margin-bottom: 10px;">
        <img id="img-<?php echo esc_attr($args['label_for']); ?>" src="<?php echo esc_url($option); ?>" style="max-width: 768px; max-height: 480px; height: auto; border: 1px solid black; padding: 16px; <?php echo $option ? '' : 'display:none;'; ?>" alt="Image Preview" />
        <?php if (!$option): ?>
            <p>No image selected. Please upload an image.</p>
        <?php endif; ?>
    </div>
    <input type="hidden" id="input-<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['label_for']); ?>" value="<?php echo esc_attr($option); ?>" />
    <button class="button upload-image-button" data-target="<?php echo esc_attr($args['label_for']); ?>">Upload Image</button>
    <?php
}

// $parent_field   = get_option('parent_acf_field');


add_action('acf/input/admin_footer', 'add_map_click_handler');
function add_map_click_handler() {
    $repeater_field                     = get_option('repeater_acf_field');
    $coordinates_x                      = get_option('coordinates_x_field');
    $coordinates_y                      = get_option('coordinates_y_field');
    $user_defined_image_map             = get_option('map_image_url');
    $user_defined_image_existing_pins   = get_option('existing_pins_image_url');
    $user_defined_image_new_pin         = get_option('new_pin_image_url');
    $map_image_url                      = !empty($user_defined_image_map) ? $user_defined_image_map             : plugins_url('img/Strangereal_Map_AC7.webp', __FILE__);
    $pin_image_url                      = !empty($user_defined_image_map) ? $user_defined_image_existing_pins   : plugins_url('img/pin.png', __FILE__);
    $pin_new_image_url                  = !empty($user_defined_image_map) ? $user_defined_image_new_pin         : plugins_url('img/pin_new.png', __FILE__);

    ?>
    <style>
        .map-container{
            position: relative;
            margin: 20px;
            border: 1px solid black;
        }
        .map-container img{
            display: block;
        }
        .marker{
            position: absolute;
            width: 2.5%;
            min-width: 15px;
            aspect-ratio: 3/4;
            transform: translateX(-50%) translateY(-100%);
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            pointer-events: none;
        }
        .marker_existing{
            background-image: url('<?php echo esc_url($pin_image_url); ?>');
        }
        .marker_new{
            background-image: url('<?php echo esc_url($pin_new_image_url); ?>');
        }
    </style>
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                var repeaterFieldName = <?php echo !empty($repeater_field) ? json_encode($repeater_field) : 'map'; ?>;
                var mapHtml =   '<div id="wp-acf-map-container" class="map-container">'                                                +
                                    '<img src="<?php echo esc_url($map_image_url); ?>" alt="Map" style="width: 100%; height: auto;">'+
                                '</div>'                                                                                             +
                                '<div id="coords-display" style="font-weight: bold;text-align:center"></div>'                        +
                $('.acf-field-repeater[data-name="' + repeaterFieldName + '"]').append(mapHtml);

                function createExistingMarkers() {
                    var rows = $('.acf-field-repeater[data-name="' + repeaterFieldName + '"] .acf-row:not(.acf-clone)');

                    rows.each(function() {
                        var xCoord = $(this).find('div[data-name="<?php echo $coordinates_x; ?>"] input').val();
                        var yCoord = $(this).find('div[data-name="<?php echo $coordinates_y; ?>"] input').val();

                        if (xCoord && yCoord) {
                            var xPercent = xCoord || 0; 
                            var yPercent = yCoord || 0;

                            var marker = $('<div class="marker marker_existing"></div>');
                            marker.css({
                                left: xPercent+'%',
                                top: yPercent+'%'
                            });
                            $('.map-container').last().append(marker);
                        }
                    });
                }
                createExistingMarkers();

                $('#wp-acf-map-container').on('click', function(e) {
                    var container = $(this);
                    var containerWidth = container.width();
                    var containerHeight = container.height();
                    var x = e.offsetX;
                    var y = e.offsetY;
                    var xPercent = (x / containerWidth) * 100;
                    var yPercent = (y / containerHeight) * 100;

                    var repeaterFieldName = <?php echo !empty($repeater_field) ? json_encode($repeater_field) : 'map'; ?>;
                    var lastRow = $('.acf-field-repeater[data-name="' + repeaterFieldName + '"] .acf-row:not(.acf-clone)').last();

                    lastRow.find('div[data-name="<?php echo $coordinates_x; ?>"] input').val(xPercent.toFixed(2));
                    lastRow.find('div[data-name="<?php echo $coordinates_y; ?>"] input').val(yPercent.toFixed(2));

                    $('#coords-display').text('X: ' + xPercent.toFixed(2) + ', Y: ' + yPercent.toFixed(2));
                    updateMarker(xPercent, yPercent);
                });

                function updateMarker(xPercent, yPercent) {
                    $('.marker_new').remove();

                    var marker = $('<div class="marker marker_new"></div>');
                    marker.css({
                        left: xPercent + '%',
                        top: yPercent + '%'
                    });

                    $('#wp-acf-map-container').append(marker);
                }
            });
        })(jQuery);
    </script>
<?php
}