let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'dist/');
mix.postCss('resources/css/app.css', 'dist/', [
    require('tailwindcss'),
])