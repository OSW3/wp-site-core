# Alert

## SCSS

```scss
@use 'resources/scss/components/alert' as alert;
@use 'components/alert' as alert;
```

## JavaScript

```js
import './components/alert.js';
```

## Template

### HTML

```html
<!-- wp:pattern {"slug":"component/alert", "args":{"title":"Information :", "message":"Une mise à jour système est planifiée.", "type":"info"}} /-->
<!-- wp:pattern {"slug":"component/alert", "args":{"title":"Succès :", "message":"L'opération a été réalisée avec succès.", "type":"success"}} /-->
<!-- wp:pattern {"slug":"component/alert", "args":{"title":"Attention :", "message":"Une action est requise.", "type":"warning"}} /-->
<!-- wp:pattern {"slug":"component/alert", "args":{"title":"Erreur :", "message":"Une erreur est survenue.", "type":"danger"}} /-->

<!-- wp:pattern {"slug":"component/alert-info", "args":{"title":"Information :", "message":"Une mise à jour système est planifiée (2)."}} /-->
<!-- wp:pattern {"slug":"component/alert-success", "args":{"title":"Succès :", "message":"L'opération a été réalisée avec succès (2)."}} /-->
<!-- wp:pattern {"slug":"component/alert-warning", "args":{"title":"Attention :", "message":"Une action est requise (2)."}} /-->
<!-- wp:pattern {"slug":"component/alert-danger", "args":{"title":"Erreur :", "message":"Une erreur est survenue (2)."}} /-->
```

### PHP

```php
echo render_block([
    'blockName' => 'core/pattern',
    'attrs'     => [
        'slug' => 'component/alert',
        'args' => [
            'title' => 'Error',
            'type'  => 'danger',
            'message'  => 'Error message'
        ]
    ]
]);
```
