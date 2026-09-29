<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface ModuleInterface
{
    /**
     * Unique identifier for the module (ex: 'wp-feat-seo').
     * 
     * @return string The unique identifier.
     */
    public static function getId(): string;

    /**
     * Text domain of the module (ex: 'wp-feat-seo').
     * 
     * @return string The text domain.
     */
    public static function getDomain(): string;

    /**
     * Human-readable name of the module (ex: 'WP Feature : SEO').
     * 
     * @return string The human-readable name.
     */
    public static function getName(): string;

    /**
     * Short description of the module's purpose.
     *
     * @return string The short description.
     */
    public static function getDescription(): string;

    /**
     * Current semantic version of the module (ex: '1.0.0').
     *
     * @return string The current semantic version.
     */
    public static function getVersion(): string;

    /**
     * Relative path to the main file from wp-content/plugins.
     *
     * @return string The relative path to the main file.
     */
    public static function getFile(): string;
    
    /**
     * Get the singleton instance of the Module class.
     *
     * @return self The singleton instance.
     */
    public static function getInstance(): self;

    /**
     * Initialize the module by setting up necessary hooks and services.
     * 
     * @return void
     */
    public static function init(): void;

    /**
     * Boot the module by initializing services and preventing deactivation if needed.
     *
     * @return void
     */
    public function boot(): void;
}