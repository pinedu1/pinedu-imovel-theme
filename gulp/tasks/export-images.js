const { src, dest } = require('gulp');

// Task para copiar imagens de /assets/images/ para /dist/images/
function images() {
  return src('assets/images/**/*')
    .pipe(dest('dist/images/'));
}

module.exports = images;
