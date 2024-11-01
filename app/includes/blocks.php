<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Blocks')):

/**
 * WPEW_Blocks Class
 *
 * @class WPEW_Blocks
 * @version 1.0.0
 */
class WPEW_Blocks extends WPEW_Base
{
    /**
     * Constructor method
     */
    public function __construct()
    {
    }

    public function init()
    {
        if(function_exists('register_block_type')) add_action('init', array($this, 'register'));
    }

    public function register()
    {
        // Register New Block Editor
        register_block_type('wpew/blockeditor', array(
            'editor_script' => 'wpew_block',
            'render_callback' => function($args)
            {
                return (trim($args['id']) == 'undefined') ? __('Please select an option from widgets!', 'wp-embed-widgets') : $this->widgetize($args);
            }, 'attributes' => array(
                'id' => array(
                    'default' => 'undefined'
                ),
            )
        ));
    }
}

endif;