<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Services;

use WP_Taxonomy;

final class TaxonomyService
{
    /**
     * Enregistre une taxonomie personnalisée s'il elle n'existe pas déjà.
     *
     * @param string $taxonomy Identifiant unique de la taxonomie (ex: 'event_category')
     * @param array<string>|string $objectTypes CPT associés (ex: 'event' ou ['event', 'post'])
     * @param string $singular Nom au singulier (ex: 'Catégorie d'événement')
     * @param string $plural Nom au pluriel (ex: 'Catégories d'événement')
     * @param array $customArgs Surcharges spécifiques pour register_taxonomy()
     */
    public static function register(
        string $taxonomy,
        array|string $objectTypes,
        string $singular,
        string $plural,
        array $customArgs = []
    ): bool {
        if (self::exists($taxonomy)) {
            return true;
        }

        $labels = [
            'name'              => $plural,
            'singular_name'     => $singular,
            'search_items'      => sprintf(__('Rechercher des %s', 'wp-site-core'), strtolower($plural)),
            'all_items'         => sprintf(__('Toutes les %s', 'wp-site-core'), strtolower($plural)),
            'parent_item'       => sprintf(__('%s parente', 'wp-site-core'), $singular),
            'parent_item_colon' => sprintf(__('%s parente :', 'wp-site-core'), $singular),
            'edit_item'         => sprintf(__('Modifier la %s', 'wp-site-core'), strtolower($singular)),
            'update_item'       => sprintf(__('Mettre à jour la %s', 'wp-site-core'), strtolower($singular)),
            'add_new_item'      => sprintf(__('Ajouter une %s', 'wp-site-core'), strtolower($singular)),
            'new_item_name'     => sprintf(__('Nom de la nouvelle %s', 'wp-site-core'), strtolower($singular)),
            'menu_name'         => $plural,
        ];

        $defaultArgs = [
            'labels'            => $labels,
            'hierarchical'      => true, // true par défaut (style catégorie), passer false pour style tag
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true, // Éditeur Gutenberg
            'query_var'         => true,
            'rewrite'           => ['slug' => $taxonomy],
        ];

        $args = array_replace_recursive($defaultArgs, $customArgs);
        $result = register_taxonomy($taxonomy, $objectTypes, $args);

        return !is_wp_error($result);
    }

    /**
     * Supprime la déclaration d'une taxonomie de la session WP courante.
     */
    public static function unregister(string $taxonomy): bool
    {
        if (!self::exists($taxonomy)) {
            return false;
        }

        $result = unregister_taxonomy($taxonomy);

        return !is_wp_error($result);
    }

    /**
     * Vérifie si une taxonomie existe.
     */
    public static function exists(string $taxonomy): bool
    {
        return taxonomy_exists($taxonomy);
    }

    /**
     * Récupère l'objet WP_Taxonomy complet.
     */
    public static function get(string $taxonomy): ?WP_Taxonomy
    {
        return get_taxonomy($taxonomy) ?: null;
    }

    // =========================================================================
    // GESTION DES TERMES (Categories, Tags, Custom Terms)
    // =========================================================================

    /**
     * Crée un terme dans une taxonomie donnée s'il n'existe pas déjà.
     *
     * @return int ID du terme (term_id) ou 0 en cas d'échec
     */
    public static function createTerm(
        string $name, 
        string $taxonomy = 'category', 
        string $slug = '', 
        int $parentId = 0,
        string $description = ''
    ): int {
        $termId = self::getTermId($name, $taxonomy);

        if ($termId !== null) {

            // Optionnel : Mettre à jour la description si elle existe déjà et doit être mise à jour
            if ($description !== '') {
                wp_update_term($termId, $taxonomy, ['description' => $description]);
            }

            return $termId;
        }

        $args = [];

        if ($slug !== '') {
            $args['slug'] = $slug;
        }

        if ($description !== '') {
            $args['description'] = $description;
        }

        if ($parentId > 0 && is_taxonomy_hierarchical($taxonomy)) {
            $args['parent'] = $parentId;
        }

        $result = wp_insert_term($name, $taxonomy, $args);

        if (is_wp_error($result)) {
            return 0;
        }

        return (int) $result['term_id'];
    }

    /**
     * Récupère l'ID d'un terme d'après son nom ou son slug.
     */
    public static function getTermId(string $term, string $taxonomy = 'category'): ?int
    {
        // Recherche par slug ou par nom
        $existing = term_exists($term, $taxonomy);

        if (is_array($existing)) {
            return (int) $existing['term_id'];
        }

        return null;
    }

    /**
     * Récupère l'ID d'un terme d'après son slug.
     */
    public static function getTermIdBySlug(string $slug, string $taxonomy = 'category'): ?int
    {
        $term = get_term_by('slug', $slug, $taxonomy);

        if (!$term) {
            return null;
        }

        return (int) $term->term_id;
    }



    /**
     * Supprime un terme d'une taxonomie par son ID.
     */
    public static function deleteTermById(int $termId, string $taxonomy = 'category'): bool
    {
        $result = wp_delete_term($termId, $taxonomy);

        return !is_wp_error($result) && (bool) $result;
    }

    /**
     * Supprime un terme d'une taxonomie par son nom ou son slug.
     */
    public static function deleteTermByName(string $name, string $taxonomy = 'category'): bool
    {
        $termId = self::getTermId($name, $taxonomy);

        if ($termId === null) {
            return false;
        }

        return self::deleteTermById($termId, $taxonomy);
    }

    public static function deleteTermBySlug(string $slug, string $taxonomy = 'category'): bool
    {
        $termId = self::getTermIdBySlug($slug, $taxonomy);

        if ($termId === null) {
            return false;
        }

        return self::deleteTermById($termId, $taxonomy);
    }

    /**
     * Associe un ensemble de termes à un post (Page, CPT ou Post).
     *
     * @param int $postId ID de l'article ou CPT
     * @param array<int|string> $terms IDs, slugs ou noms des termes
     * @param string $taxonomy Nom de la taxonomie (ex: 'category', 'post_tag', 'event_category')
     * @param bool $append Si true, ajoute aux termes existants au lieu de remplacer
     */
    public static function setPostTerms(int $postId, array $terms, string $taxonomy = 'category', bool $append = false): bool
    {
        $result = wp_set_object_terms($postId, $terms, $taxonomy, $append);

        return !is_wp_error($result);
    }

    // =========================================================================
    // RACCOURCIS NATIFS (category & post_tag)
    // =========================================================================

    /**
     * Raccourci pour créer une catégorie standard (taxonomie 'category').
     */
    public static function createCategory(string $name, string $slug = '', int $parentId = 0): int
    {
        return self::createTerm($name, 'category', $slug, $parentId);
    }

    public static function deleteCategory(string $slug): bool
    {
        return self::deleteTermBySlug($slug, 'category');
    }

    /**
     * Raccourci pour créer un étiquette/tag standard (taxonomie 'post_tag').
     */
    public static function createTag(string $name, string $slug = ''): int
    {
        return self::createTerm($name, 'post_tag', $slug);
    }

    public static function deleteTag(string $slug): bool
    {
        return self::deleteTermBySlug($slug, 'post_tag');
    }
}