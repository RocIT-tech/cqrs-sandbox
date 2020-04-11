module.exports = {
  extends: ['airbnb', 'plugin:react/recommended'],
  parserOptions: {
    ecmaVersion: 6,
    sourceType: 'module',
    ecmaFeatures: {
      jsx: true
    }
  },
  env: {
    browser: true,
    es6: true,
    node: true
  },
  rules: {
    'yoda': ['error', 'always', { onlyEquality: true }],
    'jsx-a11y/label-has-associated-control': [2, { 'assert': 'htmlFor' }],
  },
};
