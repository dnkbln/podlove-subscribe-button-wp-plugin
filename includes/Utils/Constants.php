<?php

namespace PodloveSubscribeButton\Utils;

class Constants
{
    public static string $plugin_file;
    public static string $plugin_dir;
    public static string $plugin_file_name;
    public static string $plugin_url;

    public static function init(string $plugin_file_path): void {
        self::$plugin_file = $plugin_file_path;
        self::$plugin_dir = rtrim(plugin_dir_path($plugin_file_path), DIRECTORY_SEPARATOR);
        self::$plugin_file_name = basename($plugin_file_path);
        self::$plugin_url       = plugin_dir_url($plugin_file_path);
    }
}

