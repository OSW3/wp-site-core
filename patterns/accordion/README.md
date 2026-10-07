# Accordion

Use `component/accordion` with an `items` array. Answers accept WordPress-filtered HTML.

```html
<!-- wp:pattern {"slug":"component/accordion","args":{"title":"Questions fréquentes","allow_multiple":false,"open_first":true,"items":[{"question":"Quels sont les délais ?","answer":"<p>Entre 4 et 8 semaines selon le projet.</p>"},{"question":"Proposez-vous de la maintenance ?","answer":"<p>Oui, nous proposons un accompagnement et une maintenance.</p>"}]}} /-->
```

- `allow_multiple`: `false` (default) closes the other panel when one opens; `true` allows several open panels.
- `open_first`: opens the first item by default (`true` by default). Individual items can also set `"open":true`.
- `heading_level`: heading level from 2 to 6 (`3` by default).

Every accordion instance receives unique panel IDs. Trigger buttons expose their panel state to assistive technology, and Arrow Up/Down, Home, and End move focus between questions.
