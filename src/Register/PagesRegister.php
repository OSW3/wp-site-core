<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Register;

final class PagesRegister
{
    public static function register(): array
    {
        return [
            \OSW3\WpSiteCore\Pages\DashboardPage::class,
            \OSW3\WpSiteCore\Pages\OptionsPage::class,
            \OSW3\WpSiteCore\Pages\TestPage::class,
        ];
    }
}