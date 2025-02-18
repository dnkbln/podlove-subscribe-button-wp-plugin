<?php

namespace PodloveSubscribeButton\API;

use WP_REST_Controller;

class NetworkButton_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'podlove/subscribe/v1';
        $this->rest_base = 'networkbuttons';
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/'.$this->rest_base, [
            [
                'args' => [
                ],
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
            ]
        ]);
    }

    public function get_items_permissions_check($request)
    {
        return true;
    }

    public function get_items($request)
    {
        return new \WP_REST_Response(null, 200);
    }

}