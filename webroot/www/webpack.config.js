const MiniCssExtraPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: ['./src/index.js', './src/styles.scss'],
    output: {
        filename: "main-configured.js"
    },
    plugins: [
        new MiniCssExtraPlugin({
            filename: "main-configured.css"
        }),
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtraPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            }
        ]
    }
};