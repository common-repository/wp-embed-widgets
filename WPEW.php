<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEmbedWidgets')):

/**
 * Main WPEmbedWidgets Class.
 *
 * @class WPEmbedWidgets
 * @version	1.0.0
 */
final class WPEmbedWidgets
{
    /**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';
    
    /**
	 * The single instance of the class.
	 *
	 * @var WPEmbedWidgets
	 * @since 1.0.0
	 */
	protected static $instance = null;
    
    /**
	 * Main WPEmbedWidgets Instance.
	 *
	 * Ensures only one instance of WPEmbedWidgets is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WPEmbedWidgets()
	 * @return WPEmbedWidgets - Main instance.
	 */
	public static function instance()
    {
        // Get an instance of Class
		if(is_null(self::$instance)) self::$instance = new self();
        
        // Return the instance
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone()
    {
		_doing_it_wrong(__FUNCTION__, __('Cheating huh?', 'wp-embed-widgets'), '1.0.0');
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0.0
	 */
	public function __wakeup()
    {
		_doing_it_wrong(__FUNCTION__, __('Cheating huh?', 'wp-embed-widgets'), '1.0.0');
	}
    
    /**
	 * WPEmbedWidgets Constructor.
	 */
	protected function __construct()
    {
        // Define Constants
        $this->define_constants();
        
        // Auto Loader
        spl_autoload_register(array($this, 'autoload'));
        
        // Initialize the Plugin
        $this->init();

        // Include Helper Functions
        $this->helpers();
        
        // Do wpew_loaded action
		do_action('wpew_loaded');
	}
    
    /**
	 * Define Plugin Constants.
	 */
	private function define_constants()
    {
        // Plugin Absolute Path
        if(!defined('WPEW_ABSPATH')) define('WPEW_ABSPATH', dirname(__FILE__));

        // Plugin Directory Name
        if(!defined('WPEW_DIRNAME')) define('WPEW_DIRNAME', basename(WPEW_ABSPATH));

        // Plugin Plugin Base Name
        if(!defined('WPEW_BASENAME')) define('WPEW_BASENAME', plugin_basename(WPEW_ABSPATH.'/wp-embed-widgets.php')); // wp-embed-widgets/wp-embed-widgets.php

        // Plugin Version
        if(!defined('WPEW_VERSION')) define('WPEW_VERSION', $this->version);

        // WordPress Upload Directory
		$upload_dir = wp_upload_dir();

		// Plugin Logs Directory
        if(!defined('WPEW_LOG_DIR')) define('WPEW_LOG_DIR', $upload_dir['basedir'] . '/wpew-logs/');
	}
    
    /**
     * Initialize the Plugin
     */
    private function init()
    {
        // Plugin Activation / Deactivation / Uninstall
        WPEW_Plugin_Hooks::instance();
        
        // WPEmbedWidgets Actions / Filters
        $Hooks = new WPEW_Hooks();
        $Hooks->init();

        // WPEmbedWidgets Assets
        $Assets = new WPEW_Assets();
        $Assets->init();

        // WPEmbedWidgets Shortcodes
        $Shortcodes = new WPEW_Shortcodes();
        $Shortcodes->init();

        // WPEmbedWidgets Internationalization
        $i18n = new WPEW_i18n();
        $i18n->init();

        // WPEmbedWidgets AJAX
        $Ajax = new WPEW_Ajax();
        $Ajax->init();

        // WPEmbedWidgets Sidebars
        $Sidebars = new WPEW_Sidebars();
        $Sidebars->init();

        // WPEmbedWidgets TinyMCE
        $TinyMCE = new WPEW_TinyMCE();
        $TinyMCE->init();

        // WPEmbedWidgets Block Editor
        $Blocks = new WPEW_Blocks();
        $Blocks->init();

        // Flush WordPress rewrite rules only if needed
        WPEW_RewriteRules::flush();
    }

    /**
     * Include Helper Functions
     */
    public function helpers()
    {
    }
    
    /**
     * Automatically load WPEmbedWidgets classes whenever needed.
     * @param string $class_name
     * @return void
     */
    private function autoload($class_name)
    {
        $class_ex = explode('_', strtolower($class_name));
        
        // It's not a WPEmbedWidgets Class
        if($class_ex[0] != 'wpew') return;
        
        // Drop 'WPEW'
        $class_path = array_slice($class_ex, 1);
        
        // Create Class File Path
        $file_path = WPEW_ABSPATH . '/app/includes/' . implode('/', $class_path) . '.php';
        
        // We found the class!
        if(file_exists($file_path)) require_once $file_path;
    }
    
    /**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	public function is_request($type)
    {
		switch($type)
        {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined('DOING_AJAX');
			case 'cron':
				return defined('DOING_CRON');
			case 'frontend':
				return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
            default:
                return false;
		}
	}
}

endif;

/**
 * Main instance of WPEmbedWidgets.
 *
 * Returns the main instance of WPEmbedWidgets to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WPEmbedWidgets
 */
function wpembedwidgets()
{
	return WPEmbedWidgets::instance();
}

// Init the Plugin :)
wpembedwidgets();