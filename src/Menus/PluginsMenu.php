<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Menus;

use OSW3\WpSiteCore\Register\PagesRegister;

final class PluginsMenu
{
    private $pages = [];

    public function __construct()
    {
        foreach (PagesRegister::register() as $page) {
            $this->pages[] = new $page();
        }

        foreach ($this->pages as $page) {
            $page->setRegisteredPages($this->pages);
        }

        add_action('admin_menu', [$this, 'register']);
        // $this->register();
    }

    public function register(): void
    {
        $mainPage = $this->pages[0] ?? null;

        add_menu_page(
            $mainPage->getTitle(),
            'WP Site',
            'manage_options',
            $mainPage->getSlug(),
            [$mainPage, 'render'],
            'dashicons-shortcode',
            30
        );

        foreach ($this->pages as $page) {
            add_submenu_page(
                $mainPage->getSlug(),
                $page->getTitle(),
                $page->getTabLabel(),
                'manage_options',
                $page->getSlug(),
                [$page, 'render']
            );
        }
    }
}