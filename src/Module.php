<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore;

use OSW3\WpSiteCore\Interfaces\ModuleInterface;
use OSW3\WpSiteCore\Register\MenuLocationsRegister;
use OSW3\WpSiteCore\Register\MenusRegister;
use OSW3\WpSiteCore\Services\PatternService;

class Module implements ModuleInterface
{
    /**
     * Singleton instance of the Module class.
     */
    private static ?self $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        $this->boot();
    }

    /**
     * Unique identifier for the module (ex: 'wp-core').
     *
     * @return string The unique identifier.
     */
    public static function getId(): string
    {
        return 'wp-site-core';
    }

    /**
     * Text domain of the module (ex: 'osw3-wp-core').
     *
     * @return string The text domain.
     */
    public static function getDomain(): string
    {
        return 'osw3-wp-site-core';
    }

    /**
     * Human-readable name of the module (ex: 'OSW3 WP Core').
     *
     * @return string The human-readable name.
     */
    public static function getName(): string
    {
        return 'OSW3 WP Site Core';
    }

    /**
     * Short description of the module's purpose.
     *
     * @return string The short description.
     */
    public static function getDescription(): string
    {
        return 'Initialise et configure la structure de base d\'un site WordPress.';
    }

    /**
     * Current semantic version of the module (ex: '1.0.0').
     *
     * @return string The current semantic version.
     */
    public static function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Relative path to the main file from wp-content/plugins.
     *
     * @return string The relative path to the main file.
     */
    public static function getFile(): string
    {
        return 'osw3-wp-site-core/osw3-wp-site-core.php';
    }

    /**
     * Get the path of the module.
     *
     * @return string The path of the module.
     */
    public static function getPath(): string
    {
        $path = plugin_dir_path(__FILE__);
        $path = str_replace('src/', '', $path);
        return $path;
    }

    /**
     * Get the singleton instance of the Module class.
     *
     * @return self The singleton instance.
     */
    public static function getInstance(): self
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Initialize the module by setting up necessary hooks and services.
     *
     * @return void
     */
    public static function init(): void
    {
        self::getInstance();
    }

    /**
     * Boot the module by initializing services and preventing deactivation if needed.
     *
     * @return void
     */
    public function boot(): void
    {
        // Admin menus
        foreach (MenusRegister::register() as $menu) {
            new $menu();
        }


        // Add menu locations
        $locations = [];
        
        foreach (MenuLocationsRegister::register() as $menuLocation) {
            $locations[$menuLocation::location()] = $menuLocation::label();
        }

        if (did_action('after_setup_theme')) {
            register_nav_menus($locations);
        } else {
            add_action('after_setup_theme', static function () use ($locations): void {
                register_nav_menus($locations);
            });
        }


        // Register block patterns
        PatternService::register( self::getPath() . 'patterns/' );
    }
}