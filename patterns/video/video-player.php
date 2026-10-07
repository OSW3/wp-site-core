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
$args      = $args ?? [];
$source    = $args['source'] ?? 'local';
$src       = $args['src'] ?? '';
$title     = $args['title'] ?? __('Lecteur vidéo', 'wp-theme-test');
$poster    = $args['poster'] ?? '';
$preload   = $args['preload'] ?? 'metadata';
$ratio     = $args['ratio'] ?? '16/9';
$autoplay  = $args['autoplay'] ?? false;
$muted     = $args['muted'] ?? false;
$loop      = $args['loop'] ?? false;
$tracks    = $args['tracks'] ?? [];

// 2. Validation of input parameters
$valid_url = static function ($value) {
    if (!is_string($value) || preg_match('/[\x00-\x20\x7f\\\\]/', $value)) return false;
    if (str_starts_with($value, '/') && !str_starts_with($value, '//')) return true;
    return filter_var($value, FILTER_VALIDATE_URL) !== false
        && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)
        && parse_url($value, PHP_URL_USER) === null && parse_url($value, PHP_URL_PASS) === null;
};
if (!in_array($source, ['local', 'url', 'youtube'], true)
    || !is_string($title) || trim($title) === ''
    || !is_bool($autoplay) || !is_bool($muted) || !is_bool($loop)
    || ($autoplay && !$muted)
    || !in_array($preload, ['none', 'metadata', 'auto'], true)
    || !in_array($ratio, ['16/9', '4/3', '1/1', '9/16'], true)
    || !is_string($poster) || ($poster !== '' && !$valid_url($poster))
    || !is_array($tracks)) {
    _doing_it_wrong('component/video-player', 'Invalid source, title, poster, ratio, preload or options. Autoplay requires muted=true.', '1.0.0');
    return;
}
$video_id = '';
if ($source === 'youtube') {
    if (is_string($src) && preg_match('/^[A-Za-z0-9_-]{11}$/D', $src)) {
        $video_id = $src;
    } elseif (is_string($src) && $valid_url($src)) {
        $parts = parse_url($src);
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $video_id = ltrim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            if ($path === '/watch') {
                parse_str($parts['query'] ?? '', $query);
                $video_id = $query['v'] ?? '';
            } elseif (preg_match('~^/(?:embed|shorts|live)/([^/]+)/?$~', $path, $matches)) {
                $video_id = $matches[1];
            }
        }
    }
    if (!is_string($video_id) || !preg_match('/^[A-Za-z0-9_-]{11}$/D', $video_id) || $tracks !== []) {
        _doing_it_wrong('component/video-player', 'A valid YouTube video ID or URL is required. Configure YouTube captions on YouTube, not with tracks.', '1.0.0');
        return;
    }
    $external = 'https://www.youtube.com/watch?v=' . $video_id;
    $embed = 'https://www.youtube-nocookie.com/embed/' . $video_id . '?' . http_build_query([
        'autoplay' => (int) $autoplay, 'mute' => (int) $muted, 'loop' => (int) $loop,
        'playsinline' => 1, 'rel' => 0,
    ] + ($loop ? ['playlist' => $video_id] : []), '', '&', PHP_QUERY_RFC3986);
} else {
    if ($source === 'local' && is_int($src) && $src > 0) {
        $mime = get_post_mime_type($src);
        $src = is_string($mime) && str_starts_with($mime, 'video/') ? wp_get_attachment_url($src) : false;
    }
    if (!$valid_url($src) || ($source === 'url' && !preg_match('~^https?://~i', $src))) {
        _doing_it_wrong('component/video-player', 'Provide a video attachment ID, a root-relative local path or an HTTP(S) video URL.', '1.0.0');
        return;
    }
    $external = $src;
}
$default_tracks = 0;
foreach ($tracks as $track) {
    if (!is_array($track) || !$valid_url($track['src'] ?? null)
        || !in_array($track['kind'] ?? 'subtitles', ['subtitles', 'captions'], true)
        || !is_string($track['label'] ?? null) || trim($track['label']) === ''
        || !is_string($track['srclang'] ?? null) || !preg_match('/^[A-Za-z]{2,8}(?:-[A-Za-z0-9]{1,8})*$/D', $track['srclang'])
        || !is_bool($track['default'] ?? false)) {
        _doing_it_wrong('component/video-player', 'Invalid caption track: src, label, srclang, kind or default.', '1.0.0');
        return;
    }
    $default_tracks += (int) ($track['default'] ?? false);
}
if ($default_tracks > 1) {
    _doing_it_wrong('component/video-player', 'Only one caption track can be the default.', '1.0.0');
    return;
}

// 3. Generate unique ID for the video player
$id = wp_unique_id('video-player-');
?>
<section class="video-player" data-video-player aria-labelledby="<?php echo esc_attr($id); ?>-title" style="--video-ratio: <?php echo esc_attr(str_replace('/', ' / ', $ratio)); ?>">
    <h2 class="video-player__title" id="<?php echo esc_attr($id); ?>-title"><?php echo esc_html($title); ?></h2>
    <div class="video-player__frame" id="<?php echo esc_attr($id); ?>">
        <?php if ($source === 'youtube') : ?>
            <div class="video-player__placeholder" data-video-placeholder>
                <?php if ($poster !== '') : ?><img src="<?php echo esc_url($poster); ?>" alt="" loading="lazy"><?php endif; ?>
                <p><?php esc_html_e('Charger cette vidéo transmet des données à YouTube.', 'wp-theme-test'); ?></p>
                <button type="button" class="btn btn--primary" data-video-load data-video-embed="<?php echo esc_attr($embed); ?>" aria-controls="<?php echo esc_attr($id); ?>" hidden><?php esc_html_e('Charger la vidéo YouTube', 'wp-theme-test'); ?></button>
            </div>
        <?php else : ?>
            <video controls playsinline preload="<?php echo esc_attr($preload); ?>" aria-labelledby="<?php echo esc_attr($id); ?>-title"<?php if ($poster !== '') : ?> poster="<?php echo esc_url($poster); ?>"<?php endif; ?><?php if ($muted) : ?> muted<?php endif; ?><?php if ($autoplay) : ?> autoplay<?php endif; ?><?php if ($loop) : ?> loop<?php endif; ?>>
                <source src="<?php echo esc_url($src); ?>">
                <?php foreach ($tracks as $track) : ?>
                    <track src="<?php echo esc_url($track['src']); ?>" kind="<?php echo esc_attr($track['kind'] ?? 'subtitles'); ?>" srclang="<?php echo esc_attr($track['srclang']); ?>" label="<?php echo esc_attr($track['label']); ?>"<?php if ($track['default'] ?? false) : ?> default<?php endif; ?>>
                <?php endforeach; ?>
                <?php esc_html_e('Votre navigateur ne prend pas en charge la lecture vidéo.', 'wp-theme-test'); ?>
            </video>
        <?php endif; ?>
    </div>
    <p class="video-player__status" data-video-status role="status" hidden><?php esc_html_e('Impossible de charger la vidéo. Utilisez le lien ci-dessous.', 'wp-theme-test'); ?></p>
    <p class="video-player__link"><a href="<?php echo esc_url($external); ?>"<?php if ($source === 'youtube') : ?> target="_blank" rel="noopener"<?php endif; ?>><?php echo esc_html($source === 'youtube' ? __('Voir sur YouTube (nouvel onglet)', 'wp-theme-test') : __('Ouvrir le fichier vidéo', 'wp-theme-test')); ?></a></p>
</section>
