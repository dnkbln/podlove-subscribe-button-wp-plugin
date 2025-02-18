<?php

namespace PodloveSubscribeButton\Utils\API;

class NotFound extends \WP_Error
{
    /**
     * Constructor.
     *
     * @param mixed $code
     * @param mixed $message
     */
    public function __construct($code = '', $message = '')
    {
        if (strlen($code) == 0) {
            $code = 'rest_not_found';
        }
        if (strlen($message) == 0) {
            $message = esc_html__('sorry, we did not find the requested resource');
        }
        parent::__construct($code, $message, ['status' => 404]);
    }
}
