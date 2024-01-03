const { src, dest, lastRun, watch } = require('gulp');
const streamqueue = require('streamqueue');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const livereload = require('gulp-livereload');
const postcss = require('gulp-postcss');
const cssnano = require('cssnano');
const autoprefixer = require('autoprefixer');
const objectFit = require('postcss-object-fit-images');
const rename = require('gulp-rename');

const postCSSPlugins = [autoprefixer(), cssnano(), objectFit];

exports.copyNormalize = function copyNormalize(prod = false) {
  const source = src('node_modules/normalize.css/normalize.css');
  return prod ? source.pipe(postcss(postCSSPlugins)).pipe(concat('normalize.min.css')).pipe(dest('dist/')) : source.pipe(dest('dist/'));
};

const styleScript = (srcFile, destFile, dev = false) =>
  dev
    ? src(srcFile)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(dest(destFile))
        .pipe(livereload())
    : src(srcFile)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss(postCSSPlugins))
        .pipe(rename({ extname: '.min.css' }))
        .pipe(sourcemaps.write())
        .pipe(dest(destFile));

exports.styleScript = styleScript;

exports.devThemeStyles = async function devThemeStyles() {
  await styleScript('src/styles/styles.scss', 'dist/', true);
  await styleScript('src/styles/backend-styles.scss', 'dist/', true);
};

exports.prodThemeStyles = async function prodThemeStyles() {
  await styleScript('src/styles/styles.scss', 'dist/');
  await styleScript('src/styles/backend-styles.scss', 'dist/');
};
