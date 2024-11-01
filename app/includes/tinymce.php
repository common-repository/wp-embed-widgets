<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_TinyMCE')):

/**
 * WPEW_TinyMCE Class
 *
 * @class WPEW_TinyMCE
 * @version 1.0.0
 */
class WPEW_TinyMCE extends WPEW_Base
{
    /**
     * Constructor method
     */
    public function __construct()
    {
    }

    public function init()
    {
        add_filter('mce_buttons', array($this, 'buttons'));
        add_filter('mce_external_plugins', array($this, 'external_plugins'));
        add_action('admin_enqueue_scripts', array($this, 'localize_script'));
    }

    public function buttons($buttons)
    {
        array_push($buttons, 'wpew_mce_button');
        return $buttons;
    }

    public function external_plugins($plugins)
    {
        $plugins['wpew_mce_button'] = $this->wpew_asset_url('js/backend.min.js');
        return $plugins;
    }

    public function localize_script()
    {
        // Append json output value for tinymce and etc
        wp_localize_script('wpew-admin-script','wpew', array(
            'widgets' => $this->JSON_widgets()
        ));
    }
}

endif;