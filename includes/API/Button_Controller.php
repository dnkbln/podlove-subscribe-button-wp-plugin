<?php

namespace PodloveSubscribeButton\API;

use WP_REST_Controller;

class Button_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'podlove/subscribe/v1';
        $this->rest_base = 'buttons';
    }

    public function register_routes()
    {
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
                'args' => [
                    'name' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'title' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'subtitle' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'description' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'cover' => [
                        'description' => __('An url for the episode cover', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string',
                        'validate_callback' => '\PodloveSubscribeButton\Utils\API\Validation::url'
                    ],
                    'feeds' => [
                        'description' => __('Title for the podcast::soundbite tag', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'string'
                    ],
                    'feeds' => [
                        'description' => __('List of chapters, please use MP4Chaps format.', 'podlove-subscribe-button-plugin-for-wordpress'),
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'url' => [
                                    'description' => __('Feed url', 'podlove-subscribe-button-plugin-for-wordpress'),
                                    'type' => 'string',
                                    'required' => 'true',
                                    'validate_callback' => '\PodloveSubscribeButton\Utils\API\Validation::url'
                                ],
                                'applefeedid' => [
                                    'description' => __('Apple Podcast ID', 'podlove-subscribe-button-plugin-for-wordpress'),
                                    'type' => 'string',
                                ],
                                'format' => [
                                    'description' => __('Media format', 'podlove-subscribe-button-plugin-for-wordpress'),
                                    'type' => 'integer'
                                ]
                            ]
                        ]
                    ]
                ],
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_item'],
                'permission_callback' => [$this, 'update_item_permissions_check'],
            ],
            [
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => [$this, 'delete_item'],
                'permission_callback' => [$this, 'delete_item_permissions_check'],
            ]
        ]);
    }

    public function get_items_permissions_check($request)
    {
        return true;
    }

    public function get_items($request)
    {
        $buttons = \PodloveSubscribeButton\Model\Button::all();
        $results = [];

        foreach( $buttons as $button) {
            array_push( $results, [
                'id' => $button->id,
                'name' => $button->name,
                'title' => $button->title,
                'subtitle' => $button->subtitle,
                'description' => $button->description,
                'cover' => $button->cover,
                'feeds' => $button->feeds
            ]);
        }

        return new \PodloveSubscribeButton\Utils\API\OkResponse($results);
    }

    public function create_item_permissions_check($request)
    {
        return true;
    }

    public function create_item($request)
    {
        $button = new \PodloveSubscribeButton\Model\Button();
        $button->name = "API created Button";
        $button->save();

        return new \PodloveSubscribeButton\Utils\API\CreateResponse([
            'id' => $button->id
        ]);
    }

    public function get_item_permissions_check($request)
    {
        return true;
    }

    public function get_item($request)
    {
        $id = $request->get_param('id');
        $button = \PodloveSubscribeButton\Model\Button::find_by_id($id);

        if (!isset($button)) {
            return new \PodloveSubscribeButton\Utils\API\NotFound();
        }

        return new \PodloveSubscribeButton\Utils\API\OkResponse([
            'id' => $button->id,
            'name' => $button->name,
            'title' => $button->title,
            'subtitle' => $button->subtitle,
            'description' => $button->description,
            'cover' => $button->cover,
            'feeds' => $button->feeds
        ]);
    }

    public function update_item_permissions_check($request)
    {
        return true;
    }

    public function update_item($request)
    {
        $id = $request->get_param('id');
        $button = \PodloveSubscribeButton\Model\Button::find_by_id($id);

        if (!isset($button)) {
            return new \PodloveSubscribeButton\Utils\API\NotFound();
        }

        if (isset($request['name'])) {
            $name = $request['name'];
            $button->name = $name;
        }

        if (isset($request['title'])) {
            $title = $request['title'];
            $button->title = $title;
        }

        if (isset($request['subtitle'])) {
            $subtitle = $request['subtitle'];
            $button->subtitle = $subtitle;
        }

        if (isset($request['subtitle'])) {
            $subtitle = $request['subtitle'];
            $button->subtitle = $subtitle;
        }

        if (isset($request['cover'])) {
            $cover = $request['cover'];
            $button->cover = $cover;
        }

        $feeds = [];
        if (isset($request['feeds']) && is_array($request['feeds'])) {
            for ($i = 0; $i < count($request['feeds']); ++$i) {
                $url = '';
                $itunesfeedid = '';
                $format = '';
                if (isset($request['feeds'][$i]['url'])) {
                    $url = $request['feeds'][$i]['url'];
                }
                if (isset($request['feeds'][$i]['applefeedid'])) {
                    $itunesfeedid = $request['feeds'][$i]['applefeedid'];
                }
                if (isset($request['feeds'][$i]['format'])) {
                    $format = $request['feeds'][$i]['format'];
                }
                array_push( $feeds, [
                    'url' => $url,
                    'itunesfeedid' => $itunesfeedid,
                    'format' => $format
                ]);
            }
        }
        $button->update_attributes( ["feeds" => $feeds] );

        $button->save();

        return new \PodloveSubscribeButton\Utils\API\OkResponse([
            'status' => 'ok'
        ]);
    }

    public function delete_item_permissions_check($request)
    {
        return true;
    }

    public function delete_item($request)
    {
        $id = $request->get_param('id');
        $button = \PodloveSubscribeButton\Model\Button::find_by_id($id);

        if (!isset($button)) {
            return new \PodloveSubscribeButton\Utils\API\NotFound();
        }

        $button->delete();

        return new \PodloveSubscribeButton\Utils\API\OkResponse([
            'status' => 'ok'
        ]);
    }

}