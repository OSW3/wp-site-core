document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-accordion]').forEach((accordion) => {
    const triggers = Array.from(accordion.querySelectorAll('.accordion__trigger'));
    const allowMultiple = accordion.dataset.accordionMode === 'multiple';

    const getPanel = (trigger) => {
      const panelId = trigger.getAttribute('aria-controls');
      return panelId
        ? Array.from(accordion.querySelectorAll('.accordion__panel')).find((panel) => panel.id === panelId)
        : null;
    };

    const setExpanded = (trigger, panel, expanded) => {
      trigger.setAttribute('aria-expanded', String(expanded));
      panel.hidden = !expanded;
      trigger.closest('.accordion__item')?.classList.toggle('accordion__item--open', expanded);
    };

    accordion.addEventListener('click', (event) => {
      const trigger = event.target instanceof Element
        ? event.target.closest('.accordion__trigger')
        : null;
      if (!trigger || !accordion.contains(trigger)) return;

      const panel = getPanel(trigger);
      if (!panel) return;

      const willExpand = trigger.getAttribute('aria-expanded') !== 'true';
      if (willExpand && !allowMultiple) {
        triggers.forEach((otherTrigger) => {
          if (otherTrigger === trigger) return;
          const otherPanel = getPanel(otherTrigger);
          if (otherPanel) setExpanded(otherTrigger, otherPanel, false);
        });
      }

      setExpanded(trigger, panel, willExpand);
    });

    accordion.addEventListener('keydown', (event) => {
      const trigger = event.target instanceof Element
        ? event.target.closest('.accordion__trigger')
        : null;
      if (!trigger) return;

      const currentIndex = triggers.indexOf(trigger);
      let nextIndex;

      switch (event.key) {
        case 'ArrowDown':
          nextIndex = (currentIndex + 1) % triggers.length;
          break;
        case 'ArrowUp':
          nextIndex = (currentIndex - 1 + triggers.length) % triggers.length;
          break;
        case 'Home':
          nextIndex = 0;
          break;
        case 'End':
          nextIndex = triggers.length - 1;
          break;
        default:
          return;
      }

      event.preventDefault();
      triggers[nextIndex]?.focus();
    });
  });
});