<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Abstract;

abstract class AbstractAdminPage
{
    /**
     * @var AbstractAdminPage[]
     */
    private array $registeredPages = [];

    /**
     * Get the slug of the admin page.
     *
     * @return string The slug of the admin page.
     */
    abstract public function getSlug(): string;

    /**
     * Get the title of the admin page.
     *
     * @return string The title of the admin page.
     */
    abstract public function getTitle(): string;

    /**
     * Get the label of the tab for the admin page.
     *
     * @return string The tab label.
     */
    abstract public function getTabLabel(): string;

    /**
     * Render the content of the admin page.
     *
     * @return void
     */
    abstract protected function renderContent(): void;

    /**
     * Permet d'injecter la liste globale des pages d'administration.
     *
     * @param AbstractAdminPage[] $pages
     */
    public function setRegisteredPages(array $pages): void
    {
        $this->registeredPages = $pages;
    }

    /**
     * Render the admin page.
     *
     * @return void
     */
    public function render(): void
    {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html($this->getTitle()); ?></h1>
            <hr class="wp-header-end">

            <?php $this->renderTabs(); ?>

            <div class="wp-site-core-content" style="margin-top: 20px;">
                <?php $this->renderContent(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render the tabs for the admin page.
     *
     * @return void
     */
    private function renderTabs(): void
    {
        if (count($this->registeredPages) <= 1) {
            return;
        }

        $currentSlug = $this->getSlug();

        echo '<nav class="nav-tab-wrapper" style="margin-bottom: 20px;">';
        foreach ($this->registeredPages as $page) {
            $activeClass = ($page->getSlug() === $currentSlug) ? ' nav-tab-active' : '';
            $url         = admin_url('admin.php?page=' . $page->getSlug());

            printf(
                '<a href="%s" class="nav-tab%s">%s</a>',
                esc_url($url),
                esc_attr($activeClass),
                esc_html($page->getTabLabel())
            );
        }
        echo '</nav>';
    }
}