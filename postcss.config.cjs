const prefixer = require('postcss-prefix-selector');

module.exports = {
  plugins: [
    prefixer({
      prefix: '.scRender',
      transform(prefix, selector, prefixedSelector) {
        if (
            selector.includes('[data-bs-theme') ||
            selector.startsWith('html') ||
            selector.startsWith('body') ||
            selector.includes(':root')
        ) {
            return selector;
        }
        return prefixedSelector;
      },
    }),
  ],
};
