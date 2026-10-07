export function floatingPosition(anchor, panel, viewport, direction = 'dropdown') {
  const gap = 8;
  const positions = {
    dropdown: { left: anchor.left, top: anchor.bottom + gap },
    dropup: { left: anchor.left, top: anchor.top - gap - panel.height },
    dropleft: { left: anchor.left - gap - panel.width, top: anchor.top },
    dropright: { left: anchor.right + gap, top: anchor.top },
  };
  const opposites = { dropdown: 'dropup', dropup: 'dropdown', dropleft: 'dropright', dropright: 'dropleft' };
  let placement = Object.hasOwn(positions, direction) ? direction : 'dropdown';
  const fits = (position) => {
    if (placement === 'dropdown' || placement === 'dropup') {
      return position.top >= gap && position.top + panel.height <= viewport.height - gap;
    }
    return position.left >= gap && position.left + panel.width <= viewport.width - gap;
  };
  if (!fits(positions[placement]) && fits(positions[opposites[placement]])) {
    placement = opposites[placement];
  }
  const position = positions[placement];
  return {
    placement,
    left: Math.max(gap, Math.min(position.left, viewport.width - panel.width - gap)),
    top: Math.max(gap, Math.min(position.top, viewport.height - panel.height - gap)),
  };
}

export function initFloating(root = document) {
  root.querySelectorAll('[data-floating]').forEach((component) => {
    const trigger = component.querySelector('[data-floating-trigger]');
    const panel = component.querySelector('[data-floating-panel]');
    if (!trigger || !panel || component.dataset.floatingReady) return;
    if (typeof panel.showPopover !== 'function') {
      console.error('Floating components require a browser supporting the Popover API.', component);
      return;
    }
    component.dataset.floatingReady = 'true';
    const tooltip = component.dataset.floating === 'tooltip';
    let open = false;
    let timer;
    let hovered = false;
    let focused = false;
    let dismissed = false;

    const position = () => {
      if (!open) return;
      const anchor = trigger.getBoundingClientRect();
      const rect = panel.getBoundingClientRect();
      const result = floatingPosition(anchor, rect, {
        width: window.innerWidth,
        height: window.innerHeight,
      }, component.dataset.direction);
      panel.style.left = `${result.left}px`;
      panel.style.top = `${result.top}px`;
      component.dataset.placement = result.placement;
    };
    const close = (restoreFocus = false) => {
      window.clearTimeout(timer);
      if (!open) return;
      const focusInside = panel.contains(document.activeElement);
      panel.hidePopover();
      open = false;
      if (!tooltip) trigger.setAttribute('aria-expanded', 'false');
      if (restoreFocus || focusInside) trigger.focus();
    };
    const show = () => {
      window.clearTimeout(timer);
      if (open) return;
      panel.showPopover();
      open = true;
      if (!tooltip) trigger.setAttribute('aria-expanded', 'true');
      position();
    };

    if (tooltip) {
      const refresh = () => {
        window.clearTimeout(timer);
        if ((hovered || focused) && !dismissed) show();
        else timer = window.setTimeout(() => close(), 100);
      };
      [trigger, panel].forEach((element) => {
        element.addEventListener('pointerenter', () => {
          hovered = true;
          dismissed = false;
          refresh();
        });
        element.addEventListener('pointerleave', () => {
          hovered = false;
          refresh();
        });
      });
      trigger.addEventListener('focus', () => {
        focused = true;
        dismissed = false;
        refresh();
      });
      trigger.addEventListener('blur', () => {
        focused = false;
        refresh();
      });
    } else {
      trigger.addEventListener('click', (event) => {
        if (component.dataset.floating === 'popover' && trigger instanceof HTMLAnchorElement) {
          event.preventDefault();
        }
        open ? close() : show();
      });
      if (trigger.getAttribute('role') === 'button' && trigger.tagName !== 'BUTTON') {
        trigger.addEventListener('keydown', (event) => {
          if (event.key !== 'Enter' && event.key !== ' ') return;
          event.preventDefault();
          trigger.click();
        });
      }
      panel.querySelectorAll('[data-floating-close]').forEach((button) => {
        button.addEventListener('click', () => close(true));
      });
      if (component.dataset.floating === 'dropdown') {
        trigger.addEventListener('keydown', (event) => {
          if (!['ArrowDown', 'ArrowUp'].includes(event.key)) return;
          event.preventDefault();
          show();
          const links = panel.querySelectorAll('a[href]');
          (event.key === 'ArrowDown' ? links[0] : links[links.length - 1])?.focus();
        });
        panel.addEventListener('keydown', (event) => {
          const links = Array.from(panel.querySelectorAll('a[href]'));
          const index = links.indexOf(document.activeElement);
          if (index < 0) return;
          let next;
          if (event.key === 'ArrowDown') next = (index + 1) % links.length;
          else if (event.key === 'ArrowUp') next = (index - 1 + links.length) % links.length;
          else if (event.key === 'Home') next = 0;
          else if (event.key === 'End') next = links.length - 1;
          else return;
          event.preventDefault();
          links[next].focus();
        });
        panel.addEventListener('click', (event) => {
          if (event.target instanceof Element && event.target.closest('a[href]')) close();
        });
      }
    }

    document.addEventListener('pointerdown', (event) => {
      if (!component.contains(event.target)) close();
    });
    document.addEventListener('keydown', (event) => {
      if (event.key !== 'Escape' || !open) return;
      dismissed = true;
      close(!tooltip);
      event.preventDefault();
    });
    document.addEventListener('focusin', (event) => {
      if (!tooltip && !component.contains(event.target)) close();
    });
    window.addEventListener('resize', position, { passive: true });
    window.addEventListener('scroll', position, { capture: true, passive: true });
  });
}

document.addEventListener('DOMContentLoaded', () => initFloating());
