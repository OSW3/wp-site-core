document.addEventListener('DOMContentLoaded', () => {
  const dismissButtons = document.querySelectorAll('[data-alert-dismiss]');

  dismissButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const alert = button.closest('[data-alert]');

      if (!alert) return;

      alert.classList.add('alert--dismissed');

      // Suppression du DOM après la transition CSS
      alert.addEventListener('transitionend', () => {
        alert.remove();
      }, { once: true });
    });
  });
});