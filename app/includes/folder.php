<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Folder')):

/**
 * Folder Class.
 *
 * @class WPEW_Folder
 * @version	1.0.0
 */
class WPEW_Folder extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function files($path, $filter = '.')
    {
        // Path doesn't exists
        if(!self::exists($path)) return false;

        $files = array();
        if($handle = opendir($path))
        {
            while(false !== ($entry = readdir($handle)))
            {
                if($entry == '.' or $entry == '..' or is_dir($entry)) continue;
                if(!preg_match("/$filter/", $entry)) continue;

                $files[] = $entry;
            }

            closedir($handle);
        }

        return $files;
    }

    public static function exists($path)
    {
        return is_dir($path);
    }
}

endif;