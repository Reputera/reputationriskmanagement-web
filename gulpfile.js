var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix
        .copy('node_modules/bootstrap/fonts', 'public/build/assets/fonts')
        .copy('bower_components/jquery-ui/themes/base/images', 'public/build/css/images')
        .sass([
            'resources/assets/sass/app.scss'
        ], 'public/css')
        .scripts([
            'bower_components/jquery/dist/jquery.min.js',
            'bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js',
            'node_modules/moment/min/moment.min.js',
            'bower_components/jquery-ui/jquery-ui.min.js'
        ], 'public/js/jquery.js', './')
        .scripts([
            'bower_components/admin-lte.scss/javascripts/app.js'
        ],'public/js/adminlte.js', './')
        .styles([
            'bower_components/jquery-ui/themes/base/jquery-ui.min.css',
            'bower_components/jquery-ui/themes/base/all.css'
        ], 'public/css', './')
        .copy('bower_components/bootstrap-sass-official/assets/fonts', 'public/build/fonts')
        .version([
            'public/css/app.css',
            'public/css/all.css',
            'public/js/jquery.js',
            'public/js/adminlte.js'
        ])
});
