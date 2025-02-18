<?php

namespace PodloveSubscribeButton\Utils\API;

use WP_REST_Response;

class CreateResponse extends WP_REST_Response
{
    /**
     * Constructor.
     *
     * @param $data
     * @param $headers
     */
    public function __construct($data = null, array $headers = [])
    {
        parent::__construct($data, 201, $headers);
    }
}