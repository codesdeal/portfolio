// ─── Node Core ───────────────────────────────────────────────
import { readFileSync } from 'fs';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

// ─── Gulp & Utilities ────────────────────────────────────────
import gulp from 'gulp';
import gulpif from 'gulp-if';
import { deleteAsync as del } from 'del';

// ─── CLI Flags ───────────────────────────────────────────────
import yargs from 'yargs';
import { hideBin } from 'yargs/helpers';

// ─── CSS Processing ──────────────────────────────────────────
import sass from 'gulp-dart-sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import purgecss from '@fullhuman/postcss-purgecss';
import sourcemaps from 'gulp-sourcemaps';

// ─── JavaScript Processing ───────────────────────────────────
import webpack from 'webpack-stream';
import named from 'vinyl-named';
import TerserPlugin from 'terser-webpack-plugin';

// ─── Asset Management ────────────────────────────────────────
import imagemin from 'gulp-imagemin';
import webp from 'gulp-webp';
import zip from 'gulp-zip';
import replace from 'gulp-replace';

// ─── Live Reload ─────────────────────────────────────────────
import browserSync from 'browser-sync';

// ─── Environment & Metadata ──────────────────────────────────
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const argv = yargs(hideBin(process.argv)).argv;
const production = !!argv.prod;
const pkg = JSON.parse(readFileSync('./package.json'));
const browser = browserSync.create();

// ─── PostCSS Plugins ─────────────────────────────────────────
const postcssPlugins = [
  autoprefixer({ grid: true }),
  production &&
    purgecss({
      content: ['./**/*.php', 'src/assets/js/**/*.js', 'src/assets/scss/**/*.scss'],
      safelist: {
        standard: [/^wp-/, /^has-/, /^is-/, /^align/, /^theme-/, /^swiper-/],
        deep: [/^editor-/, /^block-/],
        greedy: [/^modal/, /^aos/],
      },
      defaultExtractor: content => content.match(/[^\s"'<>]+/g) || [],
    }),
].filter(Boolean);

// ─── File Paths ──────────────────────────────────────────────
const paths = {
  styles: {
    src: ['src/assets/scss/bundle.scss', 'src/assets/scss/admin.scss', 'src/assets/scss/editor.scss'],
    dest: 'dist/assets/css',
  },
  scripts: {
    src: [
      'src/assets/js/bundle.js',
      'src/assets/js/admin.js',
      'src/assets/js/customize-preview.js',
      'src/assets/js/service-worker.js',
    ],
    dest: 'dist/assets/js',
  },
  images: {
    src: 'src/assets/images/**/*.{jpg,jpeg,png,svg,gif}',
    dest: 'dist/assets/images',
  },
  fonts: {
    src: 'node_modules/@fortawesome/fontawesome-free/webfonts/*',
    dest: 'dist/assets/webfonts',
  },
  other: {
    src: ['src/assets/**/*', '!src/assets/{images,js,scss}', '!src/assets/{images,js,scss}/**/*'],
    dest: 'dist/assets',
  },
  package: {
    src: [
      '**/*',
      '!.vscode',
      '!node_modules{,/**}',
      '!packaged{,/**}',
      '!src{,/**}',
      '!.babelrc',
      '!.gitignore',
      '!gulpfile.*',
      '!package*.json',
      '!composer.*',
    ],
    dest: 'packaged',
  },
};

// ─── Gulp Tasks ──────────────────────────────────────────────

// Styles Task
export const styles = () =>
  gulp
    .src(paths.styles.src)
    .pipe(gulpif(!production, sourcemaps.init()))
    .pipe(
      sass({
        outputStyle: production ? 'compressed' : 'expanded',
        includePaths: ['node_modules'],
      }).on('error', function (err) {
        console.error('Sass Error:', err.message);
        this.emit('end');
        browser.notify('Sass Compilation Error');
      })
    )
    .pipe(postcss(postcssPlugins))
    .pipe(gulpif(!production, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browser.stream({ match: '**/*.css' }));

// Scripts Task
export const scripts = () =>
  gulp
    .src(paths.scripts.src)
    .pipe(named())
    .pipe(
      webpack({
        mode: production ? 'production' : 'development',
        devtool: production ? false : 'source-map',
        cache: {
          type: 'filesystem',
          buildDependencies: { config: [__filename] },
        },
        optimization: {
          minimize: production,
          minimizer: [
            new TerserPlugin({
              terserOptions: {
                format: { comments: false },
                compress: {
                  drop_console: production,
                  drop_debugger: production,
                },
              },
              extractComments: false,
            }),
          ],
        },
        module: {
          rules: [
            {
              test: /\.m?js$/,
              exclude: /node_modules/,
              use: {
                loader: 'babel-loader',
                options: {
                  presets: [['@babel/preset-env', { useBuiltIns: 'usage', corejs: 3, targets: '> 0.25%, not dead' }]],
                },
              },
            },
            {
              test: /\.css$/i,
              use: ['style-loader', 'css-loader'],
            },
          ],
        },                
      })
    )
    .pipe(gulp.dest(paths.scripts.dest));

// Images Task
function optimizeImages() {
  const stream = gulp.src(paths.images.src);
  return production
    ? stream
        .pipe(
          imagemin([
            imagemin.mozjpeg({ quality: 80, progressive: true }),
            imagemin.optipng({ optimizationLevel: 5 }),
            imagemin.svgo({ plugins: [{ removeViewBox: false }, { cleanupIDs: false }] }),
          ])
        )
        .pipe(gulp.dest(paths.images.dest))
    : stream.pipe(gulp.dest(paths.images.dest));
}

// WebP Conversion
function convertWebP() {
  return gulp.src(paths.images.src).pipe(webp({ quality: 80 })).pipe(gulp.dest(paths.images.dest));
}

export const images = gulp.parallel(optimizeImages, convertWebP);

// Copy other assets
export const copy = () => gulp.src(paths.other.src).pipe(gulp.dest(paths.other.dest));
export const copyFonts = () => gulp.src(paths.fonts.src).pipe(gulp.dest(paths.fonts.dest));

// Clean output directory
export const clean = () => del(['dist']);

// Watch files for changes
export const watch = () => {
  gulp.watch('src/assets/scss/**/*.scss', styles);
  gulp.watch('src/assets/js/**/*.js', gulp.series(scripts));
  gulp.watch('**/*.php').on('change', browser.reload);
  gulp.watch(paths.images.src, gulp.series(images));
  gulp.watch(paths.other.src, gulp.series(copy));
};

// Local dev server with BrowserSync
export const serve = done => {
  browser.init({
    proxy: 'https://profile.test/',
    https: {
      key: '/etc/ssl/localhost/localhost.key',
      cert: '/etc/ssl/localhost/localhost.crt',
    },
    notify: false,
    open: true,
  });
  done();
};

// Full build pipeline
export const build = gulp.series(
  clean,
  gulp.parallel(styles, scripts, images, copy, copyFonts)
);

// Development task
export const dev = gulp.series(build, serve, watch);

// Package ZIP task
export const bundle = gulp.series(build, () =>
  gulp
    .src(paths.package.src)
    .pipe(replace('_themename', pkg.name))
    .pipe(zip(`${pkg.name}.zip`))
    .pipe(gulp.dest(paths.package.dest))
);

// Default task
export default dev;
