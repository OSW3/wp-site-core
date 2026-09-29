<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Pages;

use OSW3\WpSiteCore\Module;
use OSW3\WpSiteCore\Abstract\AbstractAdminPage;

final class TestPage extends AbstractAdminPage
{
    public function __construct() {}

    public function getSlug(): string
    {
        return 'wp-site-core-test';
    }

    public function getTitle(): string
    {
        return __('WP Site : Page de Test', Module::getDomain());
    }

    public function getTabLabel(): string
    {
        return __('Page de Test', Module::getDomain());
    }
    
    protected function renderContent(): void
    {
        ?>
        <h1>Page de test</h1>
        <?php
    }
}