<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Assets')):

/**
 * Assets Class.
 *
 * @class WPEW_Assets
 * @version	1.0.0
 */
class WPEW_Assets extends WPEW_Base
{
    /**
     * @static
     * @var array
     */
    public static $params = array();

    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}
    
    public function init()
    {
        // Include needed assets (CSS, JavaScript etc) in the WordPress backend
        add_action('admin_enqueue_scripts', array($this, 'admin'), 0);
        
        // Include needed assets (CSS, JavaScript etc) in the WordPress frontend
        add_action('wp_enqueue_scripts', array($this, 'site'), 0);

        // Register function to be called in WordPress footer hook
        add_action('wp_footer', array($this, 'load_footer'), 9999);
    }
    
    public function site()
    {
        // Include Other Assets
        do_action('wpew_site_assets');
    }
    
    public function admin()
    {
        // Backend Dependencies
        $dependencies = array('jquery');

        // Get current screen
        $current_screen = get_current_screen();

        // Add WP Blocks to the dependencies only when needed!
        if(method_exists($current_screen, 'is_block_editor') and $current_screen->is_block_editor()) $dependencies[] = 'wp-blocks';

        // Include backend script file
        wp_enqueue_script('wpew-admin-script', $this->wpew_asset_url('js/backend.min.js'), $dependencies, false, true);

        // Include Other Assets
        do_action('wpew_admin_assets');
    }

    public static function footer($string)
    {
        return self::params($string, 'footer');
    }

    public static function params($string, $key = 'footer')
    {
        $string = (string) $string;
        if(trim($string) == '') return false;

        // Register the key for removing PHP notices
        if(!isset(self::$params[$key])) self::$params[$key] = array();

        // Add it to the params
        array_push(self::$params[$key], $string);
    }

    public function load_footer()
    {
        if(!isset(self::$params['footer']) or (isset(self::$params['footer']) and !count(self::$params['footer']))) return;

        // Remove duplicate strings
        $strings = array_unique(self::$params['footer']);

        // Print the assets in the footer
        foreach($strings as $string) echo PHP_EOL.$string.PHP_EOL;
    }
}

endif;