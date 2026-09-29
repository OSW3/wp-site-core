<?php
/**
 * Plugin Name: OSW3 WP Site Core
 * Plugin URI: https://github.com/OSW3/wp-site-core
 * Description: Initialise et configure la structure de base d'un site WordPress.
 * Version: 1.0.0
 * Author: OSW3
 * Author URI: https://github.com/OSW3
 * License: MIT
 * Text Domain: osw3-wp-site-core
 */

declare(strict_types=1);

use OSW3\WpSiteCore\Registry\PluginRegistry;

// Security check to prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}


// Autoload dependencies using Composer
// --

$autoload = __DIR__ . '/vendor/autoload.php';

if (!is_file($autoload)) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-error"><p>';
        echo esc_html__(sprintf('%s: Composer autoload is missing. Run composer install in the plugin directory.', \OSW3\WpSiteCore\Module::getName()), \OSW3\WpSiteCore\Module::getDomain());
        echo '</p></div>';
    });

    return;
}

require_once $autoload;


// Hook & Bootstrap the module
// --

add_action('plugins_loaded', static function (): void {
    if (class_exists(\OSW3\WpSiteCore\Module::class)) {
        \OSW3\WpSiteCore\Module::init();
    }
});