const path = require('path');

module.exports = {
	publicPath: './',
	lintOnSave: false,

	chainWebpack: config => {
    config.plugin('copy')
    .tap(args => {
      args[0].push({
        from: path.resolve(__dirname, 'php'),
        to: path.resolve(__dirname, 'dist/php'),
        toType: 'dir',
        ignore: ['.DS_Store']
      })
      return args
    })
  }
};