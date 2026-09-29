<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Register;

final class MenusRegister
{
    public static function register(): array
    {
        return [
            \OSW3\WpSiteCore\Menus\PluginsMenu::class,
            \OSW3\WpSiteCore\Menus\MenusMenu::class,
        ];
    }
}