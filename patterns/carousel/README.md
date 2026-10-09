# Carousel

Slide transitions keep an infinite loop without cloned slides by reordering originals. Fade transitions overlap the original slides without reordering them. Titles, subtitles, text, and buttons retain their staggered entrance animations.

## WordPress template example

```html
<!-- wp:pattern {"slug":"wp-site-core/component/carousel","args":{"type":"hero","slides":[
    {"bg_image":"https://picsum.photos/1200/600?1","title":"Architecture Modulaire","subtitle":"SaaS & WordPress","align":"center","button":{"label":"Découvrir","type":"primary"}},
    {"bg_image":"https://picsum.photos/1200/600?2","title":"Performance SCSS","subtitle":"BEM Architecture","align":"left"},
    {"bg_image":"https://picsum.photos/1200/600?3","title":"Performance SCSS","subtitle":"BEM Architecture","align":"right","button":{"label":"Découvrir","type":"primary"}},
    {"bg_image":"https://picsum.photos/1200/600?4","title":"À Propos","subtitle":"Notre Histoire","align":"center","button":{"label":"En Savoir Plus","url":"/test","type":"primary"}}
]}} /-->

<!-- wp:pattern {"slug":"wp-site-core/component/carousel-dynamic","args":{"location":"main-carousel"}} /-->
<!-- wp:pattern {"slug":"wp-site-core/component/carousel-dynamic","args":{"carousel-slug":"carousel-a"}} /-->
<!-- wp:pattern {"slug":"wp-site-core/component/carousel-dynamic","args":{"carousel-id":124}} /-->
<!-- wp:pattern {"slug":"wp-site-core/component/carousel-dynamic","args":{"location":"main-carousel","type":"hero","delay":3000}} /-->
```

## Options

- `slides`: an array of slide objects or HTML strings. Object fields: `bg_image`, `subtitle`, `title`, `text`, `align`, `buttons`; alternatively `pattern` (a path below `patterns/`) with `args`. `buttons` is a list of button component arguments (`label`, `url`, `type`, `size`, `target`). The legacy single `button` is used only when `buttons` is absent; an empty `buttons` list explicitly hides all buttons.
- `type`: `standard`, `hero`, or `cinematic`.
- `transition`: `slide` (default) or `fade`, independent of `type`. Fade forces `per_view=1`, preserves navigation/loop/autoplay, and skips animations for reduced-motion users.
- `per_view`: 1, 2, or 3; the layout adapts at the existing responsive breakpoints.

Direct slide images, pictures, and videos fill their slide width without adding gaps between slides. Nested component media keep their own styles.

- `loop`: `true` by default. Set `false` to stop at the first and last available position.
- `autoplay`: `true` by default. Playback pauses on hover, keyboard focus, hidden tabs, and reduced-motion preferences. After mouse navigation with arrows or dots, it resumes when the pointer leaves; manual pause is preserved.
- `show_pause`: `true` by default. Shows the pause/play control when autoplay is enabled and multiple slides exist. Hiding it does not disable autoplay; leaving it visible is recommended for accessibility.
- `delay`: autoplay interval in milliseconds, clamped between 1000 and 60000.
- `show_controls`, `show_dots`: toggle the previous/next controls and pagination. Controls are omitted when fewer than two slides exist.
- `label`: accessible name for the carousel region.

Slide HTML and text are filtered with `wp_kses_post()`. Dot navigation, keyboard controls (when the carousel region is focused), horizontal swipe/drag, responsive visible-slide state, and reduced-motion support are included.

## WP Feat Carousel integration

The dynamic component requires WP Feat Carousel; a missing dependency is logged
and renders no carousel. It resolves a published carousel by location, slug or ID,
preserves saved slide order, and uses the plugin's schedule/geographic eligibility.
It reads the Slide model's image, alignment and resolved CTA list. Editorial text
comes from the display-title, subtitle and description metas, not the WP editor.

Saved mode maps to `transition`, not the visual `type`. Missing settings default
to slide mode, 5000 ms, and enabled arrows/dots/loop/autoplay; explicit disabled
settings remain disabled. The saved `show_pause` setting defaults to enabled
for existing carousels. Pattern arguments may override settings, but not the
computed slides. Page links are resolved by the plugin at render time.

After source changes, rebuild the consuming theme's CSS/JS bundles.
