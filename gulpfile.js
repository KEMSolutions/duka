/*******************************
 Set-up
 *******************************/

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    concatCss = require('gulp-concat-css'),
    uglify = require('gulp-uglify'),
    minifyCss = require('gulp-minify-css');

/*******************************
 Tasks
 *******************************/

// Takes all scripts in public/js/dev and creates a minified, uglified production script.
gulp.task('js', function () {
    return gulp.src(
        [
        'public/js/dev/utils/*.js',
        'public/js/dev/analytics/*.js',
        'public/js/dev/components/**/*.js'
        ])
        .pipe(concat('duka.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('public/js/prod'))
});

// Minify stylesheets
gulp.task('css', function () {
    return gulp.src([
        'public/css/dev/base/*.css',
        'public/css/dev/*.css'])
        .pipe(concat('app.css'))
        .pipe(minifyCss())
        .pipe(gulp.dest('public/css/prod'))
});


    /*******************************
     Semantic UI tasks.
     *******************************/
// Task responsible for minifying semantic css.
gulp.task('semantic-css', function() {
    return gulp.src(['public/semantic/dev/css/*.css'])
        .pipe(concatCss('semantic.css'))
        .pipe(minifyCss())
        .pipe(gulp.dest('public/semantic/prod'))
});


//Gulp watchers
gulp.task("watch", function () {
    gulp.watch('public/js/dev/*.js', ['js']);
    gulp.watch('public/css/dev/*.css', ['css']);
});

// Default task.
gulp.task('semantic', ['semantic-css']);
gulp.task('default', ['js', 'css', 'semantic']);
