# Carousel

The carousel keeps an infinite loop without cloned slides: it reorders the original slides after each transition. Titles, subtitles, text, and buttons retain their staggered entrance animations.

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

- `slides`: an array of slide objects or HTML strings. Object fields: `bg_image`, `subtitle`, `title`, `text`, `align`, `button`; alternatively `pattern` (a path below `patterns/`) with `args`.
- `type`: `standard`, `hero`, or `cinematic`.
- `per_view`: 1, 2, or 3; the layout adapts at the existing responsive breakpoints.

Direct slide images, pictures, and videos fill their slide width without adding gaps between slides. Nested component media keep their own styles.

- `loop`: `true` by default. Set `false` to stop at the first and last available position.
- `autoplay`: `false` by default. When enabled, a pause/play control is shown; playback pauses on hover, keyboard focus, hidden tabs, and reduced-motion preferences.
- `delay`: autoplay interval in milliseconds, clamped between 1000 and 60000.
- `show_controls`, `show_dots`: toggle the previous/next controls and pagination. Controls are omitted when fewer than two slides exist.
- `label`: accessible name for the carousel region.

Slide HTML and text are filtered with `wp_kses_post()`. Dot navigation, keyboard controls (when the carousel region is focused), horizontal swipe/drag, responsive visible-slide state, and reduced-motion support are included.
