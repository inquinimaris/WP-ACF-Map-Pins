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

add_action('acf/input/admin_footer', 'add_map_click_handler');
function add_map_click_handler() {
    $map_image_url = plugins_url('img/Strangereal_Map_AC7.webp', __FILE__);
    $pin_image_url = plugins_url('img/pin.png', __FILE__);
    $pin_new_image_url = plugins_url('img/pin_new.png', __FILE__);
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
                var repeaterFieldName = 'map'; 
                var mapHtml =   '<div id="wp-acf-map-container" class="map-container">'                                                +
                                    '<img src="<?php echo esc_url($map_image_url); ?>" alt="Map" style="width: 100%; height: auto;">'+
                                '</div>'                                                                                             +
                                '<div id="coords-display" style="font-weight: bold;text-align:center"></div>'                        +
                $('.acf-field-repeater[data-name="' + repeaterFieldName + '"]').append(mapHtml);

                function createExistingMarkers() {
                    var rows = $('.acf-field-repeater[data-name="' + repeaterFieldName + '"] .acf-row:not(.acf-clone)');

                    rows.each(function() {
                        var xCoord = $(this).find('div[data-name="map_marker_pos_x"] input').val();
                        var yCoord = $(this).find('div[data-name="map_marker_pos_y"] input').val();

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

                    var repeaterFieldName = 'map';
                    var lastRow = $('.acf-field-repeater[data-name="' + repeaterFieldName + '"] .acf-row:not(.acf-clone)').last();

                    lastRow.find('div[data-name="map_marker_pos_x"] input').val(xPercent.toFixed(2));
                    lastRow.find('div[data-name="map_marker_pos_y"] input').val(yPercent.toFixed(2));

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