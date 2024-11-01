<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Options')):

/**
 * Main Class.
 *
 * @class WPEW_Options
 * @version	1.0.0
 */
class WPEW_Options extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function settings()
    {
        return self::parse_args(
            get_option('wpew_settings', array()),
            self::defaults('settings')
        );
	}

    public static function styles()
    {
        return self::parse_args(
            get_option('wpew_styles', array()),
            self::defaults('styles')
        );
    }

    public static function defaults($option = 'settings')
    {
        switch($option)
        {
            case 'styles';

                return array('CSS' => '');
                break;

            default:

                return array(
                    'option1' => 'value1',
                    'option2' => 6,
                );
        }
    }
}

endif;