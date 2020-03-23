const mix = require('laravel-mix');

mix.options({
    // set uglify to false in order to prevent production minification
    // it prevents mix from pushing UglifyJSPlugin into the webpack config
    uglify: false
});

mix.webpackConfig(webpack => {
    const MomentLocalesPlugin = require('moment-locales-webpack-plugin');

    return {
        plugins: [
            // To strip all locales except “en”
            new MomentLocalesPlugin(),

            // Or: To strip all locales except “en”, “es-us” and “ru”
            // (“en” is built into Moment and can’t be removed)
            // new MomentLocalesPlugin({
            //     localesToKeep: ['es-us', 'ru'],
            // }),
        ],
    };
});

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

mix.js('./resources/js/app.js', './public/llmix/app.js');

mix.sass('./resources/sass/app.scss', './public/llmix/app.css');
// mix.sass('./resources/sass/bootstrap.scss', './public/llmix/bootstrap.css');
// mix.sass('./resources/sass/fontawesome.scss', './public/llmix/fontawesome.css');

// mix.extract();

mix.copyDirectory('./resources/svg-icons','./public/llmix/svg-icons');
mix.copyDirectory('./node_modules/bytesize-icons/dist/icons','./public/llmix/svg-icons/bs');
mix.copyDirectory('./node_modules/flag-icon-css/flags/4x3','./public/llmix/svg-icons/ctrf');


mix.copyDirectory('./node_modules/moment/locale','./public/llmix/mm-locales');
mix.copy('./node_modules/moment/min/locales.min.js','./public/llmix/mm-locales/all.min.js');


if (mix.inProduction()) {
    mix.version();
}
else {
    mix.sourceMaps();
}

// mix.disableNotifications();
mix.disableSuccessNotifications();
