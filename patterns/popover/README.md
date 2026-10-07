# Popover

## Dans un paragraphe

```html
<p>Découvrez notre <!-- wp:pattern {"slug":"component/popover","args":{"label":"accompagnement","element":"span","inline":true,"title":"Un suivi sur mesure","content":"De la <strong>conception</strong> à la maintenance."}} /--> pour votre projet.</p>
```

```php
echo '<p>Découvrez notre ';
get_template_part('patterns/popover/popover', null, [
    'label' => 'accompagnement', 'element' => 'span', 'inline' => true,
    'content' => 'De la <strong>conception</strong> à la maintenance.',
]);
echo ' pour votre projet.</p>';
```

- `element` : button (défaut), a, span. Le lien et le span sont des déclencheurs activables avec Entrée ou Espace.
- `inline` : true pour un déclencheur intégré au texte, sans apparence de bouton.
- `url` : URL facultative pour un déclencheur lien ; son clic ouvre le popover au lieu de naviguer.
- En mode inline, le panneau utilise uniquement des éléments compatibles avec un paragraphe. Le contenu accepte a, strong, em, b, i, br, code, span, small, sub et sup ; les balises de bloc sont retirées. Utiliser du texte avec br plutôt que des paragraphes.

```html
<!-- wp:pattern {"slug":"component/popover","args":{"label":"Informations","title":"Notre accompagnement","content":"<p>Un accompagnement sur mesure.</p>"}} /-->
```

```php
get_template_part('patterns/popover/popover', null, [
    'label' => 'Informations',
    'title' => 'Notre accompagnement',
    'content' => '<p>Un accompagnement sur mesure.</p>',
]);
```

- `label` : texte du déclencheur ; `title` : titre facultatif.
- `content` : HTML filtré par WordPress, avec liens possibles.
- Ouverture au clic ; fermeture avec Échap, bouton Fermer, clic extérieur ou sortie du focus.
- Pas de piège de focus : ce panneau n'est pas une modale.
- Position ajustée à l'écran ; nécessite l'API Popover du navigateur.
- Script partagé : `resources/scripts/components/floating.js`.
