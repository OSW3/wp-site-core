const TRANSITION_DURATION = 400;

export class Carousel {
  constructor(element) {
    this.carousel = element;
    this.track = element.querySelector('.carousel__track');
    this.slides = this.track ? Array.from(this.track.children) : [];
    this.prevBtn = element.querySelector('.carousel__control--prev');
    this.nextBtn = element.querySelector('.carousel__control--next');
    this.dotsContainer = element.querySelector('.carousel__dots');
    this.autoplayToggle = element.querySelector('.carousel__autoplay-toggle');

    this.isLoop = element.dataset.loop === 'true';
    this.autoplayEnabled = element.dataset.autoplay === 'true';
    this.autoplayDelay = Number.parseInt(element.dataset.delay, 10) || 5000;
    this.isAnimating = false;
    this.timer = null;
    this.isHovered = false;
    this.hasFocus = false;
    this.explicitlyResumed = false;
    this.userPaused = false;
    this.pointerStart = null;
    this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    this.slideIndexes = new Map(this.slides.map((slide, index) => [slide, index]));

    if (!this.track || this.slides.length === 0) return;

    this.init();
  }

  init() {
    this.carousel.setAttribute('role', 'region');
    this.carousel.setAttribute('aria-roledescription', 'carrousel');
    this.carousel.setAttribute('tabindex', this.carousel.getAttribute('tabindex') || '0');

    this.slides.forEach((slide, index) => {
      slide.setAttribute('role', 'group');
      slide.setAttribute('aria-roledescription', 'diapositive');
      slide.setAttribute('aria-label', `${index + 1} sur ${this.slides.length}`);
    });

    this.bindEvents();
    this.updateLayout();
    this.updateState();

    if (this.autoplayEnabled) this.scheduleAutoplay();
  }

  get visibleCount() {
    if (!this.slides.length) return 0;
    const width = this.slides[0].getBoundingClientRect().width;
    const viewportWidth = this.carousel.querySelector('.carousel__track-container')?.clientWidth || width;
    return width > 0 ? Math.max(1, Math.round(viewportWidth / width)) : 1;
  }

  get currentIndex() {
    return this.slideIndexes.get(this.track.firstElementChild) ?? 0;
  }

  get canMove() {
    return this.slides.length > this.visibleCount;
  }

  get slideWidth() {
    return this.slides[0]?.getBoundingClientRect().width || 0;
  }

  createDots() {
    if (!this.dotsContainer) return;

    const dotCount = this.isLoop ? this.slides.length : Math.max(1, this.slides.length - this.visibleCount + 1);
    if (this.dots?.length === dotCount) return;

    this.dotsContainer.replaceChildren();
    this.dotIndexes = Array.from({ length: dotCount }, (_, index) => index);
    this.dots = this.dotIndexes.map((index) => {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = 'carousel__dot';
      dot.setAttribute('aria-label', `Afficher la diapositive ${index + 1}`);
      dot.addEventListener('click', () => this.goTo(index));
      this.dotsContainer.appendChild(dot);
      return dot;
    });
  }

  updateLayout() {
    if (this.isAnimating) return;
    const width = this.slideWidth;
    if (width > 0) this.track.style.transform = 'translateX(0)';
    if (!this.isLoop) {
      const lastStart = Math.max(0, this.slides.length - this.visibleCount);
      while (this.currentIndex > lastStart) {
        this.track.insertBefore(this.track.lastElementChild, this.track.firstElementChild);
      }
    }
    this.updateState();
    this.scheduleAutoplay();
  }

  updateState() {
    this.createDots();
    const visibleCount = this.visibleCount;
    const visibleSlides = new Set(Array.from(this.track.children).slice(0, visibleCount));

    this.slides.forEach((slide, index) => {
      const isActive = slide === this.track.firstElementChild;
      const isVisible = visibleSlides.has(slide);
      slide.classList.toggle('carousel__slide--active', isActive);
      slide.setAttribute('aria-hidden', String(!isVisible));
      slide.inert = !isVisible;
      slide.setAttribute('aria-label', `${index + 1} sur ${this.slides.length}`);
    });

    const activeIndex = this.currentIndex;
    this.dots?.forEach((dot, index) => {
      if (this.dotIndexes[index] === activeIndex) {
        dot.classList.add('carousel__dot--active');
        dot.setAttribute('aria-current', 'true');
      } else {
        dot.classList.remove('carousel__dot--active');
        dot.removeAttribute('aria-current');
      }
    });

    const canMove = this.canMove;
    [this.prevBtn, this.nextBtn, this.dotsContainer, this.autoplayToggle].forEach((control) => {
      if (control) control.hidden = !canMove;
    });

    if (!this.isLoop) {
      if (this.prevBtn) this.prevBtn.disabled = activeIndex === 0;
      if (this.nextBtn) this.nextBtn.disabled = activeIndex >= this.slides.length - visibleCount;
    }

    this.carousel.setAttribute('aria-live', this.autoplayEnabled && !this.userPaused ? 'off' : 'polite');
  }

  async move(direction) {
    const offset = direction === 'next' ? 1 : -1;
    return this.moveTo(this.currentIndex + offset);
  }

  async moveTo(targetIndex) {
    if (this.isAnimating || !this.canMove || !Number.isInteger(targetIndex)) return false;

    const currentIndex = this.currentIndex;
    const lastStart = Math.max(0, this.slides.length - this.visibleCount);
    let distance;

    if (this.isLoop) {
      const normalizedTarget = (targetIndex % this.slides.length + this.slides.length) % this.slides.length;
      const forward = (normalizedTarget - currentIndex + this.slides.length) % this.slides.length;
      const backward = forward - this.slides.length;
      distance = Math.abs(forward) <= Math.abs(backward) ? forward : backward;
      targetIndex = normalizedTarget;
    } else {
      targetIndex = Math.min(Math.max(targetIndex, 0), lastStart);
      distance = targetIndex - currentIndex;
    }

    if (distance === 0) return false;

    this.isAnimating = true;
    const width = this.slideWidth;
    if (width <= 0) {
      this.isAnimating = false;
      return false;
    }

    this.setActiveDot(targetIndex);

    if (distance > 0) {
      await this.transitionTo(`translateX(-${width * distance}px)`);
      for (let index = 0; index < distance; index += 1) {
        this.track.appendChild(this.track.firstElementChild);
      }
    } else {
      for (let index = 0; index < Math.abs(distance); index += 1) {
        this.track.insertBefore(this.track.lastElementChild, this.track.firstElementChild);
      }
      this.track.style.transition = 'none';
      this.track.style.transform = `translateX(-${width * Math.abs(distance)}px)`;
      this.track.getBoundingClientRect();
      await this.transitionTo('translateX(0)');
    }

    this.track.style.transition = 'none';
    this.track.style.transform = 'translateX(0)';
    this.track.getBoundingClientRect();
    this.track.style.removeProperty('transition');
    this.track.style.removeProperty('transform');
    this.isAnimating = false;
    this.updateState();
    this.scheduleAutoplay();
    return true;
  }

  setActiveDot(activeIndex) {
    this.dots?.forEach((dot, index) => {
      const isActive = this.dotIndexes[index] === activeIndex;
      dot.classList.toggle('carousel__dot--active', isActive);
      if (isActive) {
        dot.setAttribute('aria-current', 'true');
      } else {
        dot.removeAttribute('aria-current');
      }
    });
  }

  transitionTo(transform) {
    if (this.reducedMotion.matches) {
      this.track.style.transition = 'none';
      this.track.style.transform = transform;
      return Promise.resolve();
    }

    this.track.style.transition = `transform ${TRANSITION_DURATION}ms ease-out`;
    this.track.style.transform = transform;

    return new Promise((resolve) => {
      let timeout;
      const finish = (event) => {
        if (event && (event.target !== this.track || event.propertyName !== 'transform')) return;
        this.track.removeEventListener('transitionend', finish);
        window.clearTimeout(timeout);
        resolve();
      };

      this.track.addEventListener('transitionend', finish);
      timeout = window.setTimeout(() => finish(), TRANSITION_DURATION + 100);
    });
  }

  async goTo(targetIndex) {
    if (this.isAnimating || !this.canMove) return;
    this.stopAutoplay();
    await this.moveTo(targetIndex);
    this.scheduleAutoplay();
  }

  canAutoplay() {
    return this.autoplayEnabled
      && !this.userPaused
      && !this.isHovered
      && (!this.hasFocus || this.explicitlyResumed)
      && !document.hidden
      && !this.reducedMotion.matches
      && this.canMove
      && !this.isAnimating;
  }

  scheduleAutoplay() {
    this.stopAutoplay();
    if (!this.canAutoplay()) return;

    this.timer = window.setTimeout(async () => {
      await this.move('next');
      this.scheduleAutoplay();
    }, this.autoplayDelay);
  }

  stopAutoplay() {
    if (this.timer !== null) {
      window.clearTimeout(this.timer);
      this.timer = null;
    }
  }

  bindEvents() {
    this.prevBtn?.addEventListener('click', () => {
      this.stopAutoplay();
      this.move('prev').then(() => this.scheduleAutoplay());
    });

    this.nextBtn?.addEventListener('click', () => {
      this.stopAutoplay();
      this.move('next').then(() => this.scheduleAutoplay());
    });

    this.autoplayToggle?.addEventListener('click', () => {
      this.userPaused = !this.userPaused;
      this.explicitlyResumed = !this.userPaused;
      this.autoplayToggle.setAttribute('aria-pressed', String(this.userPaused));
      this.autoplayToggle.setAttribute(
        'aria-label',
        this.userPaused ? 'Lire automatiquement les diapositives' : 'Mettre le carrousel en pause',
      );
      const label = this.autoplayToggle.querySelector('.carousel__autoplay-label');
      if (label) label.textContent = this.userPaused ? 'Lecture' : 'Pause';
      this.updateState();
      this.scheduleAutoplay();
    });

    this.carousel.addEventListener('mouseenter', () => {
      this.isHovered = true;
      this.stopAutoplay();
    });
    this.carousel.addEventListener('mouseleave', () => {
      this.isHovered = false;
      this.scheduleAutoplay();
    });
    this.carousel.addEventListener('focusin', () => {
      this.hasFocus = true;
      this.explicitlyResumed = false;
      this.stopAutoplay();
    });
    this.carousel.addEventListener('focusout', (event) => {
      if (event.relatedTarget instanceof Node && this.carousel.contains(event.relatedTarget)) return;
      this.hasFocus = false;
      this.explicitlyResumed = false;
      this.scheduleAutoplay();
    });

    this.carousel.addEventListener('keydown', (event) => {
      if (event.target !== this.carousel) return;
      if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
      event.preventDefault();
      this.stopAutoplay();
      this.move(event.key === 'ArrowRight' ? 'next' : 'prev').then(() => this.scheduleAutoplay());
    });

    const trackContainer = this.carousel.querySelector('.carousel__track-container');
    trackContainer?.addEventListener('pointerdown', (event) => {
      if (!event.isPrimary || event.button !== 0) return;
      if (event.target instanceof Element && event.target.closest('a, button, input, select, textarea')) return;
      this.pointerStart = { id: event.pointerId, x: event.clientX, y: event.clientY };
      trackContainer.setPointerCapture(event.pointerId);
    });
    trackContainer?.addEventListener('pointerup', (event) => {
      if (!this.pointerStart || this.pointerStart.id !== event.pointerId) return;
      const deltaX = event.clientX - this.pointerStart.x;
      const deltaY = event.clientY - this.pointerStart.y;
      this.pointerStart = null;

      if (Math.abs(deltaX) < 50 || Math.abs(deltaX) <= Math.abs(deltaY)) return;
      this.stopAutoplay();
      this.move(deltaX < 0 ? 'next' : 'prev').then(() => this.scheduleAutoplay());
    });
    trackContainer?.addEventListener('pointercancel', () => {
      this.pointerStart = null;
    });

    document.addEventListener('visibilitychange', () => this.scheduleAutoplay());
    this.reducedMotion.addEventListener('change', () => this.scheduleAutoplay());
    window.addEventListener('resize', () => this.updateLayout(), { passive: true });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-component="carousel"]').forEach((element) => new Carousel(element));
});
