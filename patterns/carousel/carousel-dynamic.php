<?php

/**
 * Title: Carousel Dynamic
 * Description: Dynamic carousel populated from WP Feat Carousel.
 * Categories: components, status
 * Keywords: carousel, slides, slider, gallery, dynamic
 * Slug: wp-site-core/component/carousel-dynamic
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

declare(strict_types=1);

use OSW3\WpFeatCarousel\Services\SlideEligibilityService;
use OSW3\WpFeatCarousel\Services\VisitorLocationResolver;
use OSW3\WpFeatCarousel\Models\Slide;

if (
    !class_exists(Slide::class)
    || !class_exists(SlideEligibilityService::class)
    || !class_exists(VisitorLocationResolver::class)
) {
    error_log('WP Site Core: carousel-dynamic requires WP Feat Carousel.');
    return;
}

$args = isset($args) && is_array($args)
    ? $args
    : [];

$locationSlug = isset($args['location']) && is_scalar($args['location'])
    ? sanitize_title((string) $args['location'])
    : '';

$carouselSlugValue = $args['carousel-slug']
    ?? $args['carousel_slug']
    ?? '';

$carouselSlug = is_scalar($carouselSlugValue)
    ? sanitize_title((string) $carouselSlugValue)
    : '';

$carouselIdValue = $args['carousel-id']
    ?? $args['carousel_id']
    ?? 0;

$carouselId = is_scalar($carouselIdValue)
    ? absint($carouselIdValue)
    : 0;

$carouselPost = null;

/*
 * 1. Résolution du carrousel par emplacement.
 */
if ($locationSlug !== '') {
    $carousels = get_posts([
        'post_type'      => 'carousel',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'tax_query'      => [
            [
                'taxonomy' => 'carousel_location',
                'field'    => 'slug',
                'terms'    => $locationSlug,
            ],
        ],
    ]);

    if (
        isset($carousels[0])
        && $carousels[0] instanceof WP_Post
    ) {
        $carouselPost = $carousels[0];
    }
}

/*
 * 2. Résolution du carrousel par slug.
 */
elseif ($carouselSlug !== '') {
    $resolvedCarousel = get_page_by_path(
        $carouselSlug,
        OBJECT,
        'carousel'
    );

    if ($resolvedCarousel instanceof WP_Post) {
        $carouselPost = $resolvedCarousel;
    }
}

/*
 * 3. Résolution du carrousel par ID.
 */
elseif ($carouselId > 0) {
    $resolvedCarousel = get_post($carouselId);

    if ($resolvedCarousel instanceof WP_Post) {
        $carouselPost = $resolvedCarousel;
    }
}

/*
 * Aucun carrousel valide ou publié.
 */
if (
    !$carouselPost instanceof WP_Post
    || $carouselPost->post_type !== 'carousel'
    || $carouselPost->post_status !== 'publish'
) {
    return;
}

/*
 * Réglages du carrousel.
 */
$dbSettings = get_post_meta(
    $carouselPost->ID,
    '_carousel_settings',
    true
);

if (!is_array($dbSettings)) {
    $dbSettings = [];
}
$dbSettings = array_merge([
    'mode' => 'slide',
    'delay' => 5000,
    'arrows' => 1,
    'dots' => 1,
    'loop' => 1,
    'autoplay' => 1,
    'show_pause' => 1,
], $dbSettings);

/*
 * Slides ordonnées enregistrées dans le carrousel.
 */
$slideOrders = get_post_meta(
    $carouselPost->ID,
    '_carousel_slides_order',
    true
);

if (!is_array($slideOrders) || $slideOrders === []) {
    return;
}

/*
 * La localisation du visiteur est résolue une seule fois pour
 * l'ensemble des slides de ce carrousel.
 */
$visitorLocation = VisitorLocationResolver::resolve();

$formattedSlides = [];

foreach ($slideOrders as $item) {
    if (!is_array($item)) {
        continue;
    }

    $slideId = absint($item['slide_id'] ?? 0);

    if ($slideId <= 0) {
        continue;
    }

    /*
     * Vérifie :
     *
     * - que le contenu est bien une slide publiée ;
     * - que sa programmation temporelle est active ;
     * - que son ciblage géographique autorise sa diffusion.
     */
    if (
        !SlideEligibilityService::isEligible(
            $slideId,
            $visitorLocation
        )
    ) {
        continue;
    }

    $slidePost = get_post($slideId);

    if (!$slidePost instanceof WP_Post) {
        continue;
    }

    /*
     * Image de fond.
     */
    $slide = new Slide($slidePost);
    $backgroundImage = $slide->getImageUrl() ?? '';

    /*
     * Contenu éditorial.
     */
    $title = get_post_meta(
        $slideId,
        '_slide_display_title',
        true
    );

    $subtitle = get_post_meta(
        $slideId,
        '_slide_subtitle',
        true
    );

    $text = get_post_meta(
        $slideId,
        '_slide_description',
        true
    );

    $title = is_scalar($title)
        ? (string) $title
        : '';

    $subtitle = is_scalar($subtitle)
        ? (string) $subtitle
        : '';

    $text = is_scalar($text)
        ? (string) $text
        : '';

    /*
     * Préparation de la slide pour le composant visuel.
     */
    $formattedSlides[] = [
        'bg_image' => $backgroundImage,
        'title'    => $title,
        'subtitle' => $subtitle,
        'text'     => $text,
        'align'    => $slide->getContentAlignment(),
        'buttons'  => $slide->getCtas(),
    ];
}

/*
 * Aucun rendu si aucune slide n'est actuellement éligible.
 */
if ($formattedSlides === []) {
    return;
}

/*
 * Arguments réservés à la résolution dynamique.
 *
 * Ils doivent être retirés avant de transmettre les arguments
 * au composant HTML de base.
 */
$renderArgs = $args;

unset(
    $renderArgs['location'],
    $renderArgs['carousel-slug'],
    $renderArgs['carousel_slug'],
    $renderArgs['carousel-id'],
    $renderArgs['carousel_id']
);

/*
 * Réglages calculés depuis le carrousel.
 *
 * Les arguments fournis au pattern dynamique restent prioritaires,
 * sauf pour les slides qui sont toujours calculées ici.
 */
$computedArgs = array_merge(
    [
        'type' => 'standard',
        'transition' => ($dbSettings['mode'] === 'fade' ? 'fade' : 'slide'),

        'delay' => isset($dbSettings['delay'])
            ? absint($dbSettings['delay'])
            : 5000,

        'show_controls' => !empty(
            $dbSettings['arrows']
        ),

        'show_dots' => !empty(
            $dbSettings['dots']
        ),
        'show_pause' => !empty($dbSettings['show_pause']),

        'loop' => !empty(
            $dbSettings['loop']
        ),

        'autoplay' => !empty(
            $dbSettings['autoplay']
        ),

        'label' => $carouselPost->post_title,
    ],
    $renderArgs
);

/*
 * Les slides ne peuvent pas être remplacées depuis les arguments
 * transmis au pattern dynamique.
 */
$computedArgs['slides'] = $formattedSlides;

/*
 * Délégation du rendu au composant générique.
 */
wp_site_core_render_pattern(
    'component/carousel',
    $computedArgs
);