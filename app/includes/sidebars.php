<?php
// no direct access
defined('ABSPATH') or die();

if (!class_exists('WPEW_Sidebars')) :

/**
 * Sidebars Class
 * 
 * @class WPEW_Sidebars
 * @version 1.0.0
 */
class WPEW_Sidebars extends WPEW_Base
{
    /**
     * Constructor method
     */
    public function __construct()
    {
    }

    public function init()
    {
        add_action('widgets_init', array($this, 'register'));
    }

    public function register()
    {
        register_sidebar(array(
            'name' => __('WP Embed Widgets', 'wp-embed-widgets'),
            'id' => WPEW_Base::WPEW_Sidebar,
            'description' => __('This is a hidden sidebar so you can put the widgets that you want to embed here and then put the shortcode inside of your post / page!', 'wp-embed-widgets'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => ''
        ));
    }
}

endif;
