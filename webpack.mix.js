const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fonts')
    .copy('node_modules/formBuilder/dist', 'public/js/formBuilder')
    .copy('node_modules/pristinejs/dist', 'public/js/pristinejs')
    .copy('node_modules/jquery/dist', 'public/js/jquery')
    .copy('node_modules/alertifyjs/build', 'public/js/alertifyjs');
/**
 * https://github.com/JeffreyWay/laravel-mix/issues/1292#issuecomment-577092394
 */
mix.setResourceRoot(process.env.MIX_RESOURCE_ROOT);