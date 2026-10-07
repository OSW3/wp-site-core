export function focusElement(element) {
  if (!(element instanceof HTMLElement) || !element.isConnected) return;
  if (!element.hasAttribute('tabindex') && element.tabIndex < 0) {
    element.setAttribute('tabindex', '-1');
    element.addEventListener('blur', () => element.removeAttribute('tabindex'), { once: true });
  }
  element.focus({ preventScroll: true });
}
