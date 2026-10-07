<?php
/**
 * Title: Hero Section
 * Description: Hero section for websites.
 * Categories: components, status
 * Keywords: hero, banner, section, header
 * Slug: wp-site-core/component/hero-section
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args                  = $args ?? [];
$title                 = $args['title'] ?? 'Solutions sur-mesure pour votre entreprise';
$description           = $args['description'] ?? 'Nous concevons des architectures web performantes et évolutives pour propulser votre activité.';
$primary_button_text   = $args['primary_button_text'] ?? 'Démarrer un projet';
$primary_button_link   = $args['primary_button_link'] ?? '#contact';
$secondary_button_text = $args['secondary_button_text'] ?? 'En savoir plus';
$secondary_button_link = $args['secondary_button_link'] ?? '#services';
$media                 = $args['media'] ?? 'assets/images/hero-illustration.svg';
?>
<section class="hero">
    <div class="container">
        <div class="hero__wrapper">
            <div class="hero__content">
                <h1 class="hero__title">
                    <?php echo esc_html($title); ?>
                </h1>
                <p class="hero__description">
                    <?php echo esc_html($description); ?>
                </p>
                <div class="hero__actions">
                    <a href="<?php echo esc_url($primary_button_link); ?>" class="btn btn--primary"><?php echo esc_html($primary_button_text); ?></a>
                    <a href="<?php echo esc_url($secondary_button_link); ?>" class="btn btn--secondary"><?php echo esc_html($secondary_button_text); ?></a>
                </div>
            </div>
            <div class="hero__media">
                <img src="<?php echo esc_url(get_theme_file_uri($media)); ?>" alt="Illustration Hero" class="hero__image" />
            </div>
        </div>
    </div>
</section>