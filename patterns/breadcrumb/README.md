# Breadcrumb

## SCSS

```scss
@use 'resources/scss/components/breadcrumb' as breadcrumb;90
```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- wp:pattern {"slug":"component/breadcrumb","args":{"items":[{"label":"Accueil","url":"/"},{"label":"Blog","url":"/blog"},{"label":"Article actuel"}]}} /-->
```

### PHP

```php
get_template_part('patterns/breadcrumb/breadcrumb', null, [
    'items' => [
        ['label' => 'Accueil', 'url' => home_url('/')],
        ['label' => 'Actualités', 'url' => site_url('/blog')],
        ['label' => get_the_title()],
    ],
]);
```