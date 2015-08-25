var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify');


gulp.task('js', function () {
    return gulp.src(['public/js/dev/utils/*.js', 'public/js/dev/components/**/*.js', 'public/js/dev/actions/**/*.js' ])
        .pipe(concat('boukem2.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('public/js/prod')) //the destination folder
});