<?php

namespace PodloveSubscribeButton\API;

use PodloveSubscribeButton\Utils\API\CreateResponse;
use PodloveSubscribeButton\Utils\API\NotFound;
use PodloveSubscribeButton\Utils\API\OkResponse;
use PodloveSubscribeButton\Model\Client;
use WP_REST_Controller;

class Client_Controller extends \WP_REST_Controller
{

    public function __construct()
    {
        $this->namespace = 'podlove/subscribe/v1';
        $this->rest_base = 'clients';
    }

    public function register_routes() {

        // API route for user selected clients (getting and creating)
        register_rest_route($this->namespace, '/'.$this->rest_base, [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
            ],
            [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'create_item'],
                'permission_callback' => [$this, 'create_item_permissions_check'],
            ]
        ]);

        // API route for user selected client by ID (getting, updating, deleting)
       register_rest_route($this->namespace, '/'.$this->rest_base.'/(?P<id>[\d]+)', [
            'args' => [
                'id' => [
                    'description' => __('Unique identifier for the button.', 'podlove-subscribe-button-plugin-for-wordpress'),
                    'type' => 'integer',
                ],
            ],
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_item'],
                'permission_callback' => [$this, 'get_item_permissions_check'],
            ],
            [
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_item'],
                'args'     => [
                    'title' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'title' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'platform' => [
                        'description' => __('Client platforms: android, ios, osx, windows, unix and/or web', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type'        => 'array',
                        'items'       => [
                            'type' => 'string',
                            'enum' => ['android', 'ios', 'osx', 'windows', 'unix', 'web'],
                        ],
                    ],
                    'type' => [
                        'description' => __('Client type: app and/or service', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type'        => 'array',
                        'items'       => [
                            'type' => 'string',
                            'enum' => ['app', 'service'],
                        ],
                    ],
                ],
                'permission_callback' => [$this, 'update_item_permissions_check'],
            ],
            [
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => [$this, 'delete_item'],
                'permission_callback' => [$this, 'delete_item_permissions_check'],
            ]
       ]);

        // API route for getting all available clients
        register_rest_route($this->namespace, '/'.$this->rest_base.'/list', [
            'methods' => 'GET',
            'callback' => [$this, 'get_clients'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function get_items($request) {
        $clients = Client::all();
        $results = [];

        foreach( $clients as $client) {
            array_push( $results, [
                'id' => $client->id,
                'title' => $client->title,
                'platform' => $client->get_platform_list(),
                'type' => $client->get_type_list(),
                'call_schema' => $client->call_schema
            ]);
        }

        return new OkResponse($results);
    }

    public function get_items_permissions_check($request) {
        return true;
    }

    public function create_item($request) {
        $client = new Client();
        $client->title = "API added client";
        $client->save();

        return new CreateResponse([
            'id' => $client->id
        ]);
    }

    public function create_item_permissions_check($request) {
        return current_user_can('edit_posts');
    }

    public function get_item($request) {

        $id = $request->get_param('id');
        $client = Client::find_by_id($id);

        if (!isset($client)) {
            return new NotFound();
        }

        return new OkResponse([
            'id' => $client->id,
            'title' => $client->title,
            'platform' => $client->get_platform_list(),
            'type' => $client->get_type_list(),
            'call_schema' => $client->call_schema
        ]);
    }

    public function get_item_permissions_check($request) {
        return true;
    }

    public function update_item($request) {
        $id = $request->get_param('id');
        $client = Client::find_by_id($id);

        if (!isset($client)) {
            return new NotFound();
        }

        if (isset($request['title'])) {
            $title = $request['title'];
            $client->title = $title;
        }

        if (isset($request['platform'])) {
            $platforms = $request['platform'];
            $client->platform = Client::get_platform($platforms);
        }

        if (isset($request['type'])) {
            $types = $request['type'];
            $client->type = Client::get_type($types);
        }

        $client->save();

        return new OkResponse([
            'status' => 'ok'
        ]);

    }

    public function update_item_permissions_check($request) {
        return current_user_can('edit_posts');
    }

    public function delete_item($request) {

        $id = $request->get_param('id');
        $client = Client::find_by_id($id);

        if (!isset($client)) {
            return new NotFound();
        }

        $client->delete();

        return new OkResponse([
            'status' => 'ok'
        ]);
    }

    public function delete_item_permissions_check($request) {
        return current_user_can('delete_posts');
    }

    public function get_clients() {
        return Client::config_clients();
    }
}