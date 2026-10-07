# Toast

## Déclencheur et notification séparés

```html
<!-- wp:pattern {"slug":"component/toast-trigger","args":{"toast_id":"message-sent","label":"Afficher la notification","element":"button","type":"success"}} /-->
<!-- wp:pattern {"slug":"component/toast","args":{"toast_id":"message-sent","title":"Message envoyé","content":"<p>Merci pour votre message.</p>","type":"success","position":"bottom-right","duration":5000,"auto_show":false}} /-->
```

```php
get_template_part('patterns/toast/toast-trigger', null, [
    'toast_id' => 'message-sent',
    'label' => 'Afficher la notification',
    'element' => 'a',
    'type' => 'outline',
]);
get_template_part('patterns/toast/toast', null, [
    'toast_id' => 'message-sent',
    'title' => 'Message envoyé',
    'content' => '<p>Merci pour votre message.</p>',
    'type' => 'success',
    'duration' => 5000,
]);
```

## Déclencheur dans un texte ou élément existant

```html
<p>Consultez cette <span role="button" tabindex="0" data-toast-target="message-sent">notification</span>.</p>
<a href="#message-sent" data-toast-target="message-sent">Afficher la notification</a>
```

- `toast_id` : identifiant unique partagé avec le déclencheur ; généré si non renseigné sur la notification seule.
- `title` : facultatif ; `content` : HTML filtré, obligatoire.
- `type` : primary, secondary, info (défaut), success, danger, warning.
- `position` : top-left, top-right, bottom-left, bottom-right (défaut).
- Marge de 1rem aux bords de la fenêtre, augmentée des zones de sécurité mobiles. La barre d'administration WordPress est prise en compte pour conserver cet espace en haut.
- `duration` : 5000 ms par défaut ; 0 pour une notification persistante.
- `auto_show` : false par défaut ; true pour l'afficher à l'initialisation.
- Déclencheur : `element` button (défaut), a, span ; `type` accepte aussi ghost et outline.
- Déclenchement au clic ou au clavier ; aucun vol de focus à l'ouverture.
- Fermeture par bouton ou Échap depuis la notification ; restauration du focus si nécessaire.
- Temporisation suspendue au survol, au focus et lorsque l'onglet du navigateur est masqué ; réouverture réinitialisant la durée.
- Notifications empilées par position ; annonce polie aux lecteurs d'écran.
- Sans JavaScript, les messages sont affichés dans le flux et les liens restent utilisables.
- Pour une information importante ou interactive, utiliser `duration: 0` ; ne pas réserver un contenu essentiel à une notification temporaire.
- Aucun stockage ni envoi réseau.

## Depuis un script

```js
const toast = document.getElementById('message-sent');
toast.dispatchEvent(new CustomEvent('toast:show'));
toast.dispatchEvent(new CustomEvent('toast:hide'));
```

- API module : `showToast(idOuElement)` / `hideToast(idOuElement)` depuis `resources/scripts/components/toast.js`.
- Événements de sortie : `toast:shown` / `toast:hidden`.
