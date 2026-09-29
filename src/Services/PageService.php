<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Services;

use WP_Post;
use WP_Query;

final class PageService
{
    /**
     * Crée une page si elle n'existe pas déjà d'après son slug.
     * 
     * @param string $title Le titre de la page.
     * @param string $content Le contenu de la page.
     * @param string $slug Le slug de la page.
     * @param int $parentId L'ID de la page parente (facultatif).
     * 
     * @return int L'ID de la page créée ou existante.
     */
    public static function create(string $title, string $content, string $slug, int $parentId = 0): int
    {
        $pageId = self::getIdBySlug($slug);
        
        if ($pageId !== null) {
            // Si la page est dans la corbeille, on la restaure au lieu de la dupliquer
            if (get_post_status($pageId) === 'trash') {
                self::restoreById($pageId);
            }
            return $pageId;
        }

        $page = [
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'    => $slug,
            'post_parent'  => $parentId,
        ];

        $insertedId = wp_insert_post($page);

        return is_wp_error($insertedId) ? 0 : (int) $insertedId;
    }

    /**
     * Récupère une page par son ID.
     * 
     * @param int $id L'ID de la page.
     * 
     * @return WP_Post|null La page correspondante ou null si elle n'existe pas.
     */
    public static function getById(int $id): ?WP_Post
    {
        $page = get_post($id);
        return $page !== null && $page->post_type === 'page' ? $page : null;
    }

    /**
     * Récupère une page par son slug.
     * 
     * @param string $slug Le slug de la page.
     * 
     * @return WP_Post|null La page correspondante ou null si elle n'existe pas.
     */
    public static function getBySlug(string $slug): ?WP_Post
    {
        $pageId = self::getIdBySlug($slug);

        return $pageId !== null ? get_post($pageId) : null;
    }

    /**
     * Récupère l'ID d'une page par son slug.
     * 
     * @param string $slug Le slug de la page.
     * 
     * @return int|null L'ID de la page correspondante ou null si elle n'existe pas.
     */
    public static function getIdBySlug(string $slug): ?int
    {
        $query = new WP_Query([
            'name'           => $slug,
            'post_type'      => 'page',
            'post_status'    => ['publish', 'draft', 'private', 'future', 'trash'],
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'cache_results'  => true,
        ]);

        return !empty($query->posts) ? (int) $query->posts[0] : null;
    }

    /**
     * Met à jour le contenu d'une page par son slug.
     * 
     * @param string $slug Le slug de la page.
     * @param string $content Le nouveau contenu de la page.
     * 
     * @return void
     */
    public static function updateContentBySlug(string $slug, string $content): void
    {
        $pageId = self::getIdBySlug($slug);

        if ($pageId !== null) {
            self::updateContentById($pageId, $content);
        }
    }

    /**
     * Met à jour le contenu d'une page par son ID.
     * 
     * @param int $id L'ID de la page.
     * @param string $content Le nouveau contenu de la page.
     * 
     * @return void
     */
    public static function updateContentById(int $id, string $content): void
    {
        wp_update_post([
            'ID'           => $id,
            'post_content' => $content,
        ]);
    }

    /**
     * Envoie la page à la corbeille sans la supprimer définitivement.
     */
    public static function trashBySlug(string $slug): void
    {
        $pageId = self::getIdBySlug($slug);

        if ($pageId !== null) {
            self::trashById($pageId);
        }
    }

    /**
     * Envoie la page à la corbeille sans la supprimer définitivement par son ID.
     * 
     * @param int $id L'ID de la page.
     * 
     * @return void
     */
    public static function trashById(int $id): void
    {
        wp_trash_post($id);
    }

    /**
     * Restaure une page depuis la corbeille par son ID.
     * 
     * @param int $id L'ID de la page.
     * 
     * @return void
     */
    public static function restoreById(int $id): void
    {
        wp_untrash_post($id);
    }

    /**
     * Supprime une page par son slug.
     * 
     * @param string $slug Le slug de la page.
     * @param bool $forceDelete Indique si la suppression doit être définitive.
     * 
     * @return void
     */
    public static function deleteBySlug(string $slug, bool $forceDelete = true): void
    {
        $pageId = self::getIdBySlug($slug);

        if ($pageId !== null) {
            self::deleteById($pageId, $forceDelete);
        }
    }

    /**
     * Supprime une page par son ID.
     * 
     * @param int $id L'ID de la page.
     * @param bool $forceDelete Indique si la suppression doit être définitive.
     * 
     * @return void
     */
    public static function deleteById(int $id, bool $forceDelete = true): void
    {
        wp_delete_post($id, $forceDelete);
    }

    /**
     * Vérifie si une page existe par son slug.
     * 
     * @param string $slug Le slug de la page.
     * 
     * @return bool True si la page existe, false sinon.
     */
    public static function exists(string $slug): bool
    {
        return self::getIdBySlug($slug) !== null;
    }



    public static function setAsHomePage(int $id): void
    {
        // Sauvegarde la configuration d'accueil actuelle avant modification
        $previousConfig = [
            'show_on_front' => get_option('show_on_front', 'posts'),
            'page_on_front' => get_option('page_on_front', 0),
        ];
        update_option('wp_site_starter_previous_front_page', $previousConfig, false);

        // Définit la page "Home" comme page d'accueil statique
        update_option('show_on_front', 'page');
        update_option('page_on_front', $id);
    }
    
    public static function restorePreviousFrontPageConfig(): void
    {
        $previousConfig = get_option('wp_site_starter_previous_front_page', null);
        if ($previousConfig !== null) {
            update_option('show_on_front', $previousConfig['show_on_front']);
            update_option('page_on_front', $previousConfig['page_on_front']);
            delete_option('wp_site_starter_previous_front_page');
        }
    }
}