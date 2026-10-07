# Webradio

Lecteur personnalisé pour un flux radio direct (MP3/AAC/Ogg selon le navigateur), avec boutons Lecture/Arrêt, volume et état visible. L'élément audio technique est masqué et n'affiche jamais les contrôles natifs.

## Intégration HTML

```html
<!-- wp:pattern {"slug":"component/webradio","args":{"title":"Techno.FM","src":"https://stream.techno.fm/radio1-320k.mp3","volume":0.8,"interval":30}} /-->
<!-- wp:pattern {"slug":"component/webradio","args":{"title":"Ma radio","src":"https://radio.example.com/live","api_url":"https://radio.example.com/api/now-playing","fields":{"artist":"now_playing.song.artist","title":"now_playing.song.title","version":"now_playing.song.version","artwork":"now_playing.song.art"}}} /-->
```

## Intégration PHP

```php
get_template_part('patterns/webradio/webradio', null, [
    'title' => 'Ma radio',
    'src' => 'https://radio.example.com/live',
    'api_url' => 'https://radio.example.com/api/now-playing',
    'interval' => 30,
    'fields' => [
        'artist' => 'track.artist',
        'title' => 'track.title',
        'version' => 'track.version',
        'artwork' => 'track.cover',
    ],
]);
```

## Paramètres

- `src` : obligatoire, URL HTTP(S) du flux audio direct ou chemin depuis la racine du site. Pas une page web ni une playlist M3U/PLS ; pas de lecteur HLS spécifique.
- `title` : nom de la radio et nom accessible du lecteur ; « Webradio » par défaut.
- `volume` : volume initial entre 0 et 1, 0.8 par défaut. Zéro coupe le son sans arrêter le flux. Le réglage logiciel du volume dépend du navigateur (certains appareils mobiles imposent leur volume matériel).
- `api_url` : API JSON optionnelle. **Lorsqu'elle est renseignée, elle remplace Icecast**, sans mélange des données et sans bascule silencieuse si elle échoue.
- `fields` : chemins séparés par des points, y compris les indices de tableaux (`stations.0.song.title`). Valeurs texte uniquement. Champs par défaut : `artist`, `title`, `version`, `artwork`. Pour personnaliser, fournir votre mapping complet ; seul `title` est obligatoire, les autres peuvent être omis.
- `interval` : actualisation toutes les 30 secondes par défaut, entier de 10 à 300 secondes. Requêtes sans cache, timeout de 10 secondes, sans chevauchement.

## Icecast et API

- Aucune URL Icecast à renseigner : l'origine de `src` sert à découvrir automatiquement `/status-json.xsl`. C'est la source des références déclarées par le serveur du flux, pas une lecture des tags MP3 dans le navigateur.
- Le flux Techno.FM de l'exemple expose ce JSON avec CORS et le mount `/radio1-320k.mp3`. Il peut annoncer une émission plutôt qu'un morceau et ne fournit actuellement pas de version ni de pochette.
- Le navigateur n'expose pas les blocs ICY de l'élément audio. Les radios sans endpoint Icecast JSON accessible nécessitent une API facultative ; le composant signale l'absence de métadonnées, sans prétendre pouvoir lire les références de n'importe quel serveur.
- Icecast `icestats.source` peut être un objet ou une liste. Le composant sélectionne le mount dont le chemin `listenurl` correspond au chemin du flux configuré ; il n'affiche pas arbitrairement le premier mount.
- Le champ Icecast `artist` est utilisé s'il existe. Sinon, un titre au format `Artiste - Titre` est séparé au premier ` - `. Sans séparateur, tout reste dans le titre et l'artiste est indiqué non renseigné.
- Icecast standard ne fournit généralement **ni version ni pochette**. Le composant ne les invente pas et ne recherche pas d'image chez un tiers. Configurer une API pour les enrichir.
- La version est masquée si absente. Une pochette manquante ou en erreur laisse un emplacement explicite. Les URLs de pochettes relatives sont résolues depuis l'endpoint API ; seuls HTTP(S) sont acceptés.
- Une API doit fournir au moins un artiste ou un titre non vide selon le mapping. Un payload/mapping incorrect, un mount absent, une erreur réseau/HTTP ou un timeout sont signalés et réessayés au prochain intervalle tant que la radio joue.
- En cas d'échec des métadonnées, les anciennes références sont effacées pour éviter d'afficher un morceau périmé ; l'audio peut continuer. Un mount présent sans titre/artiste affiche une absence d'information explicite.

## Lecture, confidentialité et déploiement

- Contrôles personnalisés accessibles au clavier : Lecture, Arrêt et curseur de volume avec pourcentage. Aucun autoplay et aucun lecteur natif visible.
- États : Arrêt, Chargement en cours, En lecture, Erreur de lecture. Le chargement initial et la mise en buffer déclenchent un délai de 30 secondes, puis une erreur explicite si la lecture ne reprend pas.
- Aucune connexion audio avant le clic Lecture : `src` est assigné à ce moment seulement. Les métadonnées sont récupérées lorsque l'audio entre effectivement en lecture.
- Arrêt interrompt le téléchargement audio, vide le buffer, annule les métadonnées et efface les anciennes références. Relancer Lecture ouvre une connexion fraîche au direct ; ce n'est pas une pause différée.
- Plusieurs radios indépendantes. Les modifications de texte sont annoncées poliment et uniquement lorsque les références changent.
- Sans JavaScript, les commandes personnalisées sont masquées et un message propose le lien direct vers le flux ; aucun lecteur natif n'est affiché.
- Les requêtes JSON partent du navigateur, sans cookies ni clés d'authentification. Le serveur doit autoriser **CORS** pour l'origine de votre site ; aucun proxy serveur n'est créé par le thème. Ne pas mettre de secret dans une URL publique.
- Sur un site HTTPS, fournir flux, API et pochettes HTTPS. Adapter la CSP : `media-src` pour le flux, `connect-src` pour les métadonnées, `img-src` pour les pochettes.
- Certaines radios ont un délai audio : les références sont celles déclarées actuellement par le serveur, pas une synchronisation exacte avec le buffer entendu.
- La page d'accueil utilise le flux Techno.FM fourni. Les exemples d'API restent à adapter à votre endpoint réel ; aucune API fictive n'est appelée sur la page d'accueil.
