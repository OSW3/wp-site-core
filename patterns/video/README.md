# Video Player

Lecteur natif pour un fichier local ou une URL directe ; iframe YouTube chargée uniquement après activation. Aucun SDK, aucune bibliothèque externe.

## Intégration HTML

```html
<!-- wp:pattern {"slug":"component/video-player","args":{"source":"local","src":"/wp-content/uploads/mon-film.mp4","title":"Notre présentation","poster":"/wp-content/uploads/affiche.jpg"}} /-->
<!-- wp:pattern {"slug":"component/video-player","args":{"source":"url","src":"https://example.com/film.webm","title":"Présentation distante","preload":"none"}} /-->
<!-- wp:pattern {"slug":"component/video-player","args":{"source":"youtube","src":"https://youtu.be/M7lc1UVf-VE","title":"Présentation YouTube"}} /-->
```

## Intégration PHP

```php
get_template_part('patterns/video/video-player', null, [
    'source' => 'local',
    'src' => 42, // ID d'une vidéo de la médiathèque.
    'title' => 'Notre présentation',
    'poster' => '/wp-content/uploads/affiche.jpg',
    'tracks' => [
        [
            'src' => '/wp-content/uploads/sous-titres-fr.vtt',
            'srclang' => 'fr',
            'label' => 'Français',
            'kind' => 'subtitles',
            'default' => true,
        ],
    ],
]);
```

## Paramètres

- `source` : `local` (défaut), `url`, `youtube`.
- `src` : obligatoire. Pour `local`, ID entier d'une vidéo WordPress, chemin commençant par `/`, ou URL HTTP(S) du fichier. Pour `url`, URL HTTP(S) directe du fichier, pas une page web.
- Pour `youtube`, ID de 11 caractères ou URL officielle watch, youtu.be, embed, shorts ou live. Les playlists seules ne sont pas prises en charge.
- `title` : titre visible et nom accessible ; « Lecteur vidéo » par défaut.
- `poster` : image optionnelle. Utiliser une image locale pour éviter un contact externe avant activation YouTube ; aucune miniature YouTube n'est téléchargée automatiquement.
- `ratio` : `16/9` (défaut), `4/3`, `1/1`, `9/16`. Le fichier garde ses proportions, sans recadrage.
- `preload` : `metadata` (défaut), `none`, `auto` ; uniquement pour le lecteur natif.
- `muted`, `autoplay`, `loop` : booléens, `false` par défaut. `autoplay` nécessite `muted: true` ; le navigateur peut néanmoins refuser la lecture automatique. YouTube ne charge jamais avant le clic, même avec autoplay.
- `tracks` : sous-titres WebVTT pour le lecteur natif ; `src`, `srclang`, `label`, `kind` (`subtitles` ou `captions`) et `default` facultatif. Un seul track par défaut. Les sous-titres YouTube se configurent sur YouTube.

## Comportement et limites

- Contrôles natifs : lecture/pause, recherche, volume, plein écran et sous-titres selon le navigateur. Picture-in-picture dépend du navigateur et du fournisseur.
- Plusieurs lecteurs indépendants, mise en page responsive, lien de secours toujours visible.
- Les fichiers locaux/par URL restent lisibles sans JavaScript. Sans JavaScript, YouTube conserve son explication et le lien vers la vidéo.
- YouTube utilise `youtube-nocookie.com`, avec contrôles du fournisseur et referrer conservé. Ce mode ne garantit pas l'absence de collecte après activation ni la conformité juridique.
- Les erreurs média/sous-titres sont signalées. Les erreurs internes à l'iframe YouTube (vidéo privée, embedding interdit, indisponibilité) ne peuvent pas être inspectées depuis le thème.
- Formats acceptés selon le navigateur (MP4/WebM recommandés) ; pas de lecteur HLS/DASH spécifique. Le serveur doit fournir le bon Content-Type et supporter les requêtes Range pour la recherche.
- Servir les vidéos en HTTPS sur un site HTTPS. Les sous-titres doivent être servis depuis la même origine ; aucune configuration CORS distante n'est ajoutée par le thème.
- CSP : autoriser les fichiers dans `media-src`, les affiches dans `img-src` et `https://www.youtube-nocookie.com` dans `frame-src`.
- L'exemple local de la page d'accueil est un chemin à remplacer, pas un fichier vidéo fourni par le thème.
