<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_File')):

/**
 * File Class.
 *
 * @class WPEW_File
 * @version	1.0.0
 */
class WPEW_File extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function read($path)
    {
        return file_get_contents($path);
    }

    public static function exists($path)
    {
        return file_exists($path);
    }

    public static function write($path, $content)
    {
        return file_put_contents($path, $content);
    }
}

endif;