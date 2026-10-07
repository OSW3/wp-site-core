# Topbar

```html
<!-- wp:pattern {"slug":"component/topbar","args":{"content":"<strong>Un projet ?</strong> Contactez-nous.","type":"info","sticky":false,"links":[{"label":"Contact","url":"/contact"}]}} /-->
```

```php
get_template_part('patterns/topbar/topbar', null, [
    'content' => '<strong>Un projet ?</strong> Contactez-nous.',
    'type' => 'info',
    'sticky' => false,
    'links' => [['label' => 'Contact', 'url' => '/contact']],
]);
```

- À placer avant le header pour une barre globale, ou dans le contenu.
- `content` : HTML filtré par WordPress ; `links` : liste de `label` / `url`.
- `label` : nom accessible de la barre, « Informations utiles » par défaut.
- `type` : primary, secondary (défaut), info, success, danger, warning, ghost, outline.
- `sticky` : false par défaut ; true pour rester en haut de son conteneur lors du défilement. La position sticky dépend du conteneur parent.
- Barre permanente, sans bouton de fermeture ni comportement dismiss.
- Aucun JavaScript requis.
