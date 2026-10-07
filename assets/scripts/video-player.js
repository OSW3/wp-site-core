export function initVideoPlayers(root = document) {
  root.querySelectorAll('[data-video-player]').forEach((component) => {
    if (component.dataset.videoReady) return;
    const frame = component.querySelector('.video-player__frame');
    const status = component.querySelector('[data-video-status]');
    const video = component.querySelector('video');
    const button = component.querySelector('[data-video-load]');
    if (!frame || !status || (!video && !button)) {
      console.error('Video player requires a frame, status and video or activation button.', component);
      return;
    }
    component.dataset.videoReady = 'true';
    const reportError = () => {
      status.hidden = false;
      console.error('Video player failed to load.', component);
    };
    if (video) {
      video.addEventListener('error', reportError);
      video.querySelector('source')?.addEventListener('error', reportError);
      video.querySelectorAll('track').forEach((track) => track.addEventListener('error', reportError));
      video.addEventListener('loadeddata', () => { status.hidden = true; });
      if (video.error || video.networkState === 3) reportError();
      return;
    }
    button.hidden = false;
    button.addEventListener('click', () => {
      if (frame.querySelector('iframe')) return;
      const iframe = document.createElement('iframe');
      iframe.title = component.querySelector('.video-player__title').textContent;
      iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
      iframe.allowFullscreen = true;
      iframe.referrerPolicy = 'strict-origin-when-cross-origin';
      iframe.addEventListener('error', reportError);
      iframe.src = button.dataset.videoEmbed;
      component.querySelector('[data-video-placeholder]').hidden = true;
      frame.append(iframe);
      iframe.focus({ preventScroll: true });
    });
  });
}

document.addEventListener('DOMContentLoaded', () => initVideoPlayers());
