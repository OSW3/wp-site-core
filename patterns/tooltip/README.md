# Tooltip

## Dans un paragraphe

```html
<p>Nous veillons à l’<!-- wp:pattern {"slug":"component/tooltip","args":{"label":"accessibilité","element":"span","inline":true,"text":"Un site utilisable par tous, y compris au clavier."}} /--> de votre site.</p>
```

```php
echo '<p>Nous veillons à l’';
get_template_part('patterns/tooltip/tooltip', null, [
    'label' => 'accessibilité', 'element' => 'span', 'inline' => true,
    'text' => 'Un site utilisable par tous.',
]);
echo ' de votre site.</p>';
```

- `element` : button, a ou span ; par défaut a si url est renseigné, sinon button.
- `inline` : true pour hériter de la typographie du paragraphe.
- Un span est focusable au clavier sans rôle bouton : il affiche une description, pas une action.
- Un lien conserve sa navigation ; renseigner `url` lorsque `element` vaut a.

```html
<!-- wp:pattern {"slug":"component/tooltip","args":{"label":"Contact","text":"Discutons de votre projet.","url":"/contact"}} /-->
```

```php
get_template_part('patterns/tooltip/tooltip', null, [
    'label' => 'Information',
    'text' => 'Une précision utile.',
]);
```

- `label` : texte visible ; `text` : description en texte simple, sans éléments interactifs.
- `url` : facultatif ; rend un lien au lieu d'un bouton.
- Affichage au survol et au focus ; Échap ferme l'infobulle.
- Le pointeur peut passer sur l'infobulle sans la fermer.
- Association via `aria-describedby`, identifiant unique et rôle `tooltip`.
- Position ajustée à l'écran ; nécessite l'API Popover du navigateur.
- Script partagé : `resources/scripts/components/floating.js`.
