# Author Box

## SCSS

```scss
@use 'resources/scss/components/author-box' as author-box;

```

## JavaScript

No JavaScript for this component

## Template

### HTML

```html
<!-- wp:pattern {"slug":"component/author-box","args":{"name":"Arnaud Bodel","role":"Senior Full-Stack Developer","bio":"Passionné par l'architecture web.","avatar":{"src":"https://i.pravatar.cc/150?img=12","name":"Arnaud Bodel"}}} /-->

<!-- wp:pattern {"slug":"component/author-box-wp"} /-->

```

### PHP

```php
get_template_part('patterns/author-box/author-box', null, [
    'name'   => 'Arnaud Bodel',
    'role'   => 'Lead Developer',
    'bio'    => 'Développeur et architecte logiciel.',
    'avatar' => [
        'src'  => 'https://i.pravatar.cc/150?img=12',
        'name' => 'Arnaud Bodel',
        'size' => 'lg',
    ],
    'links'  => [
        ['url' => 'https://github.com', 'label' => 'GitHub'],
        ['url' => 'https://linkedin.com', 'label' => 'LinkedIn'],
    ],
]);

```