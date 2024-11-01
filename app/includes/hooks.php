<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Hooks')):

/**
 * General Hooks Class.
 *
 * @class WPEW_Hooks
 * @version	1.0.0
 */
class WPEW_Hooks extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}
    
    public function init()
    {
        // Register Actions
        $this->actions();

        // Register Filters
        $this->filters();
    }

    public function actions()
    {
        add_action('admin_notices', array('WPEW_Flash', 'show'));
    }

    public function filters()
    {
    }
}

endif;