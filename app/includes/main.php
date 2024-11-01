<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('WPEW_Main')):

/**
 * Main Class.
 *
 * @class WPEW_Main
 * @version	1.0.0
 */
class WPEW_Main extends WPEW_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

	public function get_installed_db_version()
    {
        $installed_db_ver = get_option('wpew_db_version');
        if(trim($installed_db_ver) == '') $installed_db_ver = 0;

        return $installed_db_ver;
    }

    public function is_db_update_required()
    {
        $installed_db_ver = $this->get_installed_db_version();
        return version_compare($installed_db_ver, WPEW_Base::DB_VERSION, '<');
    }
}

endif;