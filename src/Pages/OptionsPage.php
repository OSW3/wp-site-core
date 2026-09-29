<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Pages;

use OSW3\WpSiteCore\Module;
use OSW3\WpSiteCore\Config\Options;
use OSW3\WpSiteCore\Abstract\AbstractAdminPage;

final class OptionsPage extends AbstractAdminPage
{
    private readonly Options $options;

    public function __construct() {
        $this->options = new Options();
    }

    public function getSlug(): string
    {
        return 'wp-site-core-options';
    }

    public function getTitle(): string
    {
        return __('WP Site : Réglages Globaux', Module::getDomain());
    }

    public function getTabLabel(): string
    {
        return __('Réglages Globaux', Module::getDomain());
    }
    
    protected function renderContent(): void
    {
        $this->handleSave();

        $maintenanceMode = (bool) $this->options->get('maintenance_mode', false);
        $environmentName = (string) $this->options->get('environment_name', 'production');
        ?>
        <form method="post" action="">
            <?php wp_nonce_field('wp_site_core_save_options', 'wp_site_core_nonce'); ?>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><?php esc_html_e('Nom de l\'environnement', Module::getDomain()); ?></th>
                        <td>
                            <input type="text" name="environment_name" value="<?php echo esc_attr($environmentName); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Indicateur visuel pour différencier local, staging et production.', Module::getDomain()); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Mode Maintenance global', Module::getDomain()); ?></th>
                        <td>
                            <label for="maintenance_mode">
                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" <?php checked($maintenanceMode, true); ?> />
                                <?php esc_html_e('Activer le verrouillage du site pour l\'écosystème', Module::getDomain()); ?>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php submit_button(__('Enregistrer les modifications', Module::getDomain())); ?>
        </form>
        <?php
    }

    private function handleSave(): void
    {
        if (!isset($_POST['wp_site_core_nonce'])) {
            return;
        }

        if (!wp_verify_nonce((string) $_POST['wp_site_core_nonce'], 'wp_site_core_save_options')) {
            wp_die(esc_html__('Action non autorisée.', Module::getDomain()));
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        $this->options->set('environment_name', sanitize_text_field($_POST['environment_name'] ?? 'production'));
        $this->options->set('maintenance_mode', isset($_POST['maintenance_mode']));

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Réglages enregistrés avec succès.', Module::getDomain()) . '</p></div>';
    }
}