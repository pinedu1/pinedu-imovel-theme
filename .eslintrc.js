module.exports = {
  root: true,
  ignorePatterns: ['**/js/dev/*.js', '**/js/prod/*.js', '**/node_modules/*.js', '**/gulp/**/*.js', '**/gulp/*.js', 'gulpfile.js', '**.scss'],
  parser: '@babel/eslint-parser',
  parserOptions: {
    requireConfigFile: false,
  },
  extends: 'eslint-config-airbnb/base',
  rules: {
    'no-console': 'off',
    indent: ['error', 2],
  },
  env: {
    browser: true,
    jquery: true,
  },
};
