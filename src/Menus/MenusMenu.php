<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Menus;

use OSW3\WpSiteCore\Module;

final class MenusMenu
{
    public function __construct()
    {

        add_action('after_setup_theme', function () {
            register_nav_menus([
                'primary_navigation' => __('Menu Principal', Module::getDomain()),
            ]);
        });


        add_action('admin_menu', function () {
            remove_submenu_page('themes.php', 'nav-menus.php');

            add_menu_page(
                __('Menus', Module::getDomain()),        // Titre de la page
                __('Menus', Module::getDomain()),        // Titre dans le menu admin
                'edit_theme_options',              // Capacité requise
                'nav-menus.php',                   // Slug URL cible (le gestionnaire natif WP)
                '',                                // Pas de callback nécessaire
                'dashicons-menu',                  // Icône Dashicon
                60                                 // Position dans le menu
            );
        });

        add_filter('parent_file', function ($parent_file) {
            global $pagenow;

            if ($pagenow === 'nav-menus.php') {
                return 'nav-menus.php';
            }

            return $parent_file;
        });

    }
}