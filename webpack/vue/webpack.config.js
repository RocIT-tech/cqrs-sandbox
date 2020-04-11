const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('../../public/build/vue')
  .setPublicPath('/build/vue')
  .createSharedEntry('shared', './assets/js/shared.js')
  .addStyleEntry('main_style', './assets/scss/main.scss')
  .addEntry('main', './assets/js/main.js')
  .addEntry('homepage', './assets/js/homepage/homepage.js')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = 3;
  })
  .enableVueLoader()
  .enableEslintLoader({
    parser: 'vue-eslint-parser',
  })
  .configureLoaderRule('eslint', (loaderRule) => {
    loaderRule.test = /\.(jsx?|vue)/;
  })
  .enableSassLoader(() => {}, {
    resolveUrlLoader: false,
  })
  .enablePostCssLoader();

module.exports = Encore.getWebpackConfig();
