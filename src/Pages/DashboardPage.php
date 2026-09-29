<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Pages;

use OSW3\WpSiteCore\Module;
use OSW3\WpSiteCore\Registry\PluginRegistry;
use OSW3\WpSiteCore\Abstract\AbstractAdminPage;

final class DashboardPage extends AbstractAdminPage
{
    private readonly PluginRegistry $registry;

    public function __construct() {
        $this->registry = new PluginRegistry();
    }

    /**
     * Get the slug of the admin page.
     *
     * @return string The slug of the admin page.
     */
    public function getSlug(): string
    {
        return 'wp-site-core';
    }

    /**
     * Get the title of the admin page.
     *
     * @return string The title of the admin page.
     */
    public function getTitle(): string
    {
        return __('WP Site : Dashboard Écosystème', Module::getDomain());
    }

    /**
     * Get the label of the tab for the admin page.
     *
     * @return string The tab label.
     */
    public function getTabLabel(): string
    {
        return __('Écosystème & Modules', Module::getDomain());
    }

    /**
     * Render the content of the admin page.
     *
     * @return void
     */
    protected function renderContent(): void
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        // 1. Récupération dynamique des modules enregistrés
        $modules = $this->registry->getAll();

        ?>
        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th scope="col" style="width: 20%;"><?php esc_html_e('Module', Module::getDomain()); ?></th>
                    <th scope="col" style="width: 15%;"><?php esc_html_e('ID', Module::getDomain()); ?></th>
                    <th scope="col" style="width: 10%;"><?php esc_html_e('Version', Module::getDomain()); ?></th>
                    <th scope="col"><?php esc_html_e('Description', Module::getDomain()); ?></th>
                    <th scope="col" style="width: 12%;"><?php esc_html_e('État', Module::getDomain()); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($modules)): ?>
                    <tr>
                        <td colspan="5"><?php esc_html_e('Aucun module enregistré pour le moment.', Module::getDomain()); ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($modules as $id => $moduleClass): ?>
                        <?php 
                            // $moduleClass contient "OSW3\WpSiteStarter\Module"
                            $file     = $moduleClass::getFile(); // 'wp-site-starter/wp-site-starter.php'
                            $isActive = is_plugin_active($file);
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($moduleClass::getName()); ?></strong></td>
                            <td><code><?php echo esc_html($moduleClass::getId()); ?></code></td>
                            <td><span class="badge"><?php echo esc_html($moduleClass::getVersion()); ?></span></td>
                            <td><?php echo esc_html($moduleClass::getDescription()); ?></td>
                            <td>
                                <?php if ($isActive): ?>
                                    <span style="color: #00a32a; font-weight: 600;">● <?php esc_html_e('Actif', Module::getDomain()); ?></span>
                                <?php else: ?>
                                    <span style="color: #d63638; font-weight: 600;">○ <?php esc_html_e('Inactif', Module::getDomain()); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
}