<?php 
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface PostTypeInterface
{
    /**
     * Get the slug of the post type.
     * 
     * @return string The slug of the post type.
     */
    public static function slug(): string;

    /**
     * Get the title of the post type.
     * 
     * @return string The title of the post type.
     */
    public static function title(): string;

    /**
     * Get the plural form of the post type.
     * 
     * @return string The plural form of the post type.
     */
    public static function plural(): string;

    /**
     * Get the arguments for registering the post type.
     * 
     * @return array The arguments for the post type.
     */
    public static function args(): array;
}