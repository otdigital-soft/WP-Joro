const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync').create();
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const cssnano = require('cssnano');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const order = require('gulp-order');
const sourcemaps = require('gulp-sourcemaps');

// Development Tasks
// -----------------

// Start browserSync server
gulp.task('browserSync', () => {
  browserSync.init({
    server: {
      baseDir: '.'
    },
    notify: false
  });

  gulp.watch('./assets/scss/**/**/*.scss', gulp.parallel('sass'));
  gulp.watch('./*.html').on('change', browserSync.reload);
  gulp.watch('./assets/js/**/main.js', gulp.parallel('scripts'));
  gulp.watch('./assets/js/vendor/*.js', gulp.parallel('libs'));
});

gulp.task('sass', () => {
  return gulp
    .src('./assets/scss/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(rename('style.min.css'))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./assets/css'))
    .pipe(browserSync.stream());
});

gulp.task('scripts', () => {
  return gulp
    .src('./assets/js/main.js')
    .pipe(rename('main.min.js'))
    .pipe(babel({ presets: ['@babel/env'] }))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/js/'))
    .pipe(browserSync.stream());
});

gulp.task('libs', () => {
  return gulp
    .src(['./assets/js/vendor/*.js', '!./assets/js/vendor/jquery-3.3.1.min.js'])
    .pipe(order(['jcf.js', '*.js']))
    .pipe(concat('libs.min.js'))
    .pipe(gulp.dest('./assets/js/'))
    .pipe(browserSync.stream());
});

// Watchers
gulp.task('watch', gulp.series('sass', 'libs', 'scripts', 'browserSync'));
