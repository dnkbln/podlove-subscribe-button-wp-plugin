<?php
/**
 * Version management for database migrations.
 *
 * Database changes require special care:
 * - the model has to be adjusted for users installing the plugin
 * - the current setup has to be migrated for current users
 *
 * These migrations are a way to handle current users. They do *not*
 * run on plugin activation.
 *
 * Pattern:
 *
 * - increment \PodloveSubscribeButton\DATABASE_VERSION constant by 1, e.g.
 * 		```php
 * 		define( __NAMESPACE__ . '\DATABASE_VERSION', 2 );
 * 		```
 *
 * - add a case in `\PodloveSubscribeButton\run_migrations_for_version`, e.g.
 * 		```php
 * 		function run_migrations_for_version( $version ) {
 *			global $wpdb;
 *			switch ( $version ) {
 *				case 2:
 *					$wbdb-> // run sql or whatever
 *					break;
 *			}
 *		}
 *		```
 *
 *		Feel free to move the migration code into a separate function if it's
 *		rather complex.
 *
 * - adjust the main model / setup process so new users installing the plugin
 *   will have these changes too
 *
 * - Test the migrations! :)
 */

namespace PodloveSubscribeButton;

define( __NAMESPACE__ . '\DATABASE_VERSION', 4 );

add_action( 'admin_init', '\PodloveSubscribeButton\maybe_run_database_migrations' );
add_action( 'admin_init', '\PodloveSubscribeButton\run_database_migrations', 5 );

function maybe_run_database_migrations() {
    $database_version = get_option('podlove_subscribe_button_plugin_database_version');

    if ( $database_version === false ) {
        // plugin has just been installed or Plugin Version < 1.3
        update_option( 'podlove_subscribe_button_plugin_database_version', DATABASE_VERSION );
    }
}

function run_database_migrations() {
    if (get_option('podlove_subscribe_button_plugin_database_version') >= DATABASE_VERSION)
        return;

    if (is_multisite()) {
        set_time_limit(0); // may take a while, depending on network size
        \PodloveSubscribeButton\Utils\for_every_podcast_blog(function() { migrate_for_current_blog(); });
    } else {
        migrate_for_current_blog();
    }

    if (isset($_REQUEST['_wp_http_referer']) && $_REQUEST['_wp_http_referer']) {
        wp_redirect($_REQUEST['_wp_http_referer']);
        exit;
    }
}

function migrate_for_current_blog() {
    $database_version = get_option('podlove_subscribe_button_plugin_database_version');

    for ($i = $database_version+1; $i <= DATABASE_VERSION; $i++) {
        \PodloveSubscribeButton\run_migrations_for_version($i);
        update_option('podlove_subscribe_button_plugin_database_version', $i);
    }
}

/**
 * Execute migration query. Captures error if one occurs.
 *
 * @param string $sql
 */
function podlove_do_migration_query($sql) {
    global $wpdb;

    $success = $wpdb->query($sql);

    if ($success === false) {
        update_option('podlove_subscribe_db_migration_error', [
            'error' => $wpdb->last_error,
            'query' => $wpdb->last_query,
        ]);
    }

    return (bool) $success;
}

/**
 * Find and run migration for given version number.
 *
 * @todo  move migrations into separate files
 *
 * @param  int $version
 */
function run_migrations_for_version( $version ) {

    global $wpdb;

    switch ( $version ) {
        case 3:
            $sql1 = sprintf(
                'ALTER TABLE `%s`ADD COLUMN `size` VARCHAR(255)',
                Model\Button::table_name()
            );
            $sql2 = sprintf(
                'ALTER TABLE `%s`ADD COLUMN `autowidth` BOOLEAN',
                Model\Button::table_name()
            );
            $sql3 = sprintf(
                'ALTER TABLE `%s`ADD COLUMN `color` VARCHAR(255)',
                Model\Button::table_name()
            );
            $sql4 = sprintf(
                'ALTER TABLE `%s`ADD COLUMN `style` VARCHAR(255)',
                Model\Button::table_name()
            );
            $sql5 = sprintf(
                'ALTER TABLE `%s`ADD COLUMN `format` VARCHAR(255)',
                Model\Button::table_name()
            );

            podlove_do_migration_query($sql1);
            podlove_do_migration_query($sql2);
            podlove_do_migration_query($sql3);
            podlove_do_migration_query($sql4);
            podlove_do_migration_query($sql5);

            $default_size = get_option('podlove_subscribe_button_default_size');
            $default_autowidth = get_option('podlove_subscribe_button_default_autowidth');
            $default_color = get_option('podlove_subscribe_button_default_color');
            $default_style = get_option('podlove_subscribe_button_default_style');
            $default_format = get_option('podlove_subscribe_button_default_format');

            $buttons = Model\Button::all();
            foreach($buttons as $button) {
                $button->size = $default_size;
                $button->autowidth = $default_autowidth;
                $button->color = $default_color;
                $button->style = $default_style;
                $button->format = $default_format;
                $button->save();
            }
            break;
        case 4:
            \PodloveSubscribeButton\Model\Client::build();
            break;
    }

}