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

// Security check to prevent direct access to the file
if (!defined('ABSPATH')) {
  exit;
}


// Autoload dependencies using Composer
// --

$autoloads = [
  __DIR__ . '/vendor/autoload.php',
  dirname(__DIR__, 4) . '/vendor/autoload.php',
];

$autoload = null;

foreach ($autoloads as $file) {
  if (is_file($file)) {
    $autoload = $file;
    break;
  }
}

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








/**
 * Enqueue the main stylesheet for the theme
 */
add_action('wp_enqueue_scripts', function() {
  wp_register_style("main", get_template_directory_uri() . "/assets/css/main.css");
  wp_enqueue_style("main");
});

/**
 * Enqueue the main JavaScript for the theme
 */
add_action('wp_enqueue_scripts', function() {
  wp_register_script("main", get_template_directory_uri() . "/assets/scripts/main.js", [], false, true);
  wp_enqueue_script("main");
//   if (is_singular() && comments_open() && get_option('thread_comments')) {
//     wp_enqueue_script('comment-reply');
//   }
});


/**
 * Disable default Gutenberg and WooCommerce styles
 */
add_action('wp_enqueue_scripts', function() {

  // Disable default Gutenberg block library styles
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  
  // Disable Global Styles CSS (theme.json / injected CSS variables)
  wp_dequeue_style('global-styles');
  
  // Disable classic / duotone / WooCommerce block styles if present
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('wc-blocks-vendors-style');
  wp_dequeue_style('wc-blocks-style');

}, 100);


// Remove the injection of duotone SVG / filters in the <body>
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');

// Disable the injection of inline styles for Block Patterns / Gutenberg
add_filter('should_load_separate_core_block_assets', '__return_false');


// Remove the CSS for Emojis
remove_action('wp_print_styles', 'print_emoji_styles');

// Remove additional Emoji scripts + styles (complete cleanup)
add_action('init', function () {
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

// Remove the inline CSS "wp-img-auto-sizes-contain-inline-css" (WP 6.7+)
add_action('wp_enqueue_scripts', function () {
  wp_dequeue_style('wp-img-auto-sizes-contain');
}, 9999);

// Remove the inline CSS "wp-block-template-skip-link-inline-css"
add_action('wp_enqueue_scripts', function () {
  wp_dequeue_style('wp-block-template-skip-link');
}, 9999);

// Remove the inline CSS "core-block-supports-inline-css"
// This style injects the native block support (layout, margins, etc.)
add_action('wp_footer', function () {
  wp_dequeue_style('core-block-supports');
}, 1);

// Complementary alternative to intercept and dequeue core-block-supports in wp_head
add_action('wp_enqueue_scripts', function () {
  wp_dequeue_style('core-block-supports');
}, 9999);

/**
 * Supprime le wrapper <div class="wp-block-template-part"> des blocs template-part
 */
add_filter('render_block_core/template-part', function(string $block_content): string {

  return preg_replace('/^<div class="wp-block-template-part"[^>]*>|<\/div>$/', '', $block_content);
  
}, 10, 1);










if (!function_exists('wp_site_core_render_pattern')) {
    /**
     * Rendu d'un sous-composant / pattern depuis le plugin ou le thème
     */
    function wp_site_core_render_pattern(string $slug, array $args = []): void
    {
        // Nettoyage : "patterns/button/button" -> "button/button" ou "button"
        $clean_slug = preg_replace('/^(patterns\/)?(wp-site-core\/)?(component\/)?/', '', $slug);
        $parent_dir = explode('/', $clean_slug)[0];

        $possible_paths = [
            // Overrides Thème (Prioritaires)
            get_theme_file_path("patterns/{$clean_slug}.php"),
            get_theme_file_path("patterns/{$parent_dir}/{$clean_slug}.php"),

            // Plugin Native (Fallback)
            __DIR__ . "/patterns/{$clean_slug}.php",
            __DIR__ . "/patterns/{$parent_dir}/{$clean_slug}.php",
        ];

        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                // $args est injecté automatiquement dans la portée du fichier inclus
                include $path;
                return;
            }
        }
    }
}