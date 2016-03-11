/*******************************
 Set-up
 *******************************/

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    elixir = require('laravel-elixir');

/*******************************
 Tasks
 *******************************/

// Takes all scripts in public/js/dev and creates a minified, uglified production script.
gulp.task('js', function () {
    return gulp.src(
        [
            'public/js/dev/utils/*.js',
            'public/js/dev/components/**/*.js',
            'public/js/dev/init.js'
        ])
        .pipe(concat('duka.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('public/js/prod'))
});


// Less compiler
elixir(function(mix) {
    mix.less('duka.less', "public/css/prod/duka.css");
});