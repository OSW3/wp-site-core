<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface MenuInterface
{
    /**
     * Unique identifier of the menu location (e.g., 'primary_navigation').
     * 
     * @return string The unique identifier of the menu location.
     */
    public static function location(): string;

    /**
     * Human-readable label in the admin interface (e.g., 'Primary Navigation').
     *
     * @return string The human-readable label of the menu.
     */
    public static function label(): string;

    /**
     * Optional configuration/rendering arguments (walker, container, etc.).
     *
     * @return array The optional configuration/rendering arguments for the menu.
     */
    public static function options(): array;
}