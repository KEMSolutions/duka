/*******************************
 Set-up
 *******************************/

var gulp = require('gulp'),
    elixir = require('laravel-elixir');


elixir(function(mix) {
    // Compile less.
    mix.less('duka.less', "public/css/prod/duka.css");

    // Concatenate scripts.
    mix.scripts([
        './public/js/dev/utils/*.js',
        './public/js/dev/components/**/*.js',
        './public/js/dev/init.js'
    ], "public/js/prod/duka.js");
});