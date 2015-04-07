<?php
/**
 * Plugin Name: Disable JSON API
 * Plugin URI: http://www.binarytemplar.com/disable-json-api
 * Description: Uses the built-in filters of the JSON REST API to disable its functionality
 * Version: 1.0
 * Author: Dave McHale
 * Author URI: http://www.binarytemplar.com
 * License: GPL2+
 */

add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');