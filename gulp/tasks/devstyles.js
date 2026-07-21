// Dependencies
const {
  dest,
  src
} = require('gulp');
const bs = require('browser-sync').create();
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass')( require('sass') );
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const calcFunction = require('postcss-calc');
const colormin = require('postcss-colormin');
const discardEmpty = require('postcss-discard-empty');
const mergeLonghand = require('postcss-merge-longhand');
const mergeAdjacentRules = require('postcss-merge-rules');
const minifyGradients = require('postcss-minify-gradients');
const normalizePositions = require('postcss-normalize-positions');
const normalizeUrl = require('postcss-normalize-url');
const uniqueSelectors = require('postcss-unique-selectors');
const zIndex = require('postcss-zindex');
const config = require('../config.js');

// Plugins adicionais para manipular o arquivo final
const rename = require('gulp-rename');
const header = require('gulp-header'); // <- Plugin novo aqui!

// Cabeçalho obrigatório do WordPress
const wpHeader = `/*
Theme Name: Pinedu Imovel Theme
Description: Tema base para imóveis
Author: Eduardo Pinheiro da Silva
Version: 1.0.0
Text Domain: pinedu-imovel-theme
*/
`;

function devstyles() {
  return src(config.styles.src)
    .pipe(bs.stream())
    .pipe(sourcemaps.init())
    .pipe(sass.sync(config.styles.opts.development))
    .pipe(postcss([
      autoprefixer(),
      colormin(),
      calcFunction(),
      discardEmpty(),
      mergeLonghand(),
      mergeAdjacentRules(),
      minifyGradients(),
      normalizePositions(),
      normalizeUrl(),
      zIndex(),
      uniqueSelectors()
    ]))
    .pipe(sourcemaps.write())

    // 1. Renomeia para style.css
    .pipe(rename('style.css'))

    // 2. Injeta o cabeçalho no topo do arquivo (escapando do minificador)
    .pipe(header(wpHeader))

    // 3. Salva na raiz do tema
    .pipe(dest('./'));
}

exports.devstyles = devstyles;
