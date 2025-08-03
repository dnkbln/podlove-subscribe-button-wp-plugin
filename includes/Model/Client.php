<?php

namespace PodloveSubscribeButton\Model;

use PodloveSubscribeButton\Utils\Constants;
use PodloveSubscribeButton\Model\Base;
use Symfony\Component\Yaml\Yaml;

class Client extends Base {

    public const PLATFORMS = [
        'none' => 0,
        'android' => 1,
        'ios' => 2,
        'osx' => 4,
        'windows' => 8,
        'unix' => 16,
        'web' => 32
    ];

    public const TYPES = [
        'none' => 0,
        'app' => 1,
        'service' => 2
    ];

    public function get_platform_list()
    {
        if ($this->platform === 0)
        {
            return ['none'];
        }
        else
        {
            $platform = $this->platform;
            return array_keys(array_filter(Client::PLATFORMS, function($value) use ($platform) {
                return $value > 0 && ($platform & $value) === $value;
            }));
        }
    }

    public function get_type_list()
    {
        if ($this->type === 0)
        {
            return ['none'];
        }
        else
        {
            $type = $this->type;
            return array_keys(array_filter(Client::TYPES, function($value) use ($type) {
                return $value > 0 && ($type & $value) === $value;
            }));
        }
    }

    public static function config_clients()
    {
        $file = implode(
            DIRECTORY_SEPARATOR,
            [Constants::$plugin_dir, 'includes', 'Model', 'data', 'clients.yml']
        );

        return Yaml::parse(file_get_contents($file));
    }
}

Client::property( 'id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY' );
Client::property( 'name', 'VARCHAR(255)' );
Client::property( 'platform', 'INTEGER' );
Client::property( 'type', 'INTEGER' );
