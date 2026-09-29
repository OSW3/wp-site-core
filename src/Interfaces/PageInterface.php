<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface PageInterface
{
    /**
     * Get the slug of the page.
     * 
     * @return string The slug of the page.
     */
    public static function slug(): string;

    /**
     * Get the title of the page.
     * 
     * @return string The title of the page.
     */
    public static function title(): string;

    /**
     * Get the content of the page.
     * 
     * @return string The content of the page.
     */
    public static function content(): string;
}