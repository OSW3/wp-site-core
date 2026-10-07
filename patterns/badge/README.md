# Badge

## SCSS

```scss
@use 'resources/scss/components/badge' as badge;
@use 'components/badge' as badge;
```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- wp:pattern {"slug":"component/badge","args":{"label":"Message","type":"danger"}} /-->
<!-- wp:pattern {"slug":"component/badge-primary","args":{"label":"Message"}} /-->
<!-- wp:pattern {"slug":"component/badge-info","args":{"label":"Message"}} /-->
<!-- wp:pattern {"slug":"component/badge-success","args":{"label":"Message"}} /-->
<!-- wp:pattern {"slug":"component/badge-warning","args":{"label":"Message"}} /-->
<!-- wp:pattern {"slug":"component/badge-danger","args":{"label":"Message"}} /-->
```

### PHP

```php
// Badge simple d'information
echo render_block([
    'blockName' => 'core/pattern',
    'attrs'     => [
        'slug' => 'component/badge',
        'args' => [
            'label' => 'En cours',
            'type'  => 'info',
            'size'  => 'sm'
        ]
    ]
]);

// Badge avec statut de confirmation
echo render_block([
    'blockName' => 'core/pattern',
    'attrs'     => [
        'slug' => 'component/badge',
        'args' => [
            'label' => 'Payé',
            'type'  => 'success'
        ]
    ]
]);
```
