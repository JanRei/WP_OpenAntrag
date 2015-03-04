<?php
/*
Plugin Name: WP_OpenAntrag
Plugin URI: http://github.com/josch1710
Description: Display OpenAntrag
Version: 0.2
Author: Jochen Sch&auml;fer; Jan Reinighaus
Author URI: http://www.github.com/josch1710
License: GPLv2
Text Domain: wp_openantrag
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if(!function_exists('add_action')){
    echo __('Ich bin nur ein Plugin. Es macht keinen Sinn, mich direkt aufzurufen', 'wp_openantrag');
    exit;
}

define('WP_OPENANTRAG_VERSION','0.2');
define('WP_OPENANTRAG__MINIMUM_PHP_VERSION', '5.3');
define('WP_OPENANTRAG__MINIMUM_WP_VERSION','3.9');
define('WP_OPENANTRAG__PLUGIN_URL',plugin_dir_url(__FILE__));
define('WP_OPENANTRAG__PLUGIN_DIR',plugin_dir_path(__FILE__));
define('WP_OPENANTRAG__DELETE_LIMIT',100000);
define('WP_OPENANTRAG__CACHE_TIME',30*MINUTE_IN_SECONDS);

register_activation_hook(__FILE__, function() { \WP_OpenAntrag\Plugin::plugin_activation(); });
register_deactivation_hook(__FILE__, function() { \WP_OpenAntrag\Plugin::plugin_deactivation(); });

require_once(WP_OPENANTRAG__PLUGIN_DIR . 'Plugin.php');
require_once(WP_OPENANTRAG__PLUGIN_DIR . 'Widget.php');
require_once(WP_OPENANTRAG__PLUGIN_DIR . 'shortcode.php');