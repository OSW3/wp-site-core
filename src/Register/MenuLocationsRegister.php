<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Register;

final class MenuLocationsRegister
{
    public static function register(): array
    {
        return [
            \OSW3\WpSiteCore\MenuLocations\PrimaryMenuLocation::class,
            \OSW3\WpSiteCore\MenuLocations\SecondaryMenuLocation::class,
        ];
    }
}