# Comment

## Intégration

```html
<!-- wp:pattern {"slug":"component/comment","args":{"title":"Commentaires"}} /-->
```

```php
get_template_part('patterns/comment/comment', null, [
    'title' => 'Commentaires',
]);
```

- À placer une seule fois, après le contenu d'un article ou d'une page individuelle.
- `title` : titre de la section, « Commentaires » par défaut.
- Utilise la publication courante ; aucune liste de commentaires fictifs ni ID de publication codé en dur.
- Exemple ajouté à la page d'accueil : visible si celle-ci est une page statique. Une page d'accueil listant les articles ne rend pas ce composant.
- Activer les commentaires sur la publication dans WordPress pour afficher le formulaire.

## Prise en charge WordPress

- Liste native HTML5 : auteur, avatar, date, permalien, contenu, modification et réponse selon les permissions.
- Réponses imbriquées et profondeur configurées dans Réglages > Discussion.
- Pagination native des commentaires, indépendante de la pagination des articles.
- Formulaire natif : visiteurs ou utilisateurs connectés, champs obligatoires, consentement cookies et obligation de connexion selon les réglages.
- Envoi via WordPress, sans endpoint AJAX personnalisé.
- Modération et visibilité des commentaires non approuvés gérées par WordPress.
- Aucun commentaire : message invitant à participer lorsque les commentaires sont ouverts.
- Commentaires fermés : liste conservée et message explicite, sans formulaire.
- Publication protégée par mot de passe : aucun contenu ni formulaire avant déverrouillage.
- Hors page individuelle : aucun rendu.
- Script natif `comment-reply` chargé uniquement sur les publications individuelles ouvertes aux commentaires lorsque les réponses imbriquées sont activées.
- Contenu et filtres conservés par les fonctions WordPress ; aucun contournement de la modération ou des droits.
