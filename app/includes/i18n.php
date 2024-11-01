<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_i18n')):

/**
 * i18n Class.
 *
 * @class WPEW_i18n
 * @version	1.0.0
 */
class WPEW_i18n extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public function init()
    {
        // Register Language Files
        add_action('plugins_loaded', array($this, 'load_languages'));
	}

    public function load_languages()
    {
        // File library
        $file = new WPEW_File();

        // Get current locale
        $locale = apply_filters('plugin_locale', get_locale(), 'wp-embed-widgets');

        // WordPress language directory /wp-content/languages/wp-embed-widgets-en_US.mo
        $language_filepath = WP_LANG_DIR.'/wp-embed-widgets-'.$locale.'.mo';

        // If language file exists on WordPress language directory use it
        if($file->exists($language_filepath))
        {
            load_textdomain('wp-embed-widgets', $language_filepath);
        }
        // Otherwise use plugin directory /path/to/plugin/i18n/languages/wp-embed-widgets-en_US.mo
        else
        {
            load_plugin_textdomain('wp-embed-widgets', false, dirname(WPEW_BASENAME).'/i18n/languages/');
        }
    }
}

endif;