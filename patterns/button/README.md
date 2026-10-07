# Button

## SCSS

```scss
@use 'resources/scss/components/button' as button;

```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- Bouton lien classique -->
<!-- wp:pattern {"slug":"component/button","args":{"label":"Découvrir","url":"/a-propos","type":"primary"}} /-->

<!-- Bouton secondaire outline petit format -->
<!-- wp:pattern {"slug":"component/button","args":{"label":"Annuler","type":"outline","size":"sm"}} /-->

```

### PHP

```php
// Bouton d'action formulaire (balise <button>)
get_template_part('patterns/button/button', null, [
    'label'    => 'Envoyer le message',
    'type'     => 'primary',
    'btn_type' => 'submit',
    'full'     => true,
]);

// Bouton lien avec redirection externe
get_template_part('patterns/button/button', null, [
    'label'  => 'Consulter la documentation',
    'url'    => 'https://example.com',
    'target' => '_blank',
    'type'   => 'secondary',
]);
```






# Button Group

## SCSS

```scss
@use 'resources/scss/components/button-group' as button-group;

```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- Groupe de boutons standard -->
<!-- wp:pattern {"slug":"component/button-group","args":{"buttons":[{"label":"Annuler","type":"ghost"},{"label":"Valider","type":"primary"}]}} /-->

<!-- Groupe de boutons attachés (Segmented Control) -->
<!-- wp:pattern {"slug":"component/button-group","args":{"attached":true,"buttons":[{"label":"Jour","type":"secondary"},{"label":"Semaine","type":"secondary"},{"label":"Mois","type":"primary"}]}} /-->

```

### PHP

```php
// Groupe aligné à droite
get_template_part('patterns/button-group/button-group', null, [
    'align'   => 'right',
    'buttons' => [
        [
            'label' => 'Retour',
            'type'  => 'outline',
            'url'   => '/precedents',
        ],
        [
            'label' => 'Enregistrer',
            'type'  => 'success',
        ],
    ],
]);
```