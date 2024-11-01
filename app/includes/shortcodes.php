<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Shortcodes')):

/**
 * Shortcodes Class.
 *
 * @class WPEW_Shortcodes
 * @version	1.0.0
 */
class WPEW_Shortcodes extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}
    
    public function init()
    {
        // Add shortcode to the widgets
        add_action('in_widget_form', array($this, 'shortcode_input'));

        $Widget = new WPEW_Shortcodes_Widget();
        $Widget->init();
    }

    /**
     * Append a new fields to last widget form
     *
     * @author Totalery <info@totalery.biz>
     * @param object $widget
     * @return void
     */
    public function shortcode_input($widget)
    {
        $field = __('Embed Widget', 'wp-embed-widgets') . ' : ';

        if(is_int($widget->number)) $field .= '<input type="text" class="widefat wpew-shortcode" readonly="readonly" value="'.esc_attr('[wpew-widget id="'.$widget->id.'"]').'" style="cursor: copy;" >';
        else $field .= __('Please save widget first.', 'wp-embed-widgets');

        echo '<p>'.$field.'</p>';
    }
}

endif;