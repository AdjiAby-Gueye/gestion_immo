var gulp = require('gulp');
var replace = require('gulp-replace');
var rename = require('gulp-rename');
const options = require("./package.json").options;

gulp.task('prod', () => {
    return(
        gulp.src([options.assetDirectory + '/js/angular/controller.js'])
            .pipe(replace(options.links.dev, options.links.prod))
            .pipe(gulp.dest(options.assetDirectory + '/js/angular/'))
    );
});

gulp.task('test', () => {
    return(
        gulp.src([options.assetDirectory + '/js/angular/controller.js'])
            .pipe(replace(options.links.dev, options.links.test))
            .pipe(gulp.dest(options.assetDirectory + '/js/angular/'))
    );
});
