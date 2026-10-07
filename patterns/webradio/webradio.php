<?php
/**
 * Title: Alert Info
 * Description: Alert component for displaying informational messages.
 * Categories: components, status
 * Keywords: alert, info, information, message
 * Slug: wp-site-core/component/alert-info
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args     = $args ?? [];
$src      = $args['src'] ?? '';
$title    = $args['title'] ?? __('Webradio', 'wp-theme-test');
$api_url  = $args['api_url'] ?? '';
$volume   = $args['volume'] ?? 0.8;
$interval = $args['interval'] ?? 30;

// 2. Default metadata fields for the webradio component
$fields   = $args['fields'] ?? [
    'artist' => 'artist', 
    'title' => 'title', 
    'version' => 'version', 
    'artwork' => 'artwork',
];
$valid_url = static function ($value) {
    if (!is_string($value) || preg_match('/[\x00-\x20\x7f\\\\]/', $value)) return false;
    if (str_starts_with($value, '/') && !str_starts_with($value, '//')) return true;
    return filter_var($value, FILTER_VALIDATE_URL) !== false
        && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)
        && parse_url($value, PHP_URL_USER) === null && parse_url($value, PHP_URL_PASS) === null;
};
if (!$valid_url($src) || !is_string($title) || trim($title) === ''
    || !is_string($api_url) || ($api_url !== '' && !$valid_url($api_url))
    || !is_numeric($volume) || !is_finite((float) $volume) || $volume < 0 || $volume > 1
    || filter_var($interval, FILTER_VALIDATE_INT) === false || $interval < 10 || $interval > 300
    || !is_array($fields) || !isset($fields['title'])) {
    _doing_it_wrong('component/webradio', 'Invalid stream, API URL, title, fields, volume (0-1) or interval (10-300 seconds).', '1.0.0');
    return;
}
foreach ($fields as $key => $path) {
    if (!in_array($key, ['artist', 'title', 'version', 'artwork'], true)
        || !is_string($path) || !preg_match('/^[A-Za-z0-9_-]+(?:\.[A-Za-z0-9_-]+)*$/D', $path)
        || array_intersect(explode('.', $path), ['__proto__', 'prototype', 'constructor'])) {
        _doing_it_wrong('component/webradio', 'Metadata fields must be safe dot-separated paths for artist, title, version or artwork.', '1.0.0');
        return;
    }
}

// 3. Generate unique ID for the webradio component
$id = wp_unique_id('webradio-');
$config = [
    'src' => $src, 'apiUrl' => $api_url,
    'interval' => (int) $interval, 'fields' => $fields, 'volume' => (float) $volume,
    'states' => [
        'stopped' => __('Arrêt', 'wp-theme-test'),
        'loading' => __('Chargement en cours…', 'wp-theme-test'),
        'playing' => __('En lecture', 'wp-theme-test'),
        'error' => __('Erreur de lecture', 'wp-theme-test'),
    ],
];
?>
<section class="webradio" data-webradio data-radio-config="<?php echo esc_attr(wp_json_encode($config)); ?>" aria-labelledby="<?php echo esc_attr($id); ?>-title">
    <h2 class="webradio__title" id="<?php echo esc_attr($id); ?>-title"><?php echo esc_html($title); ?></h2>
    <div class="webradio__track">
        <div class="webradio__artwork">
            <img data-radio-artwork alt="<?php esc_attr_e('Pochette du morceau en cours', 'wp-theme-test'); ?>" referrerpolicy="no-referrer" hidden>
            <span data-radio-artwork-placeholder><?php esc_html_e('Pochette indisponible', 'wp-theme-test'); ?></span>
        </div>
        <dl class="webradio__metadata" aria-live="polite" aria-atomic="true">
            <div><dt><?php esc_html_e('Artiste', 'wp-theme-test'); ?></dt><dd data-radio-artist><?php esc_html_e('Non renseigné', 'wp-theme-test'); ?></dd></div>
            <div><dt><?php esc_html_e('Titre', 'wp-theme-test'); ?></dt><dd data-radio-title><?php esc_html_e('Informations disponibles pendant l’écoute', 'wp-theme-test'); ?></dd></div>
            <div data-radio-version-row hidden><dt><?php esc_html_e('Version', 'wp-theme-test'); ?></dt><dd data-radio-version></dd></div>
        </dl>
    </div>
    <audio id="<?php echo esc_attr($id); ?>-audio" preload="none" hidden></audio>
    <div class="webradio__controls" data-radio-controls hidden>
        <button type="button" class="btn btn--primary" data-radio-play aria-controls="<?php echo esc_attr($id); ?>-audio"><?php esc_html_e('Lecture', 'wp-theme-test'); ?></button>
        <button type="button" class="btn btn--secondary" data-radio-stop aria-controls="<?php echo esc_attr($id); ?>-audio" disabled><?php esc_html_e('Arrêt', 'wp-theme-test'); ?></button>
        <label class="webradio__volume" for="<?php echo esc_attr($id); ?>-volume">
            <span><?php esc_html_e('Volume', 'wp-theme-test'); ?> <output data-radio-volume-value for="<?php echo esc_attr($id); ?>-volume"><?php echo (int) round((float) $volume * 100); ?>%</output></span>
            <input id="<?php echo esc_attr($id); ?>-volume" data-radio-volume type="range" min="0" max="100" step="1" value="<?php echo (int) round((float) $volume * 100); ?>">
        </label>
    </div>
    <p class="webradio__state" data-radio-state role="status" aria-live="polite"><?php echo esc_html($config['states']['stopped']); ?></p>
    <noscript><p><?php esc_html_e('Activez JavaScript pour utiliser ce lecteur, ou ouvrez le flux avec le lien ci-dessous.', 'wp-theme-test'); ?></p></noscript>
    <p class="webradio__status" data-radio-status role="status" hidden></p>
    <p class="webradio__status" data-radio-audio-status role="status" hidden><?php esc_html_e('Impossible de lire le flux radio. Utilisez le lien ci-dessous.', 'wp-theme-test'); ?></p>
    <span data-radio-error hidden><?php esc_html_e('Impossible de récupérer les références du morceau. Vérifiez l’URL, les champs et les autorisations CORS de la source.', 'wp-theme-test'); ?></span>
    <span data-radio-empty hidden><?php esc_html_e('Aucune référence de morceau fournie actuellement par la radio.', 'wp-theme-test'); ?></span>
    <span data-radio-unknown hidden><?php esc_html_e('Non renseigné', 'wp-theme-test'); ?></span>
    <span data-radio-artwork-error hidden><?php esc_html_e('Impossible de charger la pochette du morceau.', 'wp-theme-test'); ?></span>
    <p class="webradio__link"><a href="<?php echo esc_url($src); ?>"><?php esc_html_e('Ouvrir le flux radio', 'wp-theme-test'); ?></a></p>
</section>