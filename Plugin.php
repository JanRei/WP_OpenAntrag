<?php
/**
 * Created by PhpStorm.
 * User: jochenschaefer
 * Date: 15.08.14
 * Time: 17:23
 */

namespace WP_OpenAntrag;


class Plugin
{
    const API_HOST = 'http://openantrag.de/api';

    public static function init()
    {
        if(!self::$initiated){
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks()
    {
        self::$initiated = true;
    }


    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation()
    {
        if(version_compare(phpversion(), WP_OPENANTRAG__MINIMUM_PHP_VERSION, '<')) {
            load_plugin_textdomain('wp_openantrag');

            $message = '<strong>' . sprintf(esc_html__('WP_OpenAntrag %s requires PHP %s or higher.','wp_openantrag'), WP_OPENANTRAG_VERSION, WP_OPENANTRAG__MINIMUM_PHP_VERSION) . '</strong> '
                . sprintf(__('Please <a target="_blank" href="%1$s">upgrade PHP</a> to a current version</a>.','wp_openantrag'),'http://www.php.net');

            self::bail_on_activation($message);
            exit;
        }
        if(version_compare($GLOBALS['wp_version'], WP_OPENANTRAG__MINIMUM_WP_VERSION,'<')){
            load_plugin_textdomain('wp_openantrag');

            $message = '<strong>' . sprintf(esc_html__('WP_OpenAntrag %s requires WordPress %s or higher.','wp_openantrag'), WP_OPENANTRAG_VERSION, WP_OPENANTRAG__MINIMUM_WP_VERSION) . '</strong> '
                . sprintf(__('Please <a target="_blank" href="%1$s">upgrade WordPress</a> to a current version</a>.','wp_openantrag'),'https://codex.wordpress.org/Upgrading_WordPress');

            self::bail_on_activation($message);
        }
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation()
    {
        //tidy up
    }

    public static function log($debug)
    {
        if(defined('WP_DEBUG_LOG') && WP_DEBUG_LOG)
            error_log(print_r(compact('wp_openantrag_debug'),1)); //send message to debug.log when in debug mode
    }

    private static function bail_on_activation( $message, $deactivate = true ) {
?>
<!doctype html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <style>
        * {
            text-align: center;
            margin: 0;
            padding: 0;
            font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
        }
        p {
            margin-top: 1em;
            font-size: 18px;
        }
    </style>
<body>
<p><?php echo $message; ?></p>
</body>
</html>
<?php
        if ( $deactivate ) {
            $plugins = get_option( 'active_plugins' );
            $openantrag = plugin_basename( WP_OPENANTRAG__PLUGIN_DIR . 'akismet.php' );
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