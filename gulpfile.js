/*globals require, console*/
(function () {
    'use strict';

    require('es6-promise');

    var gulp = require('gulp'),
        gutil = require('gulp-util'),
        babel = require('gulp-babel'),
        sass = require('gulp-sass'),
        cleanCSS = require('gulp-clean-css'),
        sourcemaps = require('gulp-sourcemaps'),
        plumber = require('gulp-plumber'),
        autoprefixer = require('gulp-autoprefixer'),
        uglify = require('gulp-uglify'),
        concat = require('gulp-concat'),
        rename = require('gulp-rename'),
        browserSync = require('browser-sync'),
        connect = require('gulp-connect-php'),

        // variables
        staticSrcDir = 'resources/assets/',
        targetDistDir = 'public/',

        JS_SRC = [
            // libs and es5 stuff
            'node_modules/babel-polyfill/dist/polyfill.js'
        ],

        ES6_SRC = [
            staticSrcDir + 'js/app.js'
        ];

    // Compile Less
    // and save to target CSS directory
    gulp.task('build:sass:min', ['build:sass'], function () {
        return gulp.src(staticSrcDir + 'scss/app.scss')
            .pipe(plumber({
                errorHandler: function (err) {
                    console.log(err);
                    this.emit('end');
                }
            }))
            .pipe(sass({outputStyle: 'compressed'})
                .on('error', gutil.log))
            .pipe(autoprefixer({
                browsers: ['last 2 versions'],
                cascade: false
            }))
            .pipe(sourcemaps.init())
            .pipe(cleanCSS())
            .pipe(rename({
                extname: '.min.css'
            }))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(targetDistDir + 'css/'));
            // .pipe(browserSync.stream());
    });

    gulp.task('build:sass', function () {
        return gulp.src(staticSrcDir + '/scss/app.scss')
            .pipe(plumber({
                errorHandler: function (err) {
                    console.log(err);
                    this.emit('end');
                }
            }))
            .pipe(sass().on('error', gutil.log))
            .pipe(autoprefixer({
                browsers: ['last 2 versions'],
                cascade: false
            }))
            .pipe(gulp.dest(targetDistDir + 'css/'));
    });

    gulp.task('build:js', function () {
        return gulp.src(JS_SRC)
            .pipe(sourcemaps.init())
            .pipe(uglify())
            // .pipe(concat('libs.js'))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(targetDistDir + 'js/'));
            // .pipe(browserSync.reload({
            //     stream: true
            // }));
    });

    gulp.task('build:es6', function () {
        return gulp.src(ES6_SRC)
            .pipe(sourcemaps.init())
            .pipe(babel({
                presets: ['babel-preset-es2015']
            }))
            .pipe(uglify())
            .pipe(concat('app.js'))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(targetDistDir + 'js/'));
            // .pipe(browserSync.reload({
            //     stream: true
            // }));
    });

    gulp.task('build:assets', [
        'build:js',
        'build:es6',
        'build:sass:min'
    ]);

    // Keep an eye on Less
    gulp.task('serve', function () {

        connect.server({
            hostname: '0.0.0.0',
            base: 'public',
            open: true
        }, function () {

        });

        browserSync.init({
            // open: true,
            // proxy: 'im-developer.dev' // local dev ip or vhost
        });

        gulp.watch('*/**/*.scss', ['build:sass:min']);

        gulp.watch('public/**/*.css', function(file) {
            if (file.type === "changed") {
                browserSync.reload(file.path);
            }
        });

        gulp.watch('public/**/*.js', function(file) {
            if (file.type === "changed") {
                browserSync.reload(file.path);
            }
        });
        gulp.watch('*/**/*.js', ['build:es6']);
        gulp.watch(['*/**/**/*.html', '*/**/**/*.twig']).on('change', browserSync.reload);
    });

    // What tasks does running gulp trigger?
    gulp.task('default', ['build:sass:min', 'serve']);
}());