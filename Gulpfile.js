"use strict";

const gulp = require('gulp');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

const dest = './js/';

const files = [
    'node_modules/jquery-form/dist/jquery.form.min.js',
    'node_modules/jquery.scrollto/jquery.scrollTo.js',
    'node_modules/foundation-sites/dist/js/foundation.min.js',
    'js/src/scripts.js',
];

/*
* Grab necessary files and concat
*/
gulp.task('scripts', function () {
    return gulp.src(files)
        .pipe(concat('app.min.js'))
        .pipe(babel({
            presets: [
                [
                    "@babel/env",
                    {
                        "targets": "> 0.25%, not dead",
                    }
                ]
            ]
        }))
        .pipe(uglify())
        .pipe(gulp.dest(dest));
});