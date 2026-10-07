# Avatar

## SCSS

```scss
@use 'resources/scss/components/avatar' as avatar;

```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- Avatar complet avec photo et statut -->
<!-- wp:pattern {"slug":"component/avatar","args":{"src":"https://i.pravatar.cc/150?img=12","name":"John Doe","size":"lg","status":"online"}} /-->

<!-- Avatar avec initiales de secours (pas de src) -->
<!-- wp:pattern {"slug":"component/avatar","args":{"name":"Arnaud Bodel","size":"md","shape":"rounded"}} /-->

<!-- Wrapper d'auteur WP -->
<!-- wp:pattern {"slug":"component/avatar-author"} /-->

```

### PHP

```php
// Avatar complet avec photo et statut
get_template_part('patterns/avatar/avatar', null, [
    'src'    => 'https://i.pravatar.cc/150?img=12',
    'name'   => 'John Doe',
    'size'   => 'lg',
    'status' => 'online',
]);

// Avatar de secours avec initiales
get_template_part('patterns/avatar/avatar', null, [
    'name'  => 'Arnaud Bodel',
    'size'  => 'md',
    'shape' => 'rounded',
]);

```