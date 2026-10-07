export function initScrollToTop(root = document) {
  const links = [];
  root.querySelectorAll('[data-scroll-to-top]').forEach((link) => {
    if (link.dataset.scrollToTopReady) return;
    const threshold = Number(link.dataset.threshold ?? 300);
    if (!Number.isFinite(threshold) || threshold < 0) {
      console.error('ScrollToTop requires a finite, non-negative threshold.', link);
      return;
    }
    link.dataset.scrollToTopReady = 'true';
    links.push({ link, threshold });
    link.addEventListener('click', (event) => {
      if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
      event.preventDefault();
      const content = document.querySelector('main, .site-content') || document.body;
      const target = content.querySelector('h1') || content;
      if (!target.hasAttribute('tabindex')) {
        target.setAttribute('tabindex', '-1');
        target.addEventListener('blur', () => target.removeAttribute('tabindex'), { once: true });
      }
      target.focus({ preventScroll: true });
      const smooth = link.dataset.smooth !== 'false' && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      window.scrollTo({ top: 0, left: window.scrollX, behavior: smooth ? 'smooth' : 'instant' });
      scheduleUpdate();
    });
    link.addEventListener('blur', () => scheduleUpdate());
  });
  if (!links.length) return;

  let frame;
  const update = () => {
    frame = undefined;
    links.forEach(({ link, threshold }) => {
      link.hidden = window.scrollY < threshold && document.activeElement !== link;
    });
  };
  const scheduleUpdate = () => {
    if (frame !== undefined) return;
    frame = window.requestAnimationFrame(update);
  };
  window.addEventListener('scroll', scheduleUpdate, { passive: true });
  window.addEventListener('pageshow', scheduleUpdate);
  update();
}

document.addEventListener('DOMContentLoaded', () => initScrollToTop());
