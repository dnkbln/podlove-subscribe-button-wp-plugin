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

    public static function get_platform($platforms)
    {
        $platform_val = 0;
        if (is_array($platforms)) {
            $platform_val = Client::PLATFORMS['none'];
            foreach ( $platforms as $platform ) {
                if ( $platform === 'android' ) {
                    $platform_val = $platform_val | Client::PLATFORMS['android'];
                } else if ( $platform === 'ios' ) {
                    $platform_val = $platform_val | Client::PLATFORMS['ios'];
                } else if ( $platform === 'osx' ) {
                    $platform_val = $platform_val | Client::PLATFORMS['osx'];
                } else if ( $platform === 'windows' ) {
                    $platform_val = $platform_val | Client::PLATFORMS['windows'];
                } else if ( $platform === 'unix' ) {
                    $platform_val = $platform_val | Client::PLATFORMS['unix'];
                } else if ( $platform === 'web' ) {
                    $platform_val = $platform_val | Client::PLATFORMS['web'];
                }
            }
        } else {
            if ( $platforms === 'android') {
                $platform_val = Client::PLATFORMS['android'];
            } else if ( $platforms === 'ios' ) {
                $platform_val = Client::PLATFORMS['ios'];
            } else if ( $platforms === 'osx' ) {
                $platform_val = Client::PLATFORMS['osx'];
            } else if ( $platforms === 'windows' ) {
                $platform_val = Client::PLATFORMS['windows'];
            } else if ( $platforms === 'unix' ) {
                $platform_val = Client::PLATFORMS['unix'];
            } else if ( $platforms === 'web' ) {
                $platform_val = Client::PLATFORMS['web'];
            } else {
                $platform_val = Client::PLATFORMS['none'];
            }
        }

        return $platform_val;
    }

    public static function get_type($types)
    {
        $type_val = 0;
        if (is_array($types)) {
            $type_val = Client::TYPES['none'];
            foreach ( $types as $type ) {
                if ( $type === 'app' ) {
                    $type_val = $type_val | Client::TYPES['app'];
                } else if ( $type === 'service' ) {
                    $type_val = $type_val | Client::TYPES['service'];
                }
            }
        } else {
            if ( $types === 'app') {
                $type_val = Client::TYPES['app'];
            } else if ( $types === 'service' ) {
                $type_val = Client::TYPES['service'];
            } else {
                $type_val = Client::TYPES['none'];
            }
        }

        return $type_val;
    }

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

        $clients = Yaml::parse(file_get_contents($file));
        $merged = [];

        foreach ($clients as $client) {
            $title = $client['title'];
            $platform = Client::get_platform($client['platform']);
            $type = Client::get_type($client['type']);

            if (!isset($merged[$title])) {
                $merged[$title] = $client;
                $merged[$title]['platform'] = $platform;
                $merged[$title]['type'] = $type;
            } else {
                $merged[$title]['platform'] |= $platform;
                $merged[$title]['type'] |= $type;
            }
        }

        // Return as indexed array
        return array_values($merged);
    }

    public static function find_all_by_button_id($button_id) {
        return self::find_all_by_property( 'button_id', $button_id );
    }

    public static function delete_by_button_id($button_id) {
        $clients = self::find_all_by_property( 'button_id', $button_id );

        foreach ( $clients as $client ) {
            $client->delete();
        }
    }
}

Client::property( 'id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY' );
Client::property( 'title', 'VARCHAR(255)' );
Client::property( 'platform', 'INTEGER' );
Client::property( 'type', 'INTEGER' );
Client::property( 'call_schema', 'TEXT' );
Client::property( 'button_id', 'INT' );
Client::property( 'podcast_id', 'VARCHAR(255)' );
