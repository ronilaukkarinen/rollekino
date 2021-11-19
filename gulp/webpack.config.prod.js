const TerserPlugin = require('terser-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
  optimization: {
    minimize: true,
    minimizer: [
      (compiler) => {
        const TerserPlugin = require('terser-webpack-plugin');
        new TerserPlugin({
        terserOptions: {
          // ecma: 6,
          parse: {},
          compress: {},
          mangle: true, // Note `mangle.properties` is `false` by default.
          module: false,
          toplevel: false,
          nameCache: null,
          ie8: false,
          keep_fnames: false,
          safari10: false,
          format: {
            comments: false,
          },
        },
        extractComments: false,
      }).apply(compiler);
    },
  ]
  },
  externals: {
    jquery: 'jQuery' // Available and loaded through WordPress.
  },
  mode: 'production',
  module: {
    rules: [{
      test: /.js$/,
      exclude: /node_modules/,
      use: [{
        loader: 'babel-loader',
        options: {
          presets: [
            ['airbnb', {
              targets: {
                chrome: 50,
                ie: 11,
                firefox: 45
              }
            }]
          ]
        }
      }]
    },
    {
      test: /\.vue$/,
      loader: 'vue-loader'
    },
  ]
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
    }
  },
  plugins: [
    // make sure to include the plugin!
    new VueLoaderPlugin()
  ],
};
