// Dependencies
const { series } = require('gulp');

// Importa as tasks diretamente (FUNÇÕES)
const { prodstyles } = require('./prodstyles.js');
const { js } = require('./js.js');
const images = require('./export-images.js');
const { phpcs } = require('./phpcs.js');

/**
 * Build de produção
 * Executa uma única vez e gera os assets finais
 */
exports.build = series(
  prodstyles, // CSS final (produção)
  js,         // JavaScript final
  images,     // Imagens
);
