import { Map as LibreMap, Marker, NavigationControl, AttributionControl, setWorkerUrl } from 'maplibre-gl';
import workerUrl from 'maplibre-gl/dist/maplibre-gl-worker.mjs?worker&url';
import 'maplibre-gl/dist/maplibre-gl.css';
import { mapStyle } from './map-config.js';

setWorkerUrl(workerUrl);

export function initMaps(root = document) {
  root.querySelectorAll('[data-map]').forEach((component) => {
    if (component.dataset.mapReady || component.hasAttribute('data-map-unavailable')) return;
    const config = JSON.parse(component.dataset.mapConfig);
    const canvas = component.querySelector('[data-map-canvas]');
    const placeholder = component.querySelector('[data-map-placeholder]');
    const button = component.querySelector('[data-map-load]');
    const status = component.querySelector('[data-map-status]');
    if (!canvas || !placeholder || !button || !status) {
      console.error('Map requires its canvas, placeholder, load button and status.', component);
      return;
    }
    component.dataset.mapReady = 'true';
    button.hidden = false;
    let map;
    let observer;
    let timeout;
    let loaded = false;
    let active = false;
    const reportError = () => {
      window.clearTimeout(timeout);
      status.textContent = component.querySelector('[data-map-error]').textContent;
      status.hidden = false;
      console.error(`Map provider ${config.provider} failed to load.`);
      if (loaded) return;
      observer?.disconnect();
      map?.remove();
      map = undefined;
      canvas.replaceChildren();
      canvas.hidden = true;
      placeholder.hidden = false;
      button.disabled = false;
      active = false;
    };
    const success = () => {
      window.clearTimeout(timeout);
      loaded = true;
      status.hidden = true;
      component.dispatchEvent(new CustomEvent('map:loaded', { bubbles: true, detail: { provider: config.provider } }));
    };
    const checkTimeout = () => {
      if (document.hidden) {
        timeout = window.setTimeout(checkTimeout, 20000);
        return;
      }
      reportError();
    };
    const load = () => {
      if (active) return;
      active = true;
      button.disabled = true;
      status.hidden = true;
      canvas.hidden = false;
      placeholder.hidden = true;
      timeout = window.setTimeout(checkTimeout, 20000);
      if (config.provider === 'google') {
        const iframe = document.createElement('iframe');
        iframe.title = config.title;
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.allowFullscreen = true;
        iframe.addEventListener('load', success, { once: true });
        iframe.addEventListener('error', reportError, { once: true });
        iframe.src = config.iframeUrl;
        canvas.append(iframe);
        return;
      }
      try {
        map = new LibreMap({
          container: canvas,
          style: mapStyle(config),
          center: [config.lng, config.lat], zoom: config.zoom,
          maxZoom: 19,
          scrollZoom: false,
          attributionControl: false,
          locale: config.locale,
          canvasContextAttributes: { preserveDrawingBuffer: false },
        });
      } catch (error) {
        if (error instanceof Error && /webgl|context/i.test(error.message)) {
          reportError();
          return;
        }
        throw error;
      }
      map.on('error', reportError);
      map.once('load', success);
      map.addControl(new NavigationControl({ showCompass: false }), 'top-right');
      map.addControl(new AttributionControl({ compact: false }), 'bottom-right');
      map.getCanvas().setAttribute('aria-label', config.title);
      if (config.marker) {
        const marker = document.createElement('span');
        marker.className = 'map__marker';
        marker.setAttribute('role', 'img');
        marker.setAttribute('aria-label', config.markerLabel);
        marker.title = config.markerLabel;
        new Marker({ element: marker }).setLngLat([config.lng, config.lat]).addTo(map);
      }
      if (config.provider === 'mapy') {
        const logo = document.createElement('a');
        logo.className = 'map__provider-logo';
        logo.href = 'https://mapy.com/';
        logo.target = '_blank';
        logo.rel = 'noopener';
        const image = document.createElement('img');
        image.src = 'https://api.mapy.com/img/api/logo.svg';
        image.alt = 'Mapy.com';
        image.height = 32;
        logo.append(image);
        canvas.append(logo);
      }
      observer = new ResizeObserver(() => map?.resize());
      observer.observe(canvas);
    };
    button.addEventListener('click', () => {
      load();
      if (active) {
        const target = canvas.querySelector('iframe') || map?.getCanvas();
        target?.focus({ preventScroll: true });
      }
    });
    if (component.dataset.consent === 'false') {
      if ('IntersectionObserver' in window) {
        const visibility = new IntersectionObserver((entries) => {
          if (!entries.some((entry) => entry.isIntersecting)) return;
          visibility.disconnect();
          load();
        }, { rootMargin: '100px' });
        visibility.observe(component);
      } else load();
    }
  });
}

document.addEventListener('DOMContentLoaded', () => initMaps());
