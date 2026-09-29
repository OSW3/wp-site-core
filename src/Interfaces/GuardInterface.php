<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface GuardInterface
{
    /**
     * Check the guard conditions.
     *
     * @return array An array of issues found.
     */
    public static function check(): array;
}