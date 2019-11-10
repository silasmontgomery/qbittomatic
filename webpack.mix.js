let mix = require('laravel-mix');
require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'dist/').postCss('resources/css/app.css', 'dist/', [
    require('tailwindcss'),
]).purgeCss();