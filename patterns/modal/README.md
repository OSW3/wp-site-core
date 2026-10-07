# Modal

The trigger and modal are separate components. Give both the same `modal_id`; this lets a link, a button, or multiple triggers open the same dialog. The modal uses the native HTML `<dialog>` element, so the browser handles focus, Escape-key dismissal, and focus restoration.

## WordPress template integration

Add both pattern calls where they should render in the template, for example:

```html
<!-- wp:pattern {"slug":"component/modal-trigger","args":{"modal_id":"project-details","label":"Voir les détails","element":"a","type":"primary"}} /-->

<!-- wp:pattern {"slug":"component/modal","args":{"modal_id":"project-details","title":"Détails du projet","content":"<p>Contenu de la fenêtre modale.</p>","size":"medium","close_label":"Fermer"}} /-->
```

To render a button instead of a link, set `"element":"button"` on `component/modal-trigger`. Add more trigger calls with the same `modal_id` to open the same modal from multiple places.

The `content` value accepts WordPress-filtered HTML via `wp_kses_post()`. Trigger types accept the button component variants; invalid elements and variants fall back to `a` and `primary`. Invalid modal sizes fall back to `medium`.
