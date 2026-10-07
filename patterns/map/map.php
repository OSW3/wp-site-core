<?php
/**
 * Title: Map
 * Description: Map component for displaying interactive maps.
 * Categories: components, status
 * Keywords: map, interactive, location, navigation
 * Slug: wp-site-core/component/map
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args         = $args ?? [];
$provider     = $args['provider'] ?? 'openstreetmap';
$lat          = $args['lat'] ?? 50.6292;
$lng          = $args['lng'] ?? 3.0573;
$zoom         = $args['zoom'] ?? 13;
$height       = $args['height'] ?? 400;
$title        = $args['title'] ?? __('Notre localisation', 'wp-theme-test');
$marker_label = $args['marker_label'] ?? $title;
$consent      = $args['consent'] ?? true;
$marker       = $args['marker'] ?? true;
$style        = $args['style'] ?? 'liberty';

// 2. Validation of input parameters
$providers    = ['google', 'openstreetmap', 'openfreemap', 'mapy'];
if (!in_array($provider, $providers, true)
    || !is_numeric($lat) || !is_finite((float) $lat) || abs((float) $lat) > 85.051129
    || !is_numeric($lng) || !is_finite((float) $lng) || abs((float) $lng) > 180
    || !is_numeric($zoom) || !is_finite((float) $zoom) || (float) $zoom < 0 || (float) $zoom > 19
    || filter_var($height, FILTER_VALIDATE_INT) === false || $height < 200 || $height > 2000
    || !is_string($title) || trim($title) === '' || !is_string($marker_label) || ($marker && trim($marker_label) === '')
    || !is_bool($consent) || !is_bool($marker)
    || !in_array($style, ['liberty', 'bright', 'positron'], true)) {
    _doing_it_wrong('component/map', 'Invalid provider, coordinates, zoom (0–19), height (200–2000), title, marker label, booleans or style.', '1.0.0');
    return;
}

// 3. Prepare map configuration
$api_key = $args['api_key'] ?? '';
if ($api_key === '' && $provider === 'google' && defined('WP_THEME_GOOGLE_MAPS_KEY')) {
    $api_key = WP_THEME_GOOGLE_MAPS_KEY;
}
if ($api_key === '' && $provider === 'mapy' && defined('WP_THEME_MAPY_KEY')) {
    $api_key = WP_THEME_MAPY_KEY;
}
if (!is_string($api_key)) {
    _doing_it_wrong('component/map', 'api_key must be a string.', '1.0.0');
    return;
}
$missing_key = in_array($provider, ['google', 'mapy'], true) && trim($api_key) === '';
if ($missing_key) {
    _doing_it_wrong('component/map', 'The selected provider requires a browser API key.', '1.0.0');
}
$lat = (float) $lat;
$lng = (float) $lng;
$zoom = (float) $zoom;
$location = $lat . ',' . $lng;
$external = $provider === 'google'
    ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($location)
    : ($provider === 'mapy'
        ? 'https://mapy.com/zakladni?x=' . $lng . '&y=' . $lat . '&z=' . $zoom
        : 'https://www.openstreetmap.org/?mlat=' . $lat . '&mlon=' . $lng . '#map=' . $zoom . '/' . $lat . '/' . $lng);
$iframe_url = '';
if ($provider === 'google' && !$missing_key) {
    $iframe_url = 'https://www.google.com/maps/embed/v1/' . ($marker ? 'place' : 'view') . '?' . http_build_query([
        'key' => $api_key,
        $marker ? 'q' : 'center' => $location,
        'zoom' => (int) $zoom,
    ], '', '&', PHP_QUERY_RFC3986);
}
$names = ['google' => 'Google Maps', 'openstreetmap' => 'OpenStreetMap', 'openfreemap' => 'OpenFreeMap', 'mapy' => 'Mapy.com'];

// 4. Generate unique ID and configuration array for the map
$id = wp_unique_id('map-');
$config = [
    'provider' => $provider, 'lat' => $lat, 'lng' => $lng, 'zoom' => $zoom,
    'marker' => $marker, 'markerLabel' => $marker_label, 'style' => $style,
    'apiKey' => $provider === 'mapy' ? $api_key : '',
    'iframeUrl' => $iframe_url, 'title' => $title,
    'locale' => [
        'NavigationControl.ZoomIn' => __('Zoom avant', 'wp-theme-test'),
        'NavigationControl.ZoomOut' => __('Zoom arrière', 'wp-theme-test'),
        'AttributionControl.ToggleAttribution' => __('Afficher les attributions', 'wp-theme-test'),
    ],
];
?>
<section class="map" aria-labelledby="<?php echo esc_attr($id); ?>-title" data-map data-map-config="<?php echo esc_attr(wp_json_encode($config)); ?>" data-consent="<?php echo $consent ? 'true' : 'false'; ?>"<?php if ($missing_key) : ?> data-map-unavailable<?php endif; ?> style="--map-height: <?php echo (int) $height; ?>px">
    <h2 id="<?php echo esc_attr($id); ?>-title" class="map__title"><?php echo esc_html($title); ?></h2>
    <div class="map__frame">
        <div id="<?php echo esc_attr($id); ?>" class="map__canvas" data-map-canvas hidden></div>
        <div class="map__placeholder" data-map-placeholder>
            <p><?php echo esc_html(sprintf(__('Afficher une carte fournie par %s transmet votre adresse IP et les données nécessaires au fournisseur.', 'wp-theme-test'), $names[$provider])); ?></p>
            <?php if (!$missing_key) : ?>
                <button type="button" class="btn btn--primary" data-map-load aria-controls="<?php echo esc_attr($id); ?>" hidden><?php esc_html_e('Afficher la carte', 'wp-theme-test'); ?></button>
            <?php endif; ?>
        </div>
    </div>
    <p class="map__status" data-map-status role="status"<?php if (!$missing_key) : ?> hidden<?php endif; ?>><?php if ($missing_key) : ?><?php esc_html_e('Carte indisponible : la clé API du fournisseur doit être configurée.', 'wp-theme-test'); ?><?php endif; ?></p>
    <span data-map-error hidden><?php esc_html_e('Impossible de charger la carte. Utilisez le lien ci-dessous ou réessayez.', 'wp-theme-test'); ?></span>
    <p class="map__link"><a href="<?php echo esc_url($external); ?>" target="_blank" rel="noopener"><?php esc_html_e('Ouvrir la localisation dans un nouvel onglet', 'wp-theme-test'); ?></a></p>
</section>
