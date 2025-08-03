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

use PodloveSubscribeButton\Utils\Constants;

 if (!function_exists('podlove_log_with_stack_trace')) {
    function podlove_log_with_stack_trace($message) {
        // Basis-Lognachricht
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;

        // Stack-Trace abrufen
        $backtrace = debug_backtrace();
        $stackTrace = '';

        foreach ($backtrace as $key => $trace) {
            $file = isset($trace['file']) ? $trace['file'] : '[No File]';
            $line = isset($trace['line']) ? $trace['line'] : '[No Line]';
            $function = isset($trace['function']) ? $trace['function'] : '[No Function]';

            $stackTrace .= "#{$key} {$file} ({$line}): {$function}()" . PHP_EOL;
        }

        // Stack-Trace zur Lognachricht hinzufügen
        $logMessage .= "Stack Trace:" . PHP_EOL . $stackTrace . PHP_EOL;

        // In die PHP-Error-Log schreiben
        error_log($logMessage);
    }
}

if (!function_exists('podlove_log_without_stack_trace')) {
    function podlove_log_without_stack_trace($message) {
        // Basis-Lognachricht
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        // In die PHP-Error-Log schreiben
        error_log($logMessage);
    }
}

function load_podlove_subscribe_button()
{
    require_once __DIR__ . '/vendor/autoload.php'; // composer

    // Constants
    Constants::init(__FILE__);

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
