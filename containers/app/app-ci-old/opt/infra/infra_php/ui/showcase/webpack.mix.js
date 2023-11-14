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

mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery'],
})
    .js('resources/js/app.js', 'public/js')
    .js('resources/renderers/bootstrap4/js/main.js', 'public/js/bootstrap4-app.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/renderers/bootstrap4/sass/main.scss', 'public/css/bootstrap4-app.css')
    .sass('resources/renderers/infra/sass/main.scss', 'public/css/infra-app.css');
