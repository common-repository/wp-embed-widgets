<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Shortcodes_Widget')):

/**
 * Widget Shortcode Class.
 *
 * @class WPEW_Shortcodes_Widget
 * @version	1.0.0
 */
class WPEW_Shortcodes_Widget extends WPEW_Shortcodes
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public function init()
    {
        add_shortcode('wpew-widget', array($this, 'widgetize'));
	}
}

endif;