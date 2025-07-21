const prefixer = require("postcss-prefix-selector");
const path = module.require("node:path");
const scopeConfig = require(__dirname + "/scopecss.config.json");

const globalCssFiles = scopeConfig.globalCssFiles.map(file => file.endsWith(".css") ? file : `${file}.css`);
const scopedCssFiles = scopeConfig.scopedCssFiles.map(file => file.endsWith(".css") ? file : `${file}.css`);

Object.freeze(globalCssFiles);
Object.freeze(scopedCssFiles);

module.exports = {
    plugins: [
        prefixer({
            prefix: scopeConfig.prefixRoot,
            exclude: [":root", /html/, /body/, /data-bs-theme/, /.swal2/],
            transform: function(prefix, selector, prefixedSelector, file) {
                const fileName = path.basename(file, ".css");
                const classPrefix = `.sc${fileName.slice(0, 1).toUpperCase()}${fileName.slice(1)}`;

                if(globalCssFiles.includes(`${fileName}.css`)) return `${classPrefix} ${selector}`;
                else if(scopedCssFiles.includes(`${fileName}.css`)) return `${classPrefix}${selector}`;

                return prefixedSelector;
            }
        }),
    ],
};
