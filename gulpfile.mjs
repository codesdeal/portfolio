import gulp from 'gulp';
import { hideBin } from 'yargs/helpers';
import yargs from 'yargs';
import * as sass from 'sass';
import gulpSass from 'gulp-sass';
import cleanCSS from 'gulp-clean-css';
import gulpif from "gulp-if";
import sourcemaps from 'gulp-sourcemaps';
import imagemin from 'gulp-imagemin';
import { deleteAsync as del } from 'del';
import webpack from 'webpack-stream';
import uglify from 'gulp-uglify';
import named from 'vinyl-named';
import browserSync from 'browser-sync';
import zip from 'gulp-zip';
import replace from 'gulp-replace';
import { readFileSync } from 'fs';
const info = JSON.parse(readFileSync('./package.json'));

const browser = browserSync.create();
const sassCompiler = gulpSass(sass);
const argv = yargs(hideBin(process.argv)).argv;
const production = argv.prod;

const paths = {
  styles: {
    src: ['src/assets/scss/bundle.scss', 'src/assets/scss/admin.scss', 'src/assets/scss/editor.scss'],
    dest: 'dist/assets/css'
  },
  images: {
    src: 'src/assets/images/**/*.{jpg,jpeg,png,svg,gif}',
    dest: 'dist/assets/images'
  },
  fonts: {
    src: ['node_modules/@fortawesome/fontawesome-free/webfonts/*'],
    dest: 'dist/assets/webfonts'
  },
  scripts: {
    src: ['src/assets/js/bundle.js', 'src/assets/js/admin.js', 'src/assets/js/customize-preview.js'],
    dest: 'dist/assets/js'
  },
  plugins: {
    src: ['../../plugins/devabu-metaboxes/packaged/*'],
    dest: ['lib/plugins']
  },
  other: {
    src: ['src/assets/**/*', '!src/assets/{images,js,scss}', '!src/assets/{images,js,scss}/**/*'],
    dest: 'dist/assets'
  },
  package: {
    src: ['**/*', '!.vscode', '!node_modules{,/**}', '!packaged{,/**}', '!src{,/**}', '!.babelrc', '!.gitignore', '!gulpfile.babel.js', '!package-lock.json', '!package.json', '!yarn.lock', '!yarn-error.log', '!.git{,/**}', '!.DS_Store'],
    dest: 'packaged'
  }
};

// Start browser-sync server
export const serve = (done) => {
  browser.init({
    proxy: 'https://profile.test/',
    https: {
      key: "/etc/ssl/localhost/localhost.key",
      cert: "/etc/ssl/localhost/localhost.crt",
    },
    open: true,
    notify: false,
    // https: true
  });
  done();
};

// Reload browser
export const reload = (done) => {
  browser.reload();
  done();
};

// Clean dist folder
export const clean = () => del(['dist']);

// Compile SCSS to CSS
export const styles = () => {
  return gulp.src(paths.styles.src, { allowEmpty: true })
    .pipe(gulpif(!production, sourcemaps.init()))
    .pipe(sassCompiler().on('error', sassCompiler.logError))
    .pipe(gulpif(production, cleanCSS({ compatibility: 'ie8' })))
    .pipe(gulpif(!production, sourcemaps.write()))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browser.stream());
};

// fonts
export const copyFonts = () => {
  return gulp.src(paths.fonts.src)
    .pipe(gulp.dest(paths.fonts.dest));
};

// Optimize images
export const images = () => {
  return gulp.src(paths.images.src)
    .pipe(gulpif(production, imagemin()))
    .pipe(gulp.dest(paths.images.dest));
};

// Copy other assets
export const copy = () => {
  return gulp.src(paths.other.src)
    .pipe(gulp.dest(paths.other.dest));
};

// Copy Plugins
export const copyPlugins = () => {
  return gulp.src(paths.plugins.src)
    .pipe(gulp.dest(paths.plugins.dest));
};

// Compile and bundle JavaScript
export const scripts = () => {
  return gulp.src(paths.scripts.src)
    .pipe(named())
    .pipe(webpack({
      mode: production ? 'production' : 'development',
      devtool: !production ? 'inline-source-map' : false,
      externals: { jquery: 'jQuery' },
      module: {
        rules: [{
          test: /\.js$/,
          use: {
            loader: 'babel-loader',
            options: { presets: ['@babel/preset-env'] }
          }
        }]
      },
      output: { filename: '[name].js' }
    }))
    .pipe(gulpif(production, uglify()))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(browser.stream());
};

// Watch for file changes
export const watch = () => {
  gulp.watch('src/assets/scss/**/*.scss', styles);
  gulp.watch('src/assets/js/**/*.js', gulp.series(scripts, reload));
  gulp.watch('**/*.php', reload);
  gulp.watch(paths.images.src, gulp.series(images, reload));
  gulp.watch(paths.other.src, gulp.series(copy, reload));
};

export const compress = () => {
  return gulp.src(paths.package.src)
    .pipe(gulpif(
      (file) => file.relative.split('.').pop() !== 'zip',
      replace(/devabu/g, info.name)
    ))
    .pipe(zip(`${info.name}.zip`))
    .pipe(gulp.dest(paths.package.dest));
};

// Development Task
export const dev = gulp.series(clean, gulp.parallel(styles, copyFonts, scripts, images, copy), serve, watch);

// Build Task
export const build = gulp.series(clean, gulp.parallel(styles, copyFonts, scripts, images, copy, copyPlugins));

// Bundle Task
export const bundle = gulp.series(build, compress);

export default dev;