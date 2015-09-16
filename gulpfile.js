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
    return gulp.src(['public/js/dev/utils/*.js', 'public/js/dev/components/**/*.js' ])
        .pipe(concat('boukem2.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/js/prod'))
});

// Minify app.css
gulp.task('css', function () {
    return gulp.src("public/css/app.css")
        .pipe(minifyCss())
        .pipe(gulp.dest('public/css/prod'))
});


    /*******************************
     Semantic UI tasks.
     *******************************/
// Task responsible for minifying semantic css and js.
gulp.task('semantic-css', function() {
    return gulp.src(['public/semantic/dev/css/*.css'])
        .pipe(concatCss('semantic.css'))
        .pipe(minifyCss())
        .pipe(gulp.dest('public/semantic/prod'))
});

gulp.task('semantic-js', function () {
    return gulp.src(['public/semantic/dev/js/*.js'])
        .pipe(concat('semantic.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('public/semantic/prod'))
});


// Default task.
gulp.task('semantic', ['semantic-css', 'semantic-js']);
gulp.task('default', ['js', 'css', 'semantic']);
