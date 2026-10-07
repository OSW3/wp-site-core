import { focusElement } from './focus.js';

const initialized = new WeakSet();
const lockedPanels = new Set();
let scrollState;
let triggersReady = false;

function lockScroll(panel) {
  if (lockedPanels.has(panel)) return;
  if (!lockedPanels.size) {
    const body = document.body;
    const html = document.documentElement;
    const properties = [
      [html, 'overflow', 'hidden'],
      [body, 'position', 'fixed'],
      [body, 'top', `${-window.scrollY}px`],
      [body, 'left', `${-window.scrollX}px`],
      [body, 'width', '100%'],
      [body, 'overflow', 'hidden'],
      [body, 'padding-right', `${parseFloat(getComputedStyle(body).paddingRight) + Math.max(0, window.innerWidth - html.clientWidth)}px`],
    ];
    scrollState = {
      x: window.scrollX, y: window.scrollY,
      properties: properties.map(([element, name, value]) => {
        const previous = element.style.getPropertyValue(name);
        const priority = element.style.getPropertyPriority(name);
        element.style.setProperty(name, value, 'important');
        return { element, name, previous, priority };
      }),
    };
  }
  lockedPanels.add(panel);
}

function unlockScroll(panel) {
  if (!lockedPanels.delete(panel) || lockedPanels.size) return;
  scrollState.properties.forEach(({ element, name, previous, priority }) => {
    if (previous) element.style.setProperty(name, previous, priority);
    else element.style.removeProperty(name);
  });
  window.scrollTo({ left: scrollState.x, top: scrollState.y, behavior: 'instant' });
  scrollState = undefined;
}

function syncTriggers(panel) {
  document.querySelectorAll('[data-offcanvas-open]').forEach((trigger) => {
    if (trigger.dataset.offcanvasOpen === panel.id) trigger.setAttribute('aria-expanded', String(panel.open));
  });
}

export function openOffcanvas(target, trigger = null) {
  const panel = typeof target === 'string' ? document.getElementById(target) : target;
  if (!(panel instanceof HTMLDialogElement) || !panel.matches('[data-offcanvas]') || typeof panel.showModal !== 'function') {
    console.error('Offcanvas requires an existing dialog and native dialog support.', target);
    return false;
  }
  initOffcanvas();
  if (panel.open) return true;
  if (trigger) focusElement(trigger);
  panel.showModal();
  lockScroll(panel);
  syncTriggers(panel);
  return true;
}

export function closeOffcanvas(target) {
  const panel = typeof target === 'string' ? document.getElementById(target) : target;
  if (!(panel instanceof HTMLDialogElement) || !panel.matches('[data-offcanvas]')) {
    console.error('Offcanvas target does not exist.', target);
    return false;
  }
  panel.close();
  unlockScroll(panel);
  syncTriggers(panel);
  return true;
}

export function initOffcanvas(root = document) {
  root.querySelectorAll('[data-offcanvas]').forEach((panel) => {
    if (initialized.has(panel)) return;
    if (!(panel instanceof HTMLDialogElement) || typeof panel.showModal !== 'function') {
      console.error('Offcanvas requires native dialog support.', panel);
      return;
    }
    initialized.add(panel);
    panel.addEventListener('cancel', (event) => {
      event.preventDefault();
      closeOffcanvas(panel);
    });
    panel.querySelectorAll('[data-offcanvas-close]').forEach((close) => {
      close.addEventListener('click', () => closeOffcanvas(panel));
    });
    panel.addEventListener('close', () => {
      if (panel.open) return;
      unlockScroll(panel);
      syncTriggers(panel);
      panel.dispatchEvent(new CustomEvent('offcanvas:closed', { bubbles: true }));
    });
    panel.addEventListener('click', (event) => {
      if (event.target !== panel) return;
      const rect = panel.getBoundingClientRect();
      if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) closeOffcanvas(panel);
    });
    panel.addEventListener('offcanvas:open', () => openOffcanvas(panel));
    panel.addEventListener('offcanvas:close', () => closeOffcanvas(panel));
    new MutationObserver(() => {
      panel.open ? lockScroll(panel) : unlockScroll(panel);
      syncTriggers(panel);
    }).observe(panel, { attributes: true, attributeFilter: ['open'] });
    if (panel.open) lockScroll(panel);
  });
  if (triggersReady) return;
  triggersReady = true;
  document.addEventListener('click', (event) => {
    const trigger = event.target instanceof Element ? event.target.closest('[data-offcanvas-open]') : null;
    if (!trigger || trigger.matches(':disabled, [aria-disabled="true"]')
      || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    if (openOffcanvas(trigger.dataset.offcanvasOpen, trigger)) event.preventDefault();
  });
  document.addEventListener('keydown', (event) => {
    const trigger = event.target instanceof Element ? event.target.closest('[data-offcanvas-open]') : null;
    if (!trigger || trigger.tagName === 'BUTTON' || !['Enter', ' '].includes(event.key)
      || trigger.matches(':disabled, [aria-disabled="true"]')) return;
    event.preventDefault();
    trigger.click();
  });
}

document.addEventListener('DOMContentLoaded', () => initOffcanvas());
