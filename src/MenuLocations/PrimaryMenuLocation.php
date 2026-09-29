<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\MenuLocations;

final class PrimaryMenuLocation
{
    public static function location(): string
    {
        return 'primary-menu';
    }

    public static function label(): string
    {
        return 'Primary Menu';
    }
}