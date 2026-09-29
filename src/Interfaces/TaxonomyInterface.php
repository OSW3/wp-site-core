<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Interfaces;

interface TaxonomyInterface
{
    /**
     * Get the name of the taxonomy.
     * 
     * @return string The name of the taxonomy.
     */
    public static function name(): string;

    /**
     * Get the slug of the taxonomy.
     * 
     * @return string The slug of the taxonomy.
     */
    public static function slug(): string;
    
    /**
     * Get the parent ID of the taxonomy.
     * 
     * @return int The parent ID of the taxonomy.
     */
    public static function parentId(): int;
}