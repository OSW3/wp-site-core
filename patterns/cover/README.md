# Cover

## Intégration dans un template HTML

### Image et boutons

```html
<!-- wp:pattern {"slug":"component/cover","args":{"title":"Architecture & Design Web","subtitle":"Projets modernes","description":"<p>Des solutions adaptées à votre activité.</p>","heading_level":2,"bg_image":"/wp-content/uploads/projet.jpg","min_height":"80svh","overlay_opacity":60,"align":"center","vertical_align":"center","priority":false,"buttons":[{"label":"Découvrir","url":"/projets","type":"primary"},{"label":"Contact","url":"/contact","type":"outline"}]}} /-->
```

### Vidéo avec image de secours

Remplacer les chemins par les URLs de vos médias avant intégration.

```html
<!-- wp:pattern {"slug":"component/cover","args":{"title":"Nos réalisations","heading_level":2,"bg_video":"/wp-content/uploads/presentation.mp4","bg_image":"/wp-content/uploads/presentation.jpg","poster":"/wp-content/uploads/presentation.jpg","description":"<p>Découvrez notre savoir-faire.</p>","min_height":"70svh","priority":false,"align":"left","vertical_align":"bottom"}} /-->
```

### Fond uni

```html
<!-- wp:pattern {"slug":"component/cover","args":{"title":"Parlons de votre projet","heading_level":2,"background_color":"#212529","text_color":"#ffffff","overlay_opacity":0,"min_height":"400px","align":"left","buttons":[{"label":"Contact","url":"/contact","type":"secondary"}]}} /-->
```

## Intégration PHP avec image WordPress

```php
get_template_part('patterns/cover/cover', null, [
    'title' => 'Notre expertise',
    'heading_level' => 2,
    'description' => '<p>Un accompagnement sur mesure.</p>',
    'image_id' => 123, // ID réel d'une image de la médiathèque.
    'sizes' => '(min-width: 1200px) 1140px, 100vw',
    'priority' => false,
    'min_height' => '60svh',
    'focal_x' => 65,
    'focal_y' => 40,
]);
```

## Paramètres

- `title`, `subtitle`, `description` : textes ; la description accepte du HTML filtré par WordPress.
- `heading_level` : 1 à 6, défaut 1 ; utiliser 2 ou plus pour les sections secondaires.
- `min_height` : valeur positive en px, rem, em, vh, svh, dvh, vw ou %, ou `0` ; défaut `400px`. Pour une hauteur relative à l'écran, préférer `svh` ou `dvh` plutôt que `%`.
- `align` : left, center, right ; défaut center.
- `vertical_align` : top, center, bottom ; défaut center.
- `bg_image` : URL de l'image ; `image_id` : ID de médiathèque prioritaire sur l'URL, avec dimensions et `srcset` WordPress.
- `sizes` : largeur affichée des images de médiathèque, défaut `100vw`.
- `priority` : true pour un cover principal en haut de page (chargement eager/high), false plus bas (lazy/auto) ; défaut true.
- `bg_video` : URL MP4 ; `poster` : aperçu, défaut `bg_image`. L'image de secours reste affichée avant lecture ou en cas d'échec.
- `focal_x`, `focal_y` : point focal entre 0 et 100 %, défaut 50.
- `overlay_opacity` : entre 0 et 100, défaut 50.
- `overlay_color`, `text_color`, `background_color` : couleurs hexadécimales ; défauts #000000, #ffffff, #212529.
- `buttons` : tableaux d'arguments du composant bouton ; utiliser `type`, pas `style`.

La vidéo démarre uniquement lorsque le cover est visible. Elle se met en pause hors écran, en onglet masqué et lorsque le mouvement réduit est demandé. Le bouton pause/lecture respecte la pause manuelle. Sans JavaScript, seule l'image de secours est affichée. Les erreurs de lecture sont signalées dans le cover et dans la console.

Les paramètres invalides sont signalés via `_doing_it_wrong()` et remplacés par leur valeur par défaut ; l'opacité et le point focal sont bornés.
