export function setProgress(component, value) {
  const track = component?.querySelector('[role="progressbar"]');
  const bar = component?.querySelector('[data-progress-bar]');
  const max = Number(component?.dataset.max);
  if (!track || !bar || !Number.isFinite(max) || max <= 0
    || (value !== null && (typeof value !== 'number' || !Number.isFinite(value) || value < 0 || value > max))) {
    console.error('Progression requires a valid component and a numeric value between 0 and max, or null.', component, value);
    return false;
  }
  const indeterminate = value === null;
  const percentage = indeterminate ? 0 : Math.round(value / max * 10000) / 100;
  component.classList.toggle('progress--indeterminate', indeterminate);
  if (indeterminate) track.removeAttribute('aria-valuenow');
  else track.setAttribute('aria-valuenow', String(value));
  bar.style.width = `${percentage}%`;
  const text = component.querySelector('[data-progress-text]');
  if (text) text.textContent = indeterminate ? component.querySelector('[data-progress-pending]')?.textContent || '' : `${percentage}%`;
  component.dispatchEvent(new CustomEvent('progress:changed', { bubbles: true, detail: { value, max } }));
  return true;
}

export function initProgress(root = document) {
  root.querySelectorAll('[data-progress]').forEach((component) => {
    if (component.dataset.progressReady) return;
    component.dataset.progressReady = 'true';
    component.addEventListener('progress:update', (event) => setProgress(component, event.detail?.value));
  });
}

document.addEventListener('DOMContentLoaded', () => initProgress());
