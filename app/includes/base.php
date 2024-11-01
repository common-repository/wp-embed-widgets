<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Base')):

/**
 * Base Class.
 *
 * @class WPEW_Base
 * @version	1.0.0
 */
class WPEW_Base
{
    const DB_VERSION = 1;
    const WPEW_Sidebar = 'wp-embed-widgets';

    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public function include_html_file($file = NULL, $args = array())
    {
        // File is empty
        if(!trim($file)) return __('HTML file is empty!', 'wp-embed-widgets');
        
        $path = $this->get_wpew_path().'/app/html/'.ltrim($file, '/');
        
        // File is not exists
        if(!file_exists($path)) return __('HTML file is not exists! Check the file path please.', 'wp-embed-widgets');
        
        // Return the File Path
        if(isset($args['return_path']) and $args['return_path']) return $path;

        // Parameters passed
        if(isset($args['parameters']) and is_array($args['parameters']) and count($args['parameters'])) extract($args['parameters']);

        // Start buffering
        ob_start();
        
        // Include Once
        if(isset($args['include_once']) and $args['include_once']) include_once $path;
        else include $path;
        
        // Get Buffer
        $output = ob_get_clean();
            
        // Return the File OutPut
        if(isset($args['return_output']) and $args['return_output']) return $output;
        
        // Print the output
        echo $output;
    }
    
    public function get_wpew_path()
    {
        return WPEW_ABSPATH;
    }
    
    public function wpew_url()
    {
        return plugins_url().'/'.WPEW_DIRNAME;
    }
    
    public function wpew_asset_url($asset)
	{
		return $this->wpew_url().'/assets/'.trim($asset, '/ ');
	}

    public function wpew_asset_path($asset)
    {
        return $this->get_wpew_path().'/assets/'.trim($asset, '/ ');
    }

    public function get_post_meta($post_id)
    {
        $raw_data = get_post_meta($post_id, '', true);

        $data = array();
        foreach($raw_data as $key=>$val) $data[$key] = isset($val[0]) ? (!is_serialized($val[0]) ? $val[0] : unserialize($val[0])) : NULL;

        return $data;
    }

    public static function alert($message, $type = 'info')
    {
        if(!trim($message)) return '';
        return '<div class="wpew-alert wpew-'.$type.'">'.$message.'</div>';
    }

    public static function parse_args($a, $b)
    {
        $a = (array) $a;
        $b = (array) $b;

        $result = $a;
        foreach($b as $k=>$v)
        {
            if(is_array($v) && isset($result[$k])) $result[$k] = self::parse_args($result[$k], $v);
            elseif(!isset($result[$k])) $result[$k] = $v;
        }

        return $result;
    }

    public function response(Array $response)
    {
        echo json_encode($response, JSON_NUMERIC_CHECK);
        exit;
    }

    /**
     * Returns sidebars widgets
     * 
     * @author Totalery <info@totalery.biz>
     * @return array
     */
    public function wpew_widgets()
    {
        global $wp_registered_widgets;
        $widgets = get_option('sidebars_widgets', array());
        $widgets_output = array();

        if($widgets)
        {
            $sidebar_widgets = (isset($widgets[self::WPEW_Sidebar]) and $widgets[self::WPEW_Sidebar]) ? $widgets[self::WPEW_Sidebar] : array();

            if($sidebar_widgets)
            {
                for($i=0; $i < count($sidebar_widgets); $i++)
                {
                    $widget_callback = current($wp_registered_widgets[$sidebar_widgets[$i]]['callback']);
                    $widget_option = get_option("widget_{$widget_callback->id_base}", array());
                    $widgets_output[] = array('id' => $sidebar_widgets[$i], 'name' => trim($wp_registered_widgets[$sidebar_widgets[$i]]['name']), 'title' => (isset($widget_option[$widget_callback->number]['title']) ? trim($widget_option[$widget_callback->number]['title']) : ''));
                }
            }
        }

        return $widgets_output;
    }

    /**
     * Executing widget shortcode
     * 
     * @author Totalery <info@totalery.biz>
     * @param array $args
     * @return string
     */
    public function widgetize($args)
    {
        global $wp_registered_widgets;

        $args = shortcode_atts(array(
            'id' => '',
            'widget-tag' => 'div',
            'widget-id' => '%1$s',
            'widget-class' => 'widget %2$s',
            'title-tag' => 'h2',
            'title-class' => 'widgettitle',
            'hide-title' => '0'
        ), $args, 'wpew-widget');

        $id = isset($args['id']) ? $args['id'] : NULL;
        $widget_tag = isset($args['widget-tag']) ? $args['widget-tag'] : NULL;
        $widget_id = isset($args['widget-id']) ? $args['widget-id'] : NULL;
        $widget_class = isset($args['widget-class']) ? $args['widget-class'] : NULL;
        $title_tag = isset($args['title-tag']) ? $args['title-tag'] : NULL;
        $title_class = isset($args['title-class']) ? $args['title-class'] : NULL;
        $hide_title = isset($args['hide-title']) ? $args['hide-title'] : '0';

        if(!trim($id) or !isset($wp_registered_widgets[$id])) return '';

        preg_match('/-(\d+)$/', $id, $number);
        $options = (!empty($wp_registered_widgets) and !empty($wp_registered_widgets[$id])) ? get_option($wp_registered_widgets[$id]['callback'][0]->option_name) : array();
        $instance = isset($options[$number[1]]) ? $options[$number[1]] : array();
        $class = get_class($wp_registered_widgets[ $id ]['callback'][0]);

        // Style Options
        $title_style = '';
        $title_style .= (isset($args['title-color']) ? "color: {$args['title-color']} !important;" : '');
        $title_style .= (isset($args['title-font-size']) ? "font-size: {$args['title-font-size']} !important;" : '');
        $title_style .= (isset($args['title-bg']) ? "background-color: {$args['title-bg']} !important;" : '');

        $before_widget = "<{$widget_tag} id={$widget_id} class={$widget_class}>";
        $after_widget = "</{$widget_tag}";
        $before_title = "<{$title_tag} class={$title_class} style='{$title_style}'>";
        $after_title = "</{$title_tag}>";

        if($hide_title == '1')
        {
            $before_title = '<!--%remove%-->' . $before_title; 
            $after_title = $after_title . '<!--%remove%-->';
        }
        
        // Sidebar Options
        $sidebars_option = array('before_widget' => $before_widget, 'after_widget' => $after_widget, 'before_title' => $before_title, 'after_title' => $after_title);

        // Widget Content
        ob_start();
        the_widget($class, $instance, $sidebars_option);
        $output = ob_get_clean();

        // Hide Title
        if($hide_title == '1') $output = preg_replace('/<!--%remove%-->.*?<!--%remove%-->/', '', $output);

        return $output;
    }

    /**
     * Return a shortcodes json values output
     * 
     * @author Totalery <info@totalery.biz>
     * @param array $widgets
     * @return string json
     */
    public function JSON_widgets($widgets = array())
    {
        if(!is_array($widgets) or (is_array($widgets) and !$widgets)) $widgets = $this->wpew_widgets();

        $return = array();
        $return['widgets'] = array();

        foreach($widgets as $widget)
        {
            $shortcode = array();
            $shortcode['value'] = trim($widget['id']);

            $name = isset($widget['name']) ? trim($widget['name']) : '';
            $title = isset($widget['title']) ? trim($widget['title']) : '';
            $shortcode['label'] = ucwords($name .(trim($title) != '' ? ': '.$title : ''));

            array_push($return['widgets'], $shortcode);
        }

        return json_encode($return);
    }
}

endif;