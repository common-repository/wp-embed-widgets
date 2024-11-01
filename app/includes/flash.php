<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Flash')):

/**
 * Flash Message Class.
 *
 * @class WPEW_Flash
 * @version	1.0.0
 */
class WPEW_Flash extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function add($message, $class = 'info')
    {
        $classes = array('error', 'info', 'success', 'warning');
        if(!in_array($class, $classes)) $class = 'info';

        $flash_messages = maybe_unserialize(get_option('wpew_flash_messages', array()));
        $flash_messages[$class][] = $message;

        update_option('wpew_flash_messages', $flash_messages);
	}

    public static function show()
    {
        $flash_messages = maybe_unserialize(get_option('wpew_flash_messages', ''));
        if(!is_array($flash_messages)) return;

        foreach($flash_messages as $class=>$messages)
        {
            foreach($messages as $message) echo '<div class="notice notice-'.$class.' is-dismissible"><p>'.$message.'</p></div>';
        }

        // Clear flash messages
        delete_option('wpew_flash_messages');
	}
}

endif;