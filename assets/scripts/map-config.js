export function mapStyle(config) {
  if (config.provider === 'openfreemap') return `https://tiles.openfreemap.org/styles/${config.style}`;
  const osm = config.provider === 'openstreetmap';
  return {
    version: 8,
    sources: {
      basemap: osm ? {
        type: 'raster',
        tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
        tileSize: 256, maxzoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a>',
      } : {
        type: 'raster',
        url: `https://api.mapy.com/v1/maptiles/basic/tiles.json?apikey=${encodeURIComponent(config.apiKey)}`,
        tileSize: 256,
        attribution: '&copy; <a href="https://mapy.com/" target="_blank" rel="noopener">Seznam.cz a.s. a další</a>',
      },
    },
    layers: [{ id: 'basemap', type: 'raster', source: 'basemap' }],
  };
}
