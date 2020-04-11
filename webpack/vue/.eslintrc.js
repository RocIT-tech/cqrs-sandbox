module.exports = {
  extends: ['airbnb-base', 'plugin:vue/recommended'],
  parser: 'vue-eslint-parser',
  parserOptions: {
    parser: 'babel-eslint',
    ecmaVersion: 6,
    sourceType: 'module',
  },
  env: {
    browser: true,
    es6: true,
    node: true
  },
  rules: {
    'yoda': ['error', 'always', { onlyEquality: true }],
    'no-new': 'off',
    'import/extensions': [
      'error',
      'always',
      {
        'js': 'never',
        'jsx': 'never',
        'vue': 'never'
      }
    ],
    'vue/html-indent': [
      'error',
      4
    ],
    'vue/max-attributes-per-line': [
      'error',
      {
        'singleline': 3,
        'multiline': {
          'max': 1,
          'allowFirstLine': true
        }
      }
    ]
  },
  settings: {
    'import/resolver': {
      webpack: {
        config: {
          resolve: {
            extensions: ['.js', '.vue']
          }
        },
      },
    },
  },
  overrides: [
    {
      files: ['*.vue'],
      rules: {
        indent: 'off' // disable `indent` rule for .vue file becauses it conflicts with `vue/script-indent` rule
      }
    }
  ],
};
