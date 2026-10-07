# Map

## Fournisseurs

- `openstreetmap` : tuiles raster OSM, sans clé. Serveurs communautaires à capacité limitée, sans garantie de disponibilité ; privilégier OpenFreeMap pour un trafic important.
- `openfreemap` : carte vectorielle via MapLibre, sans clé. Styles liberty (défaut), bright, positron.
- `google` : Google Maps Embed API, clé navigateur requise et API à activer dans Google Cloud.
- `mapy` : Mapy.com REST API, clé navigateur requise ; gratuit dans la limite des crédits du forfait choisi, pas illimité.

## Exemples HTML

```html
<!-- wp:pattern {"slug":"component/map","args":{"provider":"openstreetmap","title":"Notre localisation","lat":50.6292,"lng":3.0573,"zoom":13,"height":400,"marker_label":"Lille","consent":true}} /-->
<!-- wp:pattern {"slug":"component/map","args":{"provider":"openfreemap","title":"Notre localisation","lat":50.6292,"lng":3.0573,"zoom":13,"style":"positron","consent":true}} /-->
<!-- wp:pattern {"slug":"component/map","args":{"provider":"google","title":"Notre localisation","lat":50.6292,"lng":3.0573,"zoom":13,"consent":true}} /-->
<!-- wp:pattern {"slug":"component/map","args":{"provider":"mapy","title":"Notre localisation","lat":50.6292,"lng":3.0573,"zoom":13,"consent":true}} /-->
```

## Exemple PHP

```php
get_template_part('patterns/map/map', null, [
    'provider' => 'openfreemap',
    'title' => 'Notre localisation',
    'lat' => 50.6292,
    'lng' => 3.0573,
    'zoom' => 13,
    'height' => 400,
    'marker' => true,
    'marker_label' => 'Lille',
    'style' => 'liberty',
    'consent' => true,
]);
```

## Clés Google et Mapy

Définir `WP_THEME_GOOGLE_MAPS_KEY` et/ou `WP_THEME_MAPY_KEY` dans la configuration WordPress locale, avec les valeurs fournies par votre environnement. Ne pas enregistrer de vraies clés dans le thème ou ses exemples.

- `api_key` permet aussi de transmettre une clé depuis PHP, à privilégier plutôt que de l'inscrire dans un template HTML.
- Ce sont des clés **publiques côté navigateur**, visibles dans les requêtes ; jamais des identifiants serveur secrets.
- Restreindre les clés aux domaines autorisés et aux APIs nécessaires dans les consoles des fournisseurs.
- Sans clé, Google/Mapy affichent un message de configuration manquante et un lien externe, pas une carte prétendument fonctionnelle.
- Les exemples de la page d'accueil utilisent les constantes, sans clé codée en dur.

## Paramètres

- `provider` : openstreetmap (défaut), openfreemap, google, mapy.
- `lat` / `lng` : latitude/longitude, Lille par défaut. Latitude limitée à ±85.051129 pour la projection Web Mercator ; longitude à ±180.
- `zoom` : 0 à 19, 13 par défaut ; Google utilise un zoom entier.
- `height` : pixels entiers, 200 à 2000, 400 par défaut.
- `title` : titre visible et nom accessible de la carte.
- `marker` : true par défaut ; marqueur à la position centrale.
- `marker_label` : nom du marqueur, titre par défaut ; texte simple, pas de HTML.
- `style` : liberty, bright, positron ; utilisé uniquement par OpenFreeMap.
- `consent` : true par défaut ; demande une activation avant toute requête fournisseur. false charge la carte lorsqu'elle approche de la zone visible.
- `api_key` : uniquement pour Google et Mapy.

## Comportement

- MapLibre, ses styles et son worker sont livrés localement dans les assets compilés ; aucun CDN de bibliothèque.
- Déployer tout le répertoire `assets/`, y compris `assets/assets/` contenant le worker généré. Les scripts et le worker doivent être servis depuis la même origine.
- MapLibre est inclus dans le bundle principal du thème ; l'activation différée évite les requêtes aux fournisseurs, mais ne réduit pas le téléchargement du JavaScript local.
- Zoom à la molette désactivé sur MapLibre pour ne pas interrompre le scroll de la page. Boutons et commandes clavier disponibles.
- Attribution OSM/OpenFreeMap conservée ; copyright et logo Mapy visibles et cliquables.
- Plusieurs cartes indépendantes, redimensionnement automatique, nom accessible et lien externe toujours présent.
- Sans JavaScript : titre, explication et lien externe restent utilisables.
- Erreurs de chargement signalées et bouton permettant une nouvelle tentative si la carte initiale n'a pas chargé.
- Google est une iframe externe : son événement load confirme le chargement du document, **pas** la validité de la clé ni l'absence d'un message d'erreur Google ; ces erreurs doivent être vérifiées dans la console du fournisseur.
- Les autres cartes nécessitent WebGL ; en cas d'indisponibilité, un message explicite et le lien externe sont conservés.
- Le bouton constitue une activation ponctuelle, pas un gestionnaire global de consentement ni une garantie de conformité juridique.
- Ne pas empêcher le Referer pour les tuiles OSM ; respecter le cache HTTP, sans préchargement massif ni téléchargement hors ligne.
- CSP : autoriser le worker local (`worker-src 'self'`), les hôtes des tuiles/styles/fonts/sprites dans connect-src/img-src, et Google dans frame-src. MapLibre peut utiliser des images blob/data ; adapter la politique au déploiement.
- Événement `map:loaded` avec `detail.provider`.

## Sources fournisseurs

- [Google Maps Embed](https://developers.google.com/maps/documentation/embed/embedding-map)
- [Politique des tuiles OpenStreetMap](https://operations.osmfoundation.org/policies/tiles/)
- [OpenFreeMap / MapLibre](https://openfreemap.org/quick_start/)
- [Mapy : tuiles](https://developer.mapy.com/rest-api/funkce/mapove-dlazdice/)
- [Mapy : attribution](https://developer.mapy.com/rest-api/atributovani/)
