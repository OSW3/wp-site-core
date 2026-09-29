<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface PostInterface
{
    /**
     * Get the slug for the post type.
     *
     * @return string
     */
    public static function slug(): string;

    /**
     * Get the singular title for the post type.
     *
     * @return string
     */
    public static function title(): string;

    /**
     * Get the plural title for the post type.
     *
     * @return string
     */
    public static function plural(): string;

    /**
     * Get the arguments for the post type.
     *
     * @return array
     */
    public static function args(): array;
}