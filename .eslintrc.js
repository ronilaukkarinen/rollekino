module.exports = {
  root: true,
  ignorePatterns: ['content/themes/rollekino/js/dist/*.js', 'content/themes/rollekino/node_modules/**/*.js', 'temp.js', 'content/themes/rollekino/js/src/front-end.js', '**/gulp/**/*.js', '**/gulp/*.js', 'gulpfile.js'],
  parser: 'babel-eslint',
  extends: 'eslint-config-airbnb/base',
  rules: {
    indent: ['error', 2],
  },
  env: {
    browser: true,
    jquery: true,
  },
};
