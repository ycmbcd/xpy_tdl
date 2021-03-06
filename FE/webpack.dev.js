const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');   // 打包 html

module.exports = merge(common, {
  mode: "development",
  devtool: 'cheap-module-eval-source-map',
  devServer: {
    open: true,
    hot: true,
    inline: true,
    quiet: false,
    proxy: {
      '/api': {
        target: 'http://localhost:888',
        changeOrigin: true,
        // pathRewrite: { '^/api': '/api' }
      }
    },
  },
  module: {
    rules: [
      {
        test: /\.(png|jpg|gif)$/,
        use: {
          loader: 'url-loader',
          options: {
            esModule: false,
            limit: 1024 * 10,
            name: './static/images/[name]_[hash:6].[ext]',
          }
        }
      },
      {
        test: /\.(eot|svg|ttf|woff|woff2)(\?\S*)?$/,
        loader: 'url-loader',
        options: {
          esModule: false,
          name: './static/fonts/[name]_[hash:6].[ext]',
        }
      },
      {
        test: /\.styl(us)?$/,
        loader:'style-loader!css-loader!stylus-loader'
      },
      {
        test: /\.css$/,
        use: [
          'style-loader',
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              plugins: () => [
                require('autoprefixer')({
                  overrideBrowserslist: ['last 2 version', '>1%', 'ios 7']
                })
              ]
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NamedModulesPlugin(), // HMR模式，在console中显示正确的名字
    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: 'index.html',
      favicon: 'favicon.ico',
    })
  ]
})
