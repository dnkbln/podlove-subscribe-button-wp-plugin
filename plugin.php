<?php

namespace PodloveSubscribeButton;

add_action( 'admin_menu', array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'admin_menu') );
if ( is_multisite() )
    add_action( 'network_admin_menu', array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'admin_network_menu') );

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

add_action( 'admin_enqueue_scripts', array( 'PodloveSubscribeButton\PodloveSubscribeButton', 'enqueue_assets' ) );

add_action( 'plugins_loaded', function () {
    load_plugin_textdomain( 'podlove-subscribe-button', false, dirname(plugin_basename( __FILE__)) . '/languages/');
} );

add_action('admin_head', function() {
    $data = apply_filters('subscribe_data_js', []); ?>

    <script>
      window.SUBSCRIBE_DATA = window.SUBSCRIBE_DATA || { baseUrl: '<?php echo home_url(); ?>' };
      <?php foreach ($data as $key => $value) { ?>
          window.SUBSCRIBE_DATA['<?php echo $key; ?>'] = <?php echo wp_json_encode($value); ?>;
      <?php } ?>

      window.addEventListener('load', function () {
        if (window.initSubscribeUI) {
          window.initSubscribeUI(window.SUBSCRIBE_DATA);
        }
      })
    </script>
    <?php
}, 3);

add_action('rest_api_init', function () {
    $button = new \PodloveSubscribeButton\API\Button_Controller();
    $button->register_routes();
    $networkbutton = new \PodloveSubscribeButton\API\NetworkButton_Controller();
    $networkbutton->register_routes();
});

add_action( 'init', function() {
    register_block_type( __DIR__ . '/block/build/podlove-subscribe-button-block' );
});
