<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Services;

use OSW3\WpSiteCore\Module;
use WP_Post_Type;

final class PostTypeService
{
    /**
     * Enregistre un Custom Post Type s'il n'existe pas déjà.
     *
     * @param string $postType Identifiant unique du CPT (ex: 'event', 'restaurant') - max 20 caractères.
     * @param string $singular Nom au singulier pour les libellés (ex: 'Événement')
     * @param string $plural Nom au pluriel pour les libellés (ex: 'Événements')
     * @param array $customArgs Surcharge d'arguments spécifiques pour register_post_type()
     */
    public static function register(
        string $postType,
        string $singular,
        string $plural,
        array $customArgs = []
    ): ?WP_Post_Type {
        if (self::exists($postType)) {
            return get_post_type_object($postType);
        }

        $labels = [
            'name'                  => $plural,
            'singular_name'         => $singular,
            'menu_name'             => $plural,
            'name_admin_bar'        => $singular,
            'add_new'               => __('Ajouter', Module::getDomain()),
            'add_new_item'          => sprintf(__('Ajouter un %s', Module::getDomain()), strtolower($singular)),
            'new_item'              => sprintf(__('Nouveau %s', Module::getDomain()), strtolower($singular)),
            'edit_item'             => sprintf(__('Modifier le %s', Module::getDomain()), strtolower($singular)),
            'view_item'             => sprintf(__('Voir le %s', Module::getDomain()), strtolower($singular)),
            'all_items'             => sprintf(__('Tous les %s', Module::getDomain()), strtolower($plural)),
            'search_items'          => sprintf(__('Rechercher des %s', Module::getDomain()), strtolower($plural)),
            'not_found'             => sprintf(__('Aucun %s trouvé.', Module::getDomain()), strtolower($singular)),
            'not_found_in_trash'    => sprintf(__('Aucun %s trouvé dans la corbeille.', Module::getDomain()), strtolower($singular)),
        ];

        $defaultArgs = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => $postType],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_rest'       => true, // Active l'éditeur Gutenberg
        ];

        $args = array_replace_recursive($defaultArgs, $customArgs);

        $result = register_post_type($postType, $args);

        return is_wp_error($result) ? null : $result;
    }

    /**
     * Supprime la déclaration d'un CPT de la session WordPress courante.
     */
    public static function unregister(string $postType): bool
    {
        if (!self::exists($postType)) {
            return false;
        }

        $result = unregister_post_type($postType);

        return !is_wp_error($result);
    }

    /**
     * Vérifie si un CPT est actuellement enregistré.
     */
    public static function exists(string $postType): bool
    {
        return post_type_exists($postType);
    }

    /**
     * Récupère l'objet WP_Post_Type complet.
     */
    public static function get(string $postType): ?WP_Post_Type
    {
        return get_post_type_object($postType) ?: null;
    }

    /**
     * Utile lors de l'activation/désactivation d'un module gérant des CPTs
     * pour regénérer les règles de réécriture d'URL sans conflit.
     */
    public static function flushRewriteRules(): void
    {
        flush_rewrite_rules();
    }
}