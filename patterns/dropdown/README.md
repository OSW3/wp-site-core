# Dropdown

```html
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Nos services","type":"primary","direction":"dropdown","items":[{"label":"Services","url":"/services"},{"label":"Contact","url":"/contact"}]}} /-->
```

```php
get_template_part('patterns/dropdown/dropdown', null, [
    'label' => 'Nos services',
    'type' => 'outline',
    'direction' => 'dropup',
    'items' => [['label' => 'Contact', 'url' => '/contact']],
]);
```

- `label` : texte du bouton.
- `type` : primary, secondary, info, success, danger, warning, ghost, outline ; défaut secondary. Réutilise les styles du composant bouton.
- `direction` : dropdown (bas), dropup (haut), dropleft (gauche), dropright (droite) ; défaut dropdown.
- `items` : liens avec `label` et `url`.
- Ouverture au clic ou avec les flèches ; navigation avec flèches, Home, End et Tab.
- Fermeture avec Échap, clic extérieur, sortie du focus ou sélection d'un lien.
- Liste de liens native, sans rôle ARIA de menu d'application.
- Position ajustée à l'écran ; nécessite un navigateur prenant en charge l'API Popover.
- Si le côté demandé manque de place et que le côté opposé convient, le panneau bascule automatiquement. La position est recalculée au scroll et au redimensionnement.
- Les valeurs invalides de `type` et `direction` sont signalées via `_doing_it_wrong()` puis remplacées par leur valeur par défaut.
- Script partagé : `resources/scripts/components/floating.js`, importé dans l'entrée principale.

## Variantes et directions

```html
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Primary","type":"primary","direction":"dropdown","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Secondary","type":"secondary","direction":"dropup","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Info","type":"info","direction":"dropleft","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Success","type":"success","direction":"dropright","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Danger","type":"danger","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Warning","type":"warning","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Ghost","type":"ghost","items":[{"label":"Contact","url":"/contact"}]}} /-->
<!-- wp:pattern {"slug":"component/dropdown","args":{"label":"Outline","type":"outline","items":[{"label":"Contact","url":"/contact"}]}} /-->
```
