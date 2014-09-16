<?php
/*
Plugin Name: WP_OpenAntrag
Plugin URI: http://github.com
Description: Display OpenAntrag
Version: 0.1
Author: Jochen Sch&auml;fer
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

namespace WP_OpenAntrag;


class Plugin
{
    const API_HOST = 'http://openantrag.de/api';

    public static function init() {
        if(!self::$initiated) {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks() {
        self::$initiated = true;
    }


    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation() {
        if(version_compare(phpversion(), WP_OPENANTRAG__MINIMUM_PHP_VERSION, '<')) {
            load_plugin_textdomain('wp_openantrag');

            $message = '<strong>'
                . sprintf(esc_html__('WP_OpenAntrag %s ben&ouml;tigt PHP %s oder h&ouml;her.','wp_openantrag'), WP_OPENANTRAG_VERSION, WP_OPENANTRAG__MINIMUM_PHP_VERSION)
                . '</strong><br/>'
                . sprintf(__('Installieren Sie bitte eine aktuelle Version von <a target="_blank" href="%1$s">PHP</a>.','wp_openantrag'),'http://www.php.net');

            self::bail_on_activation($message);
            exit;
        }
        if(version_compare($GLOBALS['wp_version'], WP_OPENANTRAG__MINIMUM_WP_VERSION,'<')){
            load_plugin_textdomain('wp_openantrag');

            $message = '<strong>'
                . sprintf(esc_html__('WP_OpenAntrag %s ben&ouml;tigt WordPress %s oder h&ouml;her.','wp_openantrag'), WP_OPENANTRAG_VERSION, WP_OPENANTRAG__MINIMUM_WP_VERSION)
                . '</strong><br/>'
                . sprintf(__('Installieren Sie bitte eine aktuelle Version von <a target="_blank" href="%1$s">WordPress</a>.','wp_openantrag'),'https://codex.wordpress.org/Upgrading_WordPress');

            self::bail_on_activation($message);
        }
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation() {
        //tidy up
    }

    public static function log($debug) {
        if(defined('WP_DEBUG_LOG') && WP_DEBUG_LOG)
            error_log(print_r(compact('wp_openantrag_debug'),1)); //send message to debug.log when in debug mode
    }

    public static function bail_on_activation( $message, $deactivate = true ) {
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'plugin_bailout.php';

        if ( $deactivate ) {
            $plugins = get_option( 'active_plugins' );
            $openantrag = plugin_basename( WP_OPENANTRAG__PLUGIN_DIR . DIRECTORY_SEPARATOR .'plugin.php' );
            $update  = false;
            foreach ( $plugins as $i => $plugin ) {
                if ( $plugin === $openantrag ) {
                    $plugins[$i] = false;
                    $update = true;
                }
            }

            if ( $update ) {
                update_option( 'active_plugins', array_filter( $plugins ) );
            }
        }
        exit;
    }

} 