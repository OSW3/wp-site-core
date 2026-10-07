# Tabs

```html
<!-- wp:pattern {"slug":"component/tabs","args":{"label":"Notre accompagnement","active":0,"orientation":"horizontal","activation":"automatic","items":[{"label":"Conception","content":"<p>Une conception sur mesure.</p>"},{"label":"Développement","content":"<p>Un site rapide et accessible.</p>"},{"label":"Bientôt","content":"<p>À venir.</p>","disabled":true}]}} /-->
```

```php
get_template_part('patterns/tabs/tabs', null, [
    'label' => 'Notre accompagnement',
    'active' => 0,
    'orientation' => 'horizontal',
    'activation' => 'automatic',
    'items' => [
        ['label' => 'Conception', 'content' => '<p>Une conception sur mesure.</p>'],
        ['label' => 'Développement', 'content' => '<p>Un site rapide et accessible.</p>'],
        ['label' => 'Bientôt', 'content' => '<p>À venir.</p>', 'disabled' => true],
    ],
]);
```

- `items` : label, content (HTML filtré), disabled (false par défaut).
- `label` : nom accessible de la liste.
- `active` : index de départ, à partir de 0 ; 0 par défaut.
- `orientation` : horizontal (défaut), vertical.
- `activation` : automatic (défaut), manual.
- Flèches gauche/droite en horizontal, haut/bas en vertical ; Home/End ; navigation circulaire et onglets désactivés ignorés.
- En mode manual, les flèches déplacent le focus ; Entrée/Espace active l'onglet.
- Un seul onglet dans le parcours Tab ; panneau actif focusable.
- Rôles tablist/tab/tabpanel et associations ARIA activés par le script.
- Sans JavaScript, tous les contenus sont visibles et les liens pointent vers leur panneau.
- Un fragment d'URL correspondant à un panneau sélectionne cet onglet à l'initialisation.
- Événement `tabs:changed` avec `detail.index` et `detail.panel`.
- Identifiants générés par instance, plusieurs composants indépendants possibles.
