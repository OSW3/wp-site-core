document.addEventListener('DOMContentLoaded', () => {
  const initializedModals = new WeakSet();

  document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
    const modalId = trigger.getAttribute('data-modal-open');
    const modal = modalId ? document.getElementById(modalId) : null;

    if (!(modal instanceof HTMLDialogElement)) return;

    trigger.addEventListener('click', (event) => {
      if (trigger instanceof HTMLAnchorElement) event.preventDefault();
      if (!modal.open) modal.showModal();
    });

    if (initializedModals.has(modal)) return;
    initializedModals.add(modal);

    modal.querySelectorAll('[data-modal-close]').forEach((closeButton) => {
      closeButton.addEventListener('click', () => modal.close());
    });

    modal.addEventListener('click', (event) => {
      if (event.target === modal) modal.close();
    });
  });
});
