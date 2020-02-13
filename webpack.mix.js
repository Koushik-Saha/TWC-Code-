const path = require('path');
const glob = require('glob-all');

const mix = require('laravel-mix');

let LiveReloadPlugin = require('webpack-livereload-plugin');

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

mix.js('resources/js/app.js', 'public/rmm')
    .sass('resources/js/rmm/sass/rmm.scss', 'public/rmm')
    .extract(['vue', 'jquery', 'popper.js', 'lodash', 'bootstrap'])


    .webpackConfig({
        plugins: [
            new LiveReloadPlugin(),

            //new CleanWebpackPlugin()
        ]
    })
    //.version()
    .browserSync({
        proxy: 'localhost:8000',

    });


