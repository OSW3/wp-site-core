function text(value) {
  return typeof value === 'string' ? value.trim() : '';
}

function field(data, path) {
  if (!path) return '';
  let value = data;
  for (const key of path.split('.')) {
    if (['__proto__', 'prototype', 'constructor'].includes(key)
      || value === null || typeof value !== 'object' || !Object.hasOwn(value, key)) return '';
    value = value[key];
  }
  return text(value);
}

export function metadataEndpoint(config, base) {
  if (config.apiUrl) return new URL(config.apiUrl, base).href;
  return new URL('/status-json.xsl', new URL(config.src, base)).href;
}

export function radioMetadata(data, config, base) {
  if (data === null || typeof data !== 'object') throw new Error('Invalid metadata response.');
  if (config.apiUrl) {
    const result = Object.fromEntries(['artist', 'title', 'version', 'artwork'].map((key) => [key, field(data, config.fields[key])]));
    if (!result.title && !result.artist) {
      throw new Error('The metadata API did not provide a mapped artist or title.');
    }
    return result;
  }
  if (!data.icestats || typeof data.icestats !== 'object') throw new Error('Invalid Icecast status response.');
  const sources = Array.isArray(data.icestats.source) ? data.icestats.source : [data.icestats.source];
  const stream = new URL(config.src, base);
  const mount = sources.find((source) => {
    if (!source || typeof source.listenurl !== 'string') return false;
    return new URL(source.listenurl, stream).pathname === stream.pathname;
  });
  if (!mount) throw new Error('The requested stream mount was not found in Icecast status.');
  let artist = text(mount.artist);
  let title = text(mount.title);
  if (!artist && title.includes(' - ')) {
    const separator = title.indexOf(' - ');
    artist = title.slice(0, separator).trim();
    title = title.slice(separator + 3).trim();
  }
  return { artist, title, version: '', artwork: '' };
}

export function artworkUrl(value, endpoint) {
  if (!value) return '';
  const url = new URL(value, endpoint);
  if (!['https:', 'http:'].includes(url.protocol) || url.username || url.password) {
    throw new Error('Invalid artwork URL.');
  }
  return url.href;
}
