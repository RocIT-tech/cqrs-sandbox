const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('../../public/build/react')
  .setPublicPath('/build/react')
  .createSharedEntry('shared', './assets/js/shared.js')
  .addStyleEntry('main_style', './assets/scss/main.scss')
  .addEntry('main', './assets/js/main.js')
  .addEntry('homepage', './assets/js/homepage/homepage.jsx')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = 3;
  })
  .enableReactPreset()
  .enableEslintLoader()
  .enableSassLoader(() => {}, {
    resolveUrlLoader: false,
  })
  .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
