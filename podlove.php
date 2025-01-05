<?php
/**
 * Plugin Name: Podlove Subscribe Button
 * Plugin URI:  https://wordpress.org/extend/plugins/podlove-subscribe-button/
 * Description: Brings the Podlove Subscribe Button to your WordPress installation.
 * Version:     1.3.11
 * Author:      Podlove
 * Author URI:  https://podlove.org
 * License:     MIT
 * License URI: license.txt
 * Text Domain: podlove-subscribe-button
 * Domain Path: /languages
 */

function load_podlove_subscribe_button()
{
    require_once __DIR__ . '/vendor/autoload.php'; // composer

    // Constants
    require_once __DIR__ . '/includes/Utils/constants.php';
    // Version control
    require_once __DIR__ . '/includes/Utils/Version.php';
    // Helper functions
    require_once __DIR__ . '/includes/Utils/helper.php';

    // Plugin Initalisierung
    require_once __DIR__ . '/plugin.php';

}

$correct_php_version = version_compare( phpversion(), "7.4", ">=" );

if ( ! $correct_php_version ) {
    printf( __( 'Podlove Subscribe Button Plugin requires %s or higher.<br>', 'podlove-subscribe-button' ), '<code>PHP 7.4</code>' );
    echo '<br />';
    printf( __( 'You are running %s', 'podlove-subscribe-button' ), '<code>PHP ' . phpversion() . '</code>' );
    exit;
}

load_podlove_subscribe_button();
