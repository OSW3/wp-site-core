# Progression

```html
<!-- wp:pattern {"slug":"component/progress","args":{"id":"project-progress","label":"Avancement du projet","value":65,"max":100,"type":"success","striped":true,"animated":true,"show_value":true}} /-->
```

```php
get_template_part('patterns/progress/progress', null, [
    'id' => 'project-progress',
    'label' => 'Avancement du projet',
    'value' => 65,
    'max' => 100,
    'type' => 'success',
    'striped' => true,
    'animated' => true,
]);
```

```html
<!-- wp:pattern {"slug":"component/progress","args":{"label":"Chargement","value":null,"type":"info"}} /-->
```

- Slug : `component/progress`, dossier `patterns/progress/`.
- `label` : texte accessible ; `id` : identifiant unique facultatif, généré sinon.
- `value` : nombre entre 0 et `max`, 0 par défaut ; null pour une progression indéterminée.
- `max` : nombre positif, 100 par défaut.
- `type` : primary (défaut), secondary, info, success, danger, warning.
- `striped` / `animated` : false par défaut ; animation des bandes lorsque les deux sont true.
- `show_value` : true par défaut ; pourcentage visible ou « En cours… ».
- Rôle progressbar, valeur ARIA réelle et pourcentage visuel ; animations désactivées en mode réduit.
- Affichage statique utilisable sans JavaScript. Les mises à jour ne sont pas annoncées à chaque pourcentage.

## Mise à jour depuis un script

```js
const progress = document.getElementById('project-progress');
progress.dispatchEvent(new CustomEvent('progress:update', { detail: { value: 80 } }));
// Passage en mode indéterminé :
progress.dispatchEvent(new CustomEvent('progress:update', { detail: { value: null } }));
```

- API module : `setProgress(element, value)` depuis `resources/scripts/components/progress.js`.
- Événement `progress:changed` avec `detail.value` et `detail.max`.
- Les mises à jour invalides sont signalées dans la console et ne modifient pas la progression.
