<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Plugin_Hooks')):

/**
 * WPEWoilerplate Plugin Hooks Class.
 *
 * @class WPEW_Plugin_Hooks
 * @version	1.0.0
 */
class WPEW_Plugin_Hooks
{
    /**
	 * The single instance of the class.
	 *
	 * @var WPEW_Plugin_Hooks
	 * @since 1.0.0
	 */
	protected static $instance = null;

	public $main;
	public $db;

    /**
	 * Plugin Hooks Instance.
	 *
	 * @since 1.0.0
	 * @static
	 * @return WPEW_Plugin_Hooks
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
	 * Constructor method
	 */
	protected function __construct()
    {
        register_activation_hook(WPEW_BASENAME, array($this, 'activate'));
		register_deactivation_hook(WPEW_BASENAME, array($this, 'deactivate'));
		register_uninstall_hook(WPEW_BASENAME, array('WPEW_Plugin_Hooks', 'uninstall'));
        
        // Main Class
        $this->main = new WPEW_Main();
        
        // DB Class
        $this->db = new WPEW_db();
	}
    
    /**
     * Runs on plugin activation
     * @param boolean $network
     */
    public function activate($network = false)
	{
        $current_blog_id = get_current_blog_id();
        
        // Plugin activated only for one blog
        if(!function_exists('is_multisite') or (function_exists('is_multisite') and !is_multisite())) $network = false;
        if(!$network)
        {
            $this->install($current_blog_id);

            // Add WordPress flush rewrite rules in to do list
            WPEW_RewriteRules::todo();
            
            // Don't run rest of the function
            return;
        }

        // Plugin activated for all blogs
        $blogs = $this->db->select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
        foreach($blogs as $blog_id)
        {
            switch_to_blog($blog_id);
            $this->install($blog_id);
        }

        switch_to_blog($current_blog_id);

        // Add WordPress flush rewrite rules in to do list
        WPEW_RewriteRules::todo();
	}
    
    /**
     * Install the plugin on s certain blog
     * @param int $blog_id
     */
    public function install($blog_id = 1)
    {
        // Default Settings
        add_option('wpew_settings', array());

        // DB Update
        if($this->main->is_db_update_required())
        {
            $this->db_update();
        }
    }

    public function db_update()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wpew_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `$table_name` (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('wpew_db_version', WPEW_Base::DB_VERSION);
    }
    
    /**
     * Runs on plugin deactivation
     * @param boolean $network
     */
    public function deactivate($network = false)
	{
        /**
         * Refresh WordPress rewrite rules
         */
        flush_rewrite_rules();
	}
    
    /**
     * Runs on plugin uninstallation
     */
    public static function uninstall()
	{
        // DB Class
        $db = new WPEW_db();
        
        // Getting current blog
        $current_blog_id = get_current_blog_id();

        // Single WordPress Installation
        if(!function_exists('is_multisite') or (function_exists('is_multisite') and !is_multisite()))
        {
            self::purge($current_blog_id);

            /**
             * Refresh WordPress rewrite rules
             */
            flush_rewrite_rules();
            
            // Don't run rest of the function
            return;
        }

        // WordPress is multisite so we should purge the plugin from al blogs
        $blogs = $db->select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
        foreach($blogs as $blog_id)
        {
            switch_to_blog($blog_id);
            self::purge($blog_id);
        }
        
        // Switch back to current blog
        switch_to_blog($current_blog_id);

        /**
         * Refresh WordPress rewrite rules
         */
        flush_rewrite_rules();
	}
    
    /**
     * Remove plugin from a blog
     * @param int $blog_id
     */
    public static function purge($blog_id = 1)
    {
        // Delete the data or not!
        $delete = apply_filters('wpew_purge_options', true);

        // Plugin Deleted
        if($delete)
        {
            delete_option('wpew_settings');
            delete_option('wpew_todo_flush');
            delete_option('wpew_db_version');
        }
    }
}

endif;