<?php

namespace PodloveSubscribeButton\Utils\API;

class Validation
{
    public static function url($param, $request, $key)
    {
        if (empty($param)) {
            return false;
        }

        if (preg_match('/\\b(?:(?:https?|ftp):\\/\\/|www\\.)[-a-z0-9+&@#\\/%?=~_|!:,.;]*[-a-z0-9+&@#\\/%=~_|]/i', $param)) {
            return true;
        }

        return false;
    }

    public static function maxLength255($param, $request, $key)
    {
        if (isset($param) && gettype($param) == 'string') {
            if (strlen($param) <= 255) {
                return true;
            }
        }

        return false;
    }

}