<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Services;

use OSW3\WpSiteCore\Services\TaxonomyService;
use WP_Query;

final class PostService
{
    public static function create(
        string $title,
        string $content,
        string $slug,
        string $postType = 'post',
        string $excerpt = '',
        array $terms = [],
        array $meta = []
    ): int {
        $postId = self::getIdBySlug($slug, $postType);

        if ($postId !== null) {
            if (get_post_status($postId) === 'trash') {
                wp_untrash_post($postId);
            }
            return $postId;
        }

        $postData = [
            'post_title'   => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_status'  => 'publish',
            'post_type'    => $postType,
            'post_name'    => $slug,
        ];

        $insertedId = wp_insert_post($postData);

        if (is_wp_error($insertedId) || $insertedId === 0) {
            return 0;
        }

        // Association des taxonomies
        foreach ($terms as $taxonomy => $termSlugs) {
            TaxonomyService::setPostTerms($insertedId, (array) $termSlugs, $taxonomy);
        }

        // Enregistrement des post meta
        foreach ($meta as $metaKey => $metaValue) {
            update_post_meta($insertedId, $metaKey, $metaValue);
        }

        return (int) $insertedId;
    }

    public static function getIdBySlug(string $slug, string $postType = 'post'): ?int
    {
        $query = new WP_Query([
            'name'           => $slug,
            'post_type'      => $postType,
            'post_status'    => ['publish', 'draft', 'private', 'future', 'trash'],
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'cache_results'  => true,
        ]);

        return !empty($query->posts) ? (int) $query->posts[0] : null;
    }
}