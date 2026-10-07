# ScrollToTop

## Intégration

Ajouter une seule instance par page, idéalement dans le footer pour une utilisation globale.

```html
<!-- wp:pattern {"slug":"component/scroll-to-top","args":{"label":"Retour en haut","type":"primary","position":"right","threshold":300,"show_label":false,"smooth":true}} /-->
```

```php
get_template_part('patterns/scroll/scroll-to-top', null, [
    'label' => 'Retour en haut',
    'type' => 'primary',
    'position' => 'right',
    'threshold' => 300,
    'show_label' => false,
    'smooth' => true,
]);
```

## Paramètres

- `label` : texte accessible, « Retour en haut » par défaut.
- `type` : primary, secondary, info, success, danger, warning, ghost ou outline.
- `position` : right (défaut) ou left.
- `threshold` : distance de défilement en pixels avant affichage, 300 par défaut ; 0 pour toujours afficher.
- `show_label` : false (défaut) pour l'icône seule ; true pour afficher aussi le texte.
- `smooth` : true (défaut) pour une remontée animée ; false pour une remontée immédiate.

## Comportement

- Lien natif vers le haut de page, utilisable sans JavaScript.
- Avec JavaScript, masqué sous le seuil et retiré de la navigation clavier lorsqu'il est masqué.
- Un lien ayant le focus reste visible jusqu'à la sortie du focus.
- Activation avec Entrée ; le focus revient au premier h1 du contenu principal, sinon au contenu principal ou au body.
- Respect de `prefers-reduced-motion` : remontée immédiate.
- Position fixe avec prise en compte des zones de sécurité mobiles ; masqué à l'impression.
- Écoute passive du défilement et mises à jour regroupées avec `requestAnimationFrame`.
- Le dossier `patterns/scroll/` suit le routage existant des slugs : le premier mot de `scroll-to-top` détermine le dossier.
