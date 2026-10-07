document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-component="cover"]').forEach((cover) => {
    const video = cover.querySelector('.cover__video');
    const toggle = cover.querySelector('.cover__video-toggle');
    const status = cover.querySelector('.cover__video-status');
    if (!video || !toggle || !status) return;

    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let visible = false;
    let userPaused = false;
    let failed = false;
    let playPending = false;

    const fail = (error) => {
      if (failed) return;
      failed = true;
      video.pause();
      video.hidden = true;
      toggle.hidden = true;
      status.hidden = false;
      status.textContent = 'La vidéo ne peut pas être chargée.';
      console.error('Cover video playback failed:', error);
    };

    const updateLabel = () => {
      toggle.textContent = video.paused ? 'Lire la vidéo' : 'Mettre la vidéo en pause';
    };

    const shouldPlay = () => visible && !document.hidden && !userPaused && !motion.matches && !failed;

    const updatePlayback = async () => {
      if (!shouldPlay()) {
        video.pause();
        return;
      }
      if (playPending || !video.paused) return;
      playPending = true;
      try {
        await video.play();
        if (!shouldPlay()) video.pause();
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          userPaused = true;
          updateLabel();
        } else if (error.name !== 'AbortError') {
          fail(error);
        }
      } finally {
        playPending = false;
      }
    };

    toggle.hidden = false;
    toggle.disabled = motion.matches;
    toggle.addEventListener('click', () => {
      userPaused = !video.paused;
      updatePlayback();
    });
    video.addEventListener('playing', () => {
      if (!failed) video.hidden = false;
    });
    video.addEventListener('play', updateLabel);
    video.addEventListener('pause', updateLabel);
    video.addEventListener('error', () => fail(video.error));
    video.querySelector('source')?.addEventListener('error', () => fail('Video source unavailable'));
    motion.addEventListener('change', () => {
      toggle.disabled = motion.matches;
      updatePlayback();
    });
    document.addEventListener('visibilitychange', updatePlayback);

    const observer = new IntersectionObserver(([entry]) => {
      visible = entry.isIntersecting;
      updatePlayback();
    });
    observer.observe(cover);
  });
});
