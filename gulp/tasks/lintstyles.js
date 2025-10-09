// Dependencies
const {
  src
} = require('gulp');
const stylelint = require('@ronilaukkarinen/gulp-stylelint');
const config = require('../config.js');

// Task
function lintstyles() {
  return src('sass/**/*.scss')
    .pipe(stylelint({
      failAfterError: false,
      reporters: [
        {
          formatter(results) {
            const errorsOnly = results
              .map(result => {
                const warnings = result.warnings.filter(w => w.severity === 'error');
                return Object.assign({}, result, { warnings });
              })
              .filter(result => result.warnings.length > 0);

            if (errorsOnly.length === 0) return '';
            return require('stylelint-formatter-string')(errorsOnly);
          },
          console: true
        }
      ]
    }));
}

exports.lintstyles = lintstyles;
