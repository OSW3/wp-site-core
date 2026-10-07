<?php
/**
 * Title: Alert
 * Description: Alert component for displaying error messages.
 * Categories: components, status
 * Keywords: alert, error, message
 * Slug: wp-site-core/component/alert
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$title   = $args['title'] ?? 'Default :';
$message = $args['message'] ?? 'Message par défaut...';
$type    = $args['type'] ?? 'default';

// 2. Generate CSS classes based on BEM methodology
$badge_classes = array_filter([
    'alert',
    "alert--{$type}",
]);
?>
<div class="<?php echo esc_attr(implode(' ', $badge_classes)); ?>" role="alert" data-alert>
    <div class="container-fluid">
        <div class="alert__wrapper">
            <div class="alert__icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>

            <div class="alert__content">
                <strong class="alert__title"><?= esc_html( $title ); ?></strong>
                <span class="alert__message"><?= esc_html( $message ); ?></span>
            </div>

            <button type="button" class="alert__dismiss" aria-label="Fermer l'alerte" data-alert-dismiss>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</div>