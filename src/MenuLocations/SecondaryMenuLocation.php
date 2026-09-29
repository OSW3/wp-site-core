<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\MenuLocations;

final class SecondaryMenuLocation
{
    public static function location(): string
    {
        return 'secondary-menu';
    }

    public static function label(): string
    {
        return 'Secondary Menu';
    }
}