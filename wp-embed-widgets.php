<?php
/**
 * Plugin Name: WP Embed Widgets
 * Description: A plugin to display wordpress widgets in posts and pages using a simple shortcode!
 * Version: 1.0.0
 * Author: Totalery
 * Author URI: https://totalery.com/
 * Requires at least: 4.0.0
 * Requires PHP: 5.4
 * Tested up to: 6.0
 *
 * Text Domain: wp-embed-widgets
 * Domain Path: /i18n/languages/
 */

// Initialize the Plugin or not?!
$init = true;

// Check Minimum PHP version
if(version_compare(phpversion(), '5.3.10', '<'))
{
    $init = false;

    add_action('user_admin_notices', 'wpew_admin_notice_php_min_version');
    function wpew_admin_notice_php_min_version()
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo sprintf(__("%s needs at-least PHP 5.3.10 or higher while your server PHP version is %s. Please contact your host provider and ask them to upgrade PHP of your server or change your host provider completely.", 'wp-embed-widgets'), '<strong>WP Boilerplate</strong>', '<strong>'.phpversion().'</strong>'); ?></p>
        </div>
        <?php
    }
}

// Check Minimum WP version
global $wp_version;
if(version_compare($wp_version, '4.0.0', '<'))
{
    $init = false;

    add_action('user_admin_notices', 'wpew_admin_notice_wp_min_version');
    function wpew_admin_notice_wp_min_version()
    {
        global $wp_version;
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo sprintf(__("%s needs at-least WordPress 4.0.0 or higher while your WordPress version is %s. Please update your WordPress to latest version first.", 'wp-embed-widgets'), '<strong>WP Boilerplate</strong>', '<strong>'.$wp_version.'</strong>'); ?></p>
        </div>
        <?php
    }
}

// Run the Plugin
if($init) require_once 'WPEW.php';