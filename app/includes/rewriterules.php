<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_RewriteRules')):

/**
 * Rewrite Rules Class.
 *
 * @class WPEW_RewriteRules
 * @version	1.0.0
 */
class WPEW_RewriteRules extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function todo()
    {
        update_option('wpew_todo_flush', 1);
	}

    public static function flush()
    {
        // if flush is not needed
        if(!get_option('wpew_todo_flush', 0)) return;

        // Perform the flush on WordPress init hook
        add_action('init', array('WPEW_RewriteRules', 'perform'));
	}

    public static function perform()
    {
        // Flush the rules
        global $wp_rewrite;
        $wp_rewrite->flush_rules(false);

        // remove the to do
        delete_option('wpew_todo_flush');
	}
}

endif;