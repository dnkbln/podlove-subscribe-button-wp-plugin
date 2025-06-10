<?php

namespace PodloveSubscribeButton\API;

use WP_REST_Controller;

class Settings_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'podlove/subscribe/v1';
        $this->rest_base = 'settings';
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/'.$this->rest_base, [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_item'],
                'permission_callback' => [$this, 'get_item_permissions_check'],
            ],
            [
                'args' => [
                    'size' => [
                        'description' => __('Size of the subscribe button', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string',
                        'enum' => ['small', 'medium', 'big']
                    ],
                    'color' => [
                        'description' => __('Color of the subscribe button', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'autowidth' => [
                        'description' => __('Autowidth of the subscribe button', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string',
                        'enum' => ['on', 'off']
                    ],
                    'style' => [
                        'description' => __('Style of the subscribe button', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string',
                        'enum' => ['filled', 'outline', 'frameless']
                    ],
                    'format' => [
                        'description' => __('Format of the subscribe button', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string',
                        'enum' => ['rectangle', 'square', 'cover']
                    ],
                ],
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_item'],
                'permission_callback' => [$this, 'update_item_permissions_check'],
            ]
        ]);
    }

    public function get_item($request)
    {
        $settings = [];
        $settings = \PodloveSubscribeButton\Model\Button::get_global_setting_with_fallback();
        return new \PodloveSubscribeButton\Utils\API\OkResponse($settings);
    }

    public function get_item_permissions_check($request)
    {
        return true;
    }

    public function update_item($request)
    {
        if (isset($request['size'])) {
            $option = 'size';
            $value = $request['size'];
            update_option('podlove_subscribe_button_default_' . $option, $value);
        }

        if (isset($request['color'])) {
            $option = 'color';
            $value = $request['color'];
            update_option('podlove_subscribe_button_default_' . $option, $value);
        }

        if (isset($request['autowidth'])) {
            $option = 'autowidth';
            $value = $request['autowidth'];
            update_option('podlove_subscribe_button_default_' . $option, $value);
        }

        if (isset($request['sstyle'])) {
            $option = 'sstyle';
            $value = $request['sstyle'];
            update_option('podlove_subscribe_button_default_' . $option, $value);
        }

        if (isset($request['format'])) {
            $option = 'format';
            $value = $request['format'];
            update_option('podlove_subscribe_button_default_' . $option, $value);
        }

        return new \PodloveSubscribeButton\Utils\API\OkResponse([
            'status' => 'ok'
        ]);
    }

    public function update_item_permissions_check($request)
    {
        return true;
    }
}