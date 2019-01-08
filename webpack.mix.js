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

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');


mix.styles([
    'resources/layout/css/style.css',
    'resources/layout/css/font-awesome.min.css',
    'resources/layout/css/simple-line-icons.min.css',
], 'public/css/app.css')
mix.scripts([
    'resources/layout/js/jquery.min.js',
    'resources/layout/js/popper.min.js',
    // 'resources/layout/js/bootstrap.min.js',
    'resources/layout/js/Chart.min.js',
    'resources/layout/js/pace.min.js',
    'resources/layout/js/template.js',
    'resources/layout/js/sweetalert2.all.min.js'
], 'public/js/app.js')
mix.js('resources/js/app.js', 'public/js/vue.js');