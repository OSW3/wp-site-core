# Card

## SCSS

```scss
@use 'resources/scss/components/card' as card;

```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- Carte standard avec image et badge -->
<!-- wp:pattern {"slug":"component/card","args":{"title":"Titre de la carte","url":"/article-slug","text":"Description courte du composant...","meta":"12 Octobre 2026","image_src":"https://picsum.photos/600/400","badge":{"label":"Tech","type":"primary"}}} /-->

<!-- Carte dynamique liée à un article WordPress -->
<!-- wp:pattern {"slug":"component/card-post"} /-->

```

### PHP

```php
// Carte personnalisée au format horizontal
get_template_part('patterns/card/card', null, [
    'title'      => 'Développement de composant',
    'url'        => '/docs/card',
    'meta'       => 'Mise à jour le 2 Octobre 2026',
    'text'       => 'Intégration du composant Card avec support BEM et Gutenberg.',
    'image_src'  => 'https://picsum.photos/600/400',
    'horizontal' => true,
    'badge'      => [
        'label' => 'Composant',
        'type'  => 'info',
    ],
]);

```