module.exports = {
    root: true,
    env: {
      node: true,
    },
    extends: [
      'plugin:vue/vue3-essential',
      'eslint:recommended',
      '@vue/typescript/recommended'
    ],
    parserOptions: {
      ecmaVersion: 2020,
      parser: '@typescript-eslint/parser'
    },
    rules: {
      // 你可以在這裡自訂規則
    },
  }