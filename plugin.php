<?php

namespace PodloveSubscribeButton;
use PodloveSubscribeButton\PodloveSubscribeButton;

add_action( 'admin_menu', array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'admin_menu') );
if ( is_multisite() )
    add_action( 'network_admin_menu', array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'admin_network_menu') );

add_action( 'admin_init', array( 'PodloveSubscribeButton\Settings\Buttons', 'process_form' ) );
register_activation_hook( __FILE__, array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'build_models' ) );

// Register Settings
add_action( 'admin_init', function () {
    $settings = array( 'size', 'autowidth', 'style', 'format', 'color' );

    foreach ( $settings as $setting ) {
        if ( 'autowidth' == $setting ) {
            $args = array(
                'sanitize_callback' => array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'sanitize_settings' ),
            );
            register_setting( 'podlove-subscribe-button', 'podlove_subscribe_button_default_' . $setting, $args );
        } else {
            register_setting( 'podlove-subscribe-button', 'podlove_subscribe_button_default_' . $setting );
        }
    }
} );

add_shortcode( 'podlove-subscribe-button', array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'shortcode' ) );

add_action( 'plugins_loaded', function () {
    load_plugin_textdomain( 'podlove-subscribe-button', false, dirname(plugin_basename( __FILE__)) . '/languages/');
} );

PodloveSubscribeButton::run();
