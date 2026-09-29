<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Services;

use WP_Term;

final class MenuService
{
    /**
     * Déclare un ou plusieurs emplacements de menu (Theme Locations).
     *
     * @param array<string, string> $locations Tableau associative ['location_slug' => 'Nom Lisible']
     */
    public static function registerLocations(array $locations): void
    {
        if (empty($locations)) {
            return;
        }

        // On enregistre les emplacements immédiatement si after_setup_theme est déjà passé,
        // sinon on s'accroche au hook.
        // if (did_action('after_setup_theme')) {
        //     register_nav_menus($locations);
        // } else {
        //     add_action('after_setup_theme', static function () use ($locations): void {
        //         register_nav_menus($locations);
        //     });
        // }
    }

    /**
     * Supprime la déclaration d'un ou plusieurs emplacements de menu.
     *
     * @param array<string> $locationSlugs Slugs des emplacements à retirer
     */
    public static function unregisterLocations(array $locationSlugs): void
    {
        foreach ($locationSlugs as $slug) {
            unregister_nav_menu($slug);
        }
    }

    /**
     * Crée un menu de navigation s'il n'existe pas déjà.
     *
     * @return int ID du menu créé ou existant (0 en cas d'erreur)
     */
    public static function create(string $menuName): int
    {
        $menuExists = wp_get_nav_menu_object($menuName);

        if ($menuExists) {
            return (int) $menuExists->term_id;
        }

        $menuId = wp_create_nav_menu($menuName);

        return is_wp_error($menuId) ? 0 : (int) $menuId;
    }

    /**
     * Assigne un menu existant à un emplacement (Theme Location).
     */
    public static function assignToLocation(int $menuId, string $locationSlug): void
    {
        if ($menuId <= 0) {
            return;
        }

        $locations = get_theme_mod('nav_menu_locations', []);
        
        // Assigne seulement si l'emplacement n'a pas déjà un menu attribué
        if (!isset($locations[$locationSlug]) || empty($locations[$locationSlug])) {
            $locations[$locationSlug] = $menuId;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    /**
     * Ajoute une page WordPress existante à un menu.
     */
    public static function addPage(int $menuId, int $pageId, string $customTitle = '', int $parentId = 0): int
    {
        $page = get_post($pageId);

        if (!$page) {
            return 0;
        }

        return self::addItem($menuId, [
            'menu-item-title'     => $customTitle !== '' ? $customTitle : $page->post_title,
            'menu-item-object'    => 'page',
            'menu-item-object-id' => $pageId,
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish',
            'menu-item-parent-id' => $parentId,
        ]);
    }

    /**
     * Ajoute un lien personnalisé (URL externe ou relative) à un menu.
     */
    public static function addCustomLink(int $menuId, string $title, string $url, int $parentId = 0): int
    {
        return self::addItem($menuId, [
            'menu-item-title'     => $title,
            'menu-item-url'       => $url,
            'menu-item-type'      => 'custom',
            'menu-item-status'    => 'publish',
            'menu-item-parent-id' => $parentId,
        ]);
    }

    /**
     * Ajoute un élément générique à un menu via wp_update_nav_menu_item().
     */
    public static function addItem(int $menuId, array $itemData): int
    {
        $itemId = wp_update_nav_menu_item($menuId, 0, $itemData);

        return is_wp_error($itemId) ? 0 : (int) $itemId;
    }

    /**
     * Supprime un menu complet par son nom ou son ID.
     */
    public static function delete(string|int $menu): bool
    {
        $result = wp_delete_nav_menu($menu);

        return !is_wp_error($result) && $result;
    }

    public static function deleteBySlug(string $slug): bool
    {
        return self::delete($slug);
    }

    /**
     * Vérifie si un menu existe.
     */
    public static function exists(string|int $menu): bool
    {
        return wp_get_nav_menu_object($menu) !== false;
    }

    /**
     * Récupère l'objet WP_Term d'un menu.
     */
    public static function get(string|int $menu): ?WP_Term
    {
        $menuObject = wp_get_nav_menu_object($menu);

        return $menuObject instanceof WP_Term ? $menuObject : null;
    }
}