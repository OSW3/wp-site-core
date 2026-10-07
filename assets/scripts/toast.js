import { focusElement } from './focus.js';

const controllers = new WeakMap();
let triggersReady = false;

export function showToast(target, trigger = null) {
  const toast = typeof target === 'string' ? document.getElementById(target) : target;
  const controller = toast && controllers.get(toast);
  if (!controller) {
    console.error('Toast target is missing or has not been initialized.', target);
    return false;
  }
  controller.show(trigger);
  return true;
}

export function hideToast(target) {
  const toast = typeof target === 'string' ? document.getElementById(target) : target;
  const controller = toast && controllers.get(toast);
  if (!controller) {
    console.error('Toast target is missing or has not been initialized.', target);
    return false;
  }
  controller.hide();
  return true;
}

export function initToast(root = document) {
  root.querySelectorAll('[data-toast]').forEach((toast) => {
    if (controllers.has(toast)) return;
    const duration = Number(toast.dataset.duration);
    if (!Number.isFinite(duration) || duration < 0 || duration > 2147483647) {
      console.error('Toast duration must be between 0 and 2147483647 ms.', toast);
      return;
    }
    const position = ['top-left', 'top-right', 'bottom-left', 'bottom-right'].find((value) => toast.classList.contains(`toast--${value}`));
    if (!position) {
      console.error('Toast requires a valid position.', toast);
      return;
    }
    let stack = document.querySelector(`[data-toast-stack="${position}"]`);
    if (!stack) {
      stack = document.createElement('div');
      stack.className = `toast-stack toast-stack--${position}`;
      stack.dataset.toastStack = position;
      document.body.append(stack);
    }
    stack.append(toast);
    const announcer = document.createElement('span');
    announcer.className = 'toast__announcer';
    announcer.setAttribute('role', 'status');
    announcer.setAttribute('aria-live', 'polite');
    announcer.setAttribute('aria-atomic', 'true');
    stack.append(announcer);
    const pauses = new Set();
    let timer;
    let announcement;
    let started;
    let remaining = duration;
    let returnFocus;
    toast.hidden = true;
    toast.querySelectorAll('[data-toast-dismiss]').forEach((button) => {
      button.hidden = false;
      button.addEventListener('click', () => hide());
    });
    const hide = () => {
      if (toast.hidden) return;
      const restoreFocus = toast.contains(document.activeElement);
      toast.hidden = true;
      window.clearTimeout(timer);
      window.clearTimeout(announcement);
      timer = undefined;
      pauses.clear();
      if (restoreFocus) focusElement(returnFocus?.isConnected ? returnFocus : document.body);
      announcer.textContent = '';
      toast.dispatchEvent(new CustomEvent('toast:hidden', { bubbles: true }));
    };
    const schedule = () => {
      window.clearTimeout(timer);
      timer = undefined;
      if (!duration || pauses.size || toast.hidden) return;
      started = performance.now();
      timer = window.setTimeout(hide, remaining);
    };
    const pause = (reason) => {
      if (timer !== undefined) {
        remaining = Math.max(0, remaining - (performance.now() - started));
        window.clearTimeout(timer);
        timer = undefined;
      }
      pauses.add(reason);
    };
    const resume = (reason) => {
      if (!pauses.delete(reason)) return;
      schedule();
    };
    const show = (trigger) => {
      returnFocus = trigger || (toast.contains(document.activeElement) ? returnFocus : document.activeElement);
      remaining = duration;
      pauses.clear();
      toast.hidden = false;
      if (toast.matches(':hover')) pauses.add('hover');
      if (toast.contains(document.activeElement)) pauses.add('focus');
      if (document.hidden) pauses.add('visibility');
      else pauses.delete('visibility');
      announcer.textContent = '';
      window.clearTimeout(announcement);
      announcement = window.setTimeout(() => {
        announcer.textContent = [toast.querySelector('.toast__title')?.textContent, toast.querySelector('.toast__content')?.textContent].filter(Boolean).join('. ');
      }, 0);
      schedule();
      toast.dispatchEvent(new CustomEvent('toast:shown', { bubbles: true }));
    };
    controllers.set(toast, { show, hide });
    toast.addEventListener('toast:show', () => show(null));
    toast.addEventListener('toast:hide', hide);
    toast.addEventListener('pointerenter', () => pause('hover'));
    toast.addEventListener('pointerleave', () => resume('hover'));
    toast.addEventListener('focusin', () => pause('focus'));
    toast.addEventListener('focusout', (event) => {
      if (!toast.contains(event.relatedTarget)) resume('focus');
    });
    toast.addEventListener('keydown', (event) => {
      if (event.key !== 'Escape') return;
      event.preventDefault();
      hide();
    });
    document.addEventListener('visibilitychange', () => {
      document.hidden ? pause('visibility') : resume('visibility');
    });
    if (toast.dataset.autoShow === 'true') show(null);
  });
  if (triggersReady) return;
  triggersReady = true;
  document.addEventListener('click', (event) => {
    const trigger = event.target instanceof Element ? event.target.closest('[data-toast-target]') : null;
    if (!trigger || trigger.matches(':disabled, [aria-disabled="true"]')) return;
    if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    if (showToast(trigger.dataset.toastTarget, trigger)) event.preventDefault();
  });
  document.addEventListener('keydown', (event) => {
    const trigger = event.target instanceof Element ? event.target.closest('[data-toast-target]') : null;
    if (!trigger || trigger.tagName === 'BUTTON' || !['Enter', ' '].includes(event.key)) return;
    if (trigger.matches(':disabled, [aria-disabled="true"]')) return;
    event.preventDefault();
    trigger.click();
  });
}

document.addEventListener('DOMContentLoaded', () => initToast());
