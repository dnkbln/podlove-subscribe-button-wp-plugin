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
        return new \PodloveSubscribeButton\Utils\API\OkResponse("");
    }

    public function update_item_permissions_check($request)
    {
        return true;
    }
}