# Pagination

## Requête WordPress courante

```html
<!-- wp:pattern {"slug":"component/pagination","args":{"align":"center","size":"medium"}} /-->
```

```php
get_template_part('patterns/pagination/pagination', null, [
    'align' => 'center',
    'size' => 'medium',
]);
```

## Requête personnalisée

```html
<!-- wp:pattern {"slug":"component/pagination","args":{"current":4,"total":12,"base":"/articles/page/%#%/","mid_size":1,"end_size":1}} /-->
```

```php
get_template_part('patterns/pagination/pagination', null, [
    'current' => max(1, (int) get_query_var('paged')),
    'total' => (int) $query->max_num_pages,
    'base' => str_replace('999999999', '%#%', get_pagenum_link(999999999)),
]);
```

- `current` / `total` : page courante et nombre total de pages ; par défaut issus de WordPress.
- Pour une requête sans résultat (`max_num_pages = 0`) ou une seule page, aucun contrôle n'est rendu.
- `base` : URL avec `%#%` remplacé par le numéro ; par défaut WordPress fournit les URLs et conserve les paramètres de requête.
- `mid_size` : pages de part et d'autre de la page courante, 2 par défaut.
- `end_size` : pages à chaque extrémité, 1 par défaut.
- `prev_next` : true par défaut ; affiche Précédent / Suivant.
- `align` : left, center (défaut), right ; `size` : small, medium (défaut), large.
- `label` : nom accessible de la navigation.
- Liens natifs, ellipses et `aria-current="page"` fournis par `paginate_links()` ; aucun JavaScript.
- Le composant ne crée pas la boucle d'articles et ne pagine pas lui-même les données.
