import { metadataEndpoint, radioMetadata, artworkUrl } from './webradio-metadata.js';

export function initWebradios(root = document) {
  root.querySelectorAll('[data-webradio]').forEach((component) => {
    if (component.dataset.radioReady) return;
    const config = JSON.parse(component.dataset.radioConfig);
    const audio = component.querySelector('audio');
    const status = component.querySelector('[data-radio-status]');
    const audioStatus = component.querySelector('[data-radio-audio-status]');
    const artist = component.querySelector('[data-radio-artist]');
    const title = component.querySelector('[data-radio-title]');
    const version = component.querySelector('[data-radio-version]');
    const versionRow = component.querySelector('[data-radio-version-row]');
    const artwork = component.querySelector('[data-radio-artwork]');
    const placeholder = component.querySelector('[data-radio-artwork-placeholder]');
    const controls = component.querySelector('[data-radio-controls]');
    const play = component.querySelector('[data-radio-play]');
    const stopButton = component.querySelector('[data-radio-stop]');
    const volume = component.querySelector('[data-radio-volume]');
    const volumeValue = component.querySelector('[data-radio-volume-value]');
    const state = component.querySelector('[data-radio-state]');
    if (![audio, status, audioStatus, artist, title, version, versionRow, artwork, placeholder, controls, play, stopButton, volume, volumeValue, state].every(Boolean)) {
      console.error('Webradio requires its audio, custom controls, metadata, artwork and status elements.', component);
      return;
    }
    component.dataset.radioReady = 'true';
    const endpoint = metadataEndpoint(config, document.baseURI);
    let timer;
    let controller;
    let generation = 0;
    let displayed = '';
    let active = false;
    let playback = 0;
    let loadTimeout;
    controls.hidden = false;
    audio.volume = config.volume;
    const setState = (value) => {
      component.dataset.radioState = value;
      state.textContent = config.states[value];
      play.disabled = active;
      stopButton.disabled = !active;
    };
    setState('stopped');
    const message = (selector) => component.querySelector(selector).textContent;
    const hideArtwork = () => {
      artwork.hidden = true;
      artwork.removeAttribute('src');
      placeholder.hidden = false;
    };
    const clearMetadata = () => {
      displayed = '';
      artist.textContent = message('[data-radio-unknown]');
      title.textContent = message('[data-radio-unknown]');
      version.textContent = '';
      versionRow.hidden = true;
      hideArtwork();
    };
    const stop = () => {
      generation++;
      window.clearTimeout(timer);
      timer = undefined;
      controller?.abort();
      controller = undefined;
    };
    const releaseAudio = () => {
      active = false;
      playback++;
      window.clearTimeout(loadTimeout);
      stop();
      audio.pause();
      audio.removeAttribute('src');
      audio.load();
    };
    const playbackError = (error) => {
      releaseAudio();
      clearMetadata();
      setState('error');
      audioStatus.hidden = false;
      console.error('Webradio audio stream failed.', error);
    };
    const loading = () => {
      if (!active) return;
      setState('loading');
      window.clearTimeout(loadTimeout);
      loadTimeout = window.setTimeout(() => {
        if (active) playbackError(new Error('Radio playback timed out.'));
      }, 30000);
    };
    const refresh = async () => {
      const current = generation;
      const request = new AbortController();
      controller = request;
      const timeout = window.setTimeout(() => request.abort(), 10000);
      try {
        const response = await fetch(endpoint, { signal: request.signal, credentials: 'omit', cache: 'no-store' });
        if (!response.ok) throw new Error(`Metadata HTTP ${response.status}.`);
        const track = radioMetadata(await response.json(), config, document.baseURI);
        const cover = artworkUrl(track.artwork, endpoint);
        if (current !== generation) return;
        const signature = JSON.stringify(track);
        status.hidden = true;
        if (signature !== displayed) {
          artist.textContent = track.artist || message('[data-radio-unknown]');
          title.textContent = track.title || message('[data-radio-unknown]');
          version.textContent = track.version;
          versionRow.hidden = !track.version;
          hideArtwork();
          if (cover) {
            artwork.src = cover;
            artwork.hidden = false;
            placeholder.hidden = true;
          }
          displayed = signature;
        }
        if (!track.artist && !track.title) {
          status.textContent = message('[data-radio-empty]');
          status.hidden = false;
        }
      } catch (error) {
        if (current !== generation) return;
        clearMetadata();
        status.textContent = message('[data-radio-error]');
        status.hidden = false;
        console.error('Webradio metadata failed.', error);
      } finally {
        window.clearTimeout(timeout);
        if (current === generation) {
          controller = undefined;
          if (active && !audio.paused && !audio.ended) {
            timer = window.setTimeout(() => {
              timer = undefined;
              refresh();
            }, config.interval * 1000);
          }
        }
      }
    };
    artwork.addEventListener('error', () => {
      hideArtwork();
      displayed = '';
      status.textContent = message('[data-radio-artwork-error]');
      status.hidden = false;
      console.error('Webradio artwork failed to load.', component);
    });
    play.addEventListener('click', async () => {
      if (active) return;
      active = true;
      const attempt = ++playback;
      audioStatus.hidden = true;
      status.hidden = true;
      audio.src = config.src;
      loading();
      try {
        await audio.play();
      } catch (error) {
        if (attempt !== playback) return;
        playbackError(error);
      }
    });
    stopButton.addEventListener('click', () => {
      releaseAudio();
      clearMetadata();
      status.hidden = true;
      audioStatus.hidden = true;
      setState('stopped');
      play.focus({ preventScroll: true });
    });
    volume.addEventListener('input', () => {
      audio.volume = Number(volume.value) / 100;
      volumeValue.textContent = `${volume.value}%`;
    });
    audio.addEventListener('playing', () => {
      if (!active) return;
      window.clearTimeout(loadTimeout);
      setState('playing');
      if (!controller && !timer) refresh();
    });
    audio.addEventListener('waiting', loading);
    audio.addEventListener('stalled', loading);
    audio.addEventListener('pause', () => {
      if (!active) return;
      releaseAudio();
      clearMetadata();
      status.hidden = true;
      setState('stopped');
    });
    audio.addEventListener('ended', () => {
      if (active) playbackError(new Error('Radio stream ended.'));
    });
    audio.addEventListener('error', () => {
      if (active) playbackError(audio.error);
    });
    if (audio.error) {
      audioStatus.hidden = false;
      console.error('Webradio audio stream failed before initialization.', component);
    }
    window.addEventListener('pagehide', () => {
      releaseAudio();
      clearMetadata();
      setState('stopped');
    });
  });
}

document.addEventListener('DOMContentLoaded', () => initWebradios());
