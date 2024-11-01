<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Form')):

/**
 * Form Class.
 *
 * @class WPEW_Form
 * @version	1.0.0
 */
class WPEW_Form extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function label($args = array())
    {
        if(!count($args)) return false;

        return '<label for="'.(isset($args['for']) ? esc_attr($args['for']) : '').'" class="'.(isset($args['class']) ? esc_attr($args['class']) : '').'">'.$args['title'].'</label>';
    }

    public static function text($args = array())
    {
        if(!count($args)) return false;
        return self::input($args, 'text');
    }

    public static function separator($args = array())
    {
        if(!count($args)) return false;
        return '<div class="wpew-separator">'.(isset($args['label']) ? $args['label'] : '').'</div>';
    }

    public static function input($args = array(), $type = 'text')
    {
        if(!count($args)) return false;
        return '<input type="'.esc_attr($type).'" name="'.esc_attr($args['name']).'" id="'.(isset($args['id']) ? esc_attr($args['id']) : '').'" value="'.(isset($args['value']) ? esc_attr($args['value']) : '').'" placeholder="'.(isset($args['placeholder']) ? esc_attr($args['placeholder']) : '').'" />';
    }

    public static function select($args = array())
    {
        if(!count($args)) return false;

        $options = '';
        foreach($args['options'] as $value=>$label) $options .= '<option value="'.esc_attr($value).'" '.((isset($args['value']) and trim($args['value']) != '' and $args['value'] == $value) ? 'selected="selected"' : '').'>'.$label.'</option>';

        $attributes = '';
        if(isset($args['attributes']) and is_array($args['attributes']) and count($args['attributes']))
        {
            foreach($args['attributes'] as $key=>$value) $attributes .= $key.'="'.esc_attr($value).'" ';
        }

        return '<select name="'.esc_attr($args['name']).'" id="'.(isset($args['id']) ? esc_attr($args['id']) : '').'" class="'.(isset($args['class']) ? esc_attr($args['class']) : '').'" '.trim($attributes).'>
            '.$options.'                       
        </select>';
    }

    public static function switcher($args = array())
    {
        if(!count($args)) return false;
        $toggle = isset($args['toggle']) ? true : false;

        return '<label class="wpew-switch">
            <input type="hidden" name="'.esc_attr($args['name']).'" value="0">
            <input type="checkbox" id="'.esc_attr($args['id']).'" name="'.esc_attr($args['name']).'" value="1" '.((isset($args['value']) and trim($args['value']) != '' and $args['value'] == 1) ? 'checked="checked"' : '').'>
            <span class="wpew-slider '.($toggle ? 'wpew-toggle' : '').'" '.($toggle ? 'data-for="'.esc_attr($args['toggle']).'"' : '').'></span>
        </label>';
    }

    public static function textarea($args = array())
    {
        if(!count($args)) return false;
        return '<textarea name="'.esc_attr($args['name']).'" id="'.(isset($args['id']) ? esc_attr($args['id']) : '').'">'.(isset($args['value']) ? $args['value'] : '').'</textarea>';
    }

    public static function colorpicker($args)
    {
        if(!count($args)) return false;

        return '<input type="text" name="'.esc_attr($args['name']).'" id="'.(isset($args['id']) ? esc_attr($args['id']) : '').'" class="'.(isset($args['class']) ? esc_attr($args['class']) : 'wpew-colorpicker').'" value="'.(isset($args['value']) ? esc_attr($args['value']) : '').'" data-default-color="'.(isset($args['default']) ? esc_attr($args['default']) : '').'" />';
    }

    public static function hidden($args = array())
    {
        if(!count($args)) return false;
        return self::input($args, 'hidden');
    }

    public static function pages($args = array())
    {
        if(!count($args)) return false;

        // Get WordPress Pages
        $pages = get_pages();

        $options = array();
        foreach($pages as $page) $options[$page->ID] = $page->post_title;

        $args['options'] = $options;

        // Dropdown Field
        return self::select($args);
    }

    public static function submit($args = array())
    {
        return '<button type="submit" id="'.(isset($args['id']) ? esc_attr($args['id']) : '').'" class="'.(isset($args['class']) ? esc_attr($args['class']) : 'button button-primary').'">'.$args['label'].'</button>';
    }

    public static function nonce($action, $name = '_wpnonce')
    {
        wp_nonce_field($action, $name);
    }
}

endif;