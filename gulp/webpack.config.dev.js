const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
  externals: {
    jquery: 'jQuery' // Available and loaded through WordPress.
  },
  mode: 'development',
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
