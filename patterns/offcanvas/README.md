# Offcanvas

## Déclencheur et panneau séparés

```html
<!-- wp:pattern {"slug":"component/offcanvas-trigger","args":{"offcanvas_id":"site-menu","label":"Ouvrir le menu","element":"button","type":"primary"}} /-->
<!-- wp:pattern {"slug":"component/offcanvas","args":{"offcanvas_id":"site-menu","title":"Navigation","position":"left","content":"<nav aria-label=\"Menu latéral\"><ul><li><a href=\"/\">Accueil</a></li><li><a href=\"/contact\">Contact</a></li></ul></nav>","close_label":"Fermer le menu"}} /-->
```

```php
get_template_part('patterns/offcanvas/offcanvas-trigger', null, [
    'offcanvas_id' => 'site-menu',
    'label' => 'Ouvrir le menu',
    'element' => 'a',
    'type' => 'outline',
]);
get_template_part('patterns/offcanvas/offcanvas', null, [
    'offcanvas_id' => 'site-menu',
    'title' => 'Navigation',
    'position' => 'right',
    'content' => '<p>Contenu du panneau.</p>',
    'close_label' => 'Fermer le menu',
]);
```

## Déclencheur libre

```html
<a href="#site-menu" role="button" data-offcanvas-open="site-menu" aria-haspopup="dialog" aria-controls="site-menu" aria-expanded="false">Menu</a>
<span role="button" tabindex="0" data-offcanvas-open="site-menu" aria-haspopup="dialog" aria-controls="site-menu" aria-expanded="false">Menu</span>
```

- `offcanvas_id` : identifiant unique partagé avec les déclencheurs ; généré sur le panneau si absent.
- `position` : left (défaut) ou right.
- `title` : nom accessible du panneau ; `content` : HTML filtré par WordPress.
- `close_label` : libellé accessible du bouton de fermeture.
- Déclencheur : `element` button (défaut), a ou span.
- `type` : primary, secondary, info, success, danger, warning, ghost, outline.
- Panneau de 24rem maximum, limité à la largeur de l'écran.
- Fond assombri et flouté à 6px ; assombrissement conservé si le navigateur ne prend pas en charge le flou.
- Page bloquée à sa position de défilement, sans saut horizontal ; position et styles restaurés à la fermeture.
- Le contenu du panneau reste défilable. Plusieurs panneaux ouverts gardent le verrouillage jusqu'à la fermeture du dernier.
- Dialogue modal natif : focus contenu dans le panneau, arrière-plan inerte, fermeture avec Échap et retour au déclencheur.
- Fermeture par bouton ou clic sur le backdrop, mais pas par clic sur l'espace vide intérieur.
- Animation d'entrée désactivée avec `prefers-reduced-motion`.
- Nécessite JavaScript et un navigateur prenant en charge `<dialog>.showModal()`.

## Depuis un script

```js
const panel = document.getElementById('site-menu');
panel.dispatchEvent(new CustomEvent('offcanvas:open'));
panel.dispatchEvent(new CustomEvent('offcanvas:close'));
```

- API module : `openOffcanvas(idOuElement)` / `closeOffcanvas(idOuElement)` depuis `resources/scripts/components/offcanvas.js`.
- Événement `offcanvas:closed` après fermeture.
- Les ouvertures/fermetures natives via `showModal()` / `close()` sont aussi observées pour synchroniser le verrouillage et les déclencheurs.
