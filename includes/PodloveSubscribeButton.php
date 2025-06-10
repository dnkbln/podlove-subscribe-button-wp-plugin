<?php

namespace PodloveSubscribeButton;

class PodloveSubscribeButton {

    public static function enqueue_assets( $hook ) {

        $pages = array( 'settings_page_podlove-subscribe-button', 'widgets.php' );

        if ( ! in_array( $hook, $pages )  ) {
            return;
        }

        // Vue UI
        $version = '0.0.1';
        wp_register_script('podlove-subscribe-button-client', plugin_dir_url(__FILE__).'../client/dist/main.js', [], $version, false);
        add_filter('subscribe_data_js', function ($data) {
            $data['api'] = [
                'base' => esc_url_raw(rest_url('podlove/subscribe')),
                'nonce' => wp_create_nonce('wp_rest'),
            ];

            return $data;
        });
        wp_enqueue_script('podlove-subscribe-button-client');
        wp_enqueue_style('podlove-subscribe-button-client', plugin_dir_url(__FILE__).'../client/dist/style.css', [], $version);
    }

    public static function admin_menu() {
        add_options_page(
                'Podlove Subscribe Button Options',
                'Podlove Subscribe Button',
                'manage_options',
                'podlove-subscribe-button',
                array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'page')
            );
    }

    public static function admin_network_menu() {
        add_submenu_page(
                'settings.php',
                'Podlove Subscribe Button Options',
                'Podlove Subscribe Button',
                'manage_options',
                'podlove-subscribe-button',
                array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'page')
            );
    }

    public static function build_models() {
        // Build Databases
        \PodloveSubscribeButton\Model\Button::build();
        if ( is_multisite() )
            \PodloveSubscribeButton\Model\NetworkButton::build();

        // Set Button "default" values
        $default_values = array(
                'size' => 'big',
                'autowidth' => 'on',
                'color' => '#599677',
                'style' => 'filled',
                'format' => 'rectangle'
            );

        foreach ($default_values as $option => $default_value) {
            if ( ! get_option('podlove_subscribe_button_default_' . $option ) ) {
                update_option('podlove_subscribe_button_default_' . $option, $default_value);
            }
        }
    }

    public static function shortcode( $args ) {
        if ( ! $args || ! isset($args['button']) ) {
            return __('You need to create a Button first and provide its ID.', 'podlove-subscribe-button');
        } else {
            $buttonid = $args['button'];
        }

        // Fetch the (network)button by it's name
        if ( ! $button = \PodloveSubscribeButton\Model\Button::get_button_by_name($args['button']) )
            return sprintf( __('Oops. There is no button with the ID "%s".', 'podlove-subscribe-button'), $args['button'] );

        // Get button styling and options
        $autowidth = self::interpret_width_attribute( self::get_array_value_with_fallback($args, 'width') );
        $size = self::get_attribute( 'size', self::get_array_value_with_fallback($args, 'size') );
        $style = self::get_attribute( 'style', self::get_array_value_with_fallback($args, 'style') );
        $format = self::get_attribute( 'format', self::get_array_value_with_fallback($args, 'format') );
        $color = self::get_attribute( 'color', self::get_array_value_with_fallback($args, 'color') );

        if ( isset($args['language']) ) {
            $language = $args['language'];
        } else {
            $language = 'en';
        }

        if ( isset($args['color']) ) {
            $color = $args['color'];
        } else {
            $color = get_option('podlove_subscribe_button_default_color', '#599677');
        }

        if ( isset($args['hide']) && $args['hide'] == 'true' ) {
            $hide = true;
        } else {
            $hide = false;
        }

        // Render button
        return $button->render($size, $autowidth, $style, $format, $color, $hide, $buttonid, $language);
    }

    public static function get_array_value_with_fallback($args, $key) {
        if ( isset($args[$key]) )
            return $args[$key];

        return "";
    }

    /**
     * @param  string $attribute
     * @param  string $attribute_value
     * @return string
     */
    private static function get_attribute($attribute=null, $attribute_value=null) {
        if ( isset($attribute_value) && ctype_alnum($attribute_value) && key_exists( $attribute_value, \PodloveSubscribeButton\Model\Button::$$attribute ) ) {
            return $attribute_value;
        } else {
            return get_option('podlove_subscribe_button_default_' . $attribute, \PodloveSubscribeButton\Model\Button::$defaultSettings[$attribute]);
        }
    }

    /**
     * Interprets the provided width attribute and return either auto- or a specific width
     * @param  string $width_attribute
     * @return string
     */
    private static function interpret_width_attribute( $width_attribute = null ) {
        if ( $width_attribute == 'auto' )
            return 'on';
        if ( $width_attribute && $width_attribute !== 'auto' )
            return 'off';

        return get_option('podlove_subscribe_button_default_autowidth', 'on');
    }

    public static function sanitize_settings( $input = null ) {
        if ( null == $input ) {
            return 'off';
        } elseif ( 'on' == $input ) {
            return $input;
        }
    }

    public static function page() {
        ?>
        <div style="padding-top: 1rem; padding-right: 1rem" >
            <div data-client="podlove-subscribe-button">
                <subscribe-button-overview></subscribe-button-overview>
                <subscribe-button-list></subscribe-button-list>
            </div>
        </div>
        <?php
    }
}
