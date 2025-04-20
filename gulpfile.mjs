// ─── Core Modules ────────────────────────────────────────────
import { readFileSync } from 'fs';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

// ─── Gulp & Utilities ────────────────────────────────────────
import gulp from 'gulp';
import gulpif from 'gulp-if';
import { deleteAsync as del } from 'del';

// ─── CLI Args ────────────────────────────────────────────────
import yargs from 'yargs';
import { hideBin } from 'yargs/helpers';

// ─── Styles ──────────────────────────────────────────────────
import * as sass from 'sass';
import gulpSass from 'gulp-sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import purgecss from '@fullhuman/postcss-purgecss';
import sourcemaps from 'gulp-sourcemaps';

// ─── Scripts ─────────────────────────────────────────────────
import webpack from 'webpack-stream';
import named from 'vinyl-named';
import TerserPlugin from 'terser-webpack-plugin';

// ─── Assets ──────────────────────────────────────────────────
import imagemin from 'gulp-imagemin';
import webp from 'gulp-webp';
import zip from 'gulp-zip';
import replace from 'gulp-replace';

// ─── Server ──────────────────────────────────────────────────
import browserSync from 'browser-sync';

// ─── Environment Setup ───────────────────────────────────────
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const argv = yargs(hideBin(process.argv)).argv;
const production = !!argv.prod;
const info = JSON.parse(readFileSync('./package.json'));
const browser = browserSync.create();
const sassCompiler = gulpSass(sass);

// ─── PostCSS Plugins ─────────────────────────────────────────
const postcssPlugins = [
  autoprefixer({ grid: true }),
  production &&
    purgecss({
      content: [
        './**/*.php',
        'src/assets/js/**/*.{js,ts}',
        'src/assets/scss/**/*.scss',
      ],
      safelist: {
        standard: [/^wp-/, /^has-/, /^is-/, /^align/, /^theme-/],
        deep: [/^editor-/, /^block-/],
        greedy: [/^modal/, /^slick/],
      },
      defaultExtractor: content => content.match(/\w+[-/:.]?(?<!:)/g) || [],
    }),
].filter(Boolean);

// ─── Paths ───────────────────────────────────────────────────
const paths = {
  styles: {
    src: [
      'src/assets/scss/bundle.scss',
      'src/assets/scss/admin.scss',
      'src/assets/scss/editor.scss',
    ],
    dest: 'dist/assets/css',
  },
  scripts: {
    src: [
      'src/assets/js/bundle.js',
      'src/assets/js/admin.js',
      'src/assets/js/customize-preview.js',
      'src/assets/js/service-worker.js', // Add service worker to build
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
    src: [
      'src/assets/**/*',
      '!src/assets/{images,js,scss}',
      '!src/assets/{images,js,scss}/**/*',
    ],
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

// ─── Tasks ───────────────────────────────────────────────────

// Styles
export const styles = () =>
  gulp
    .src(paths.styles.src, { sourcemaps: !production })
    .pipe(
      sassCompiler({
        outputStyle: production ? 'compressed' : 'expanded',
        includePaths: ['node_modules'],
      }).on('error', function (err) {
        sassCompiler.logError.call(this, err);
        this.emit('end');
        browser.notify('Sass Compilation Error');
      })
    )
    .pipe(postcss(postcssPlugins))
    .pipe(gulpif(!production, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browser.stream({ match: '**/*.css' }));

// Scripts
export const scripts = () =>
  gulp
    .src(paths.scripts.src)
    .pipe(named())
    .pipe(
		webpack({
		  mode: production ? 'production' : 'development',
		  devtool: production ? false : 'eval-source-map',
		  cache: {
			type: 'filesystem',
			buildDependencies: { config: [__filename] },
		  },
		  optimization: {
			moduleIds: 'deterministic',
			runtimeChunk: 'single',
			splitChunks: {
			  cacheGroups: {
				vendor: {
				  test: /[\\/]node_modules[\\/]/,
				  name: 'vendors',
				  chunks: 'all',
				},
			  },
			},
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
				test: /\.[jt]sx?$/,
				exclude: /node_modules/,
				use: {
				  loader: 'babel-loader',
				  options: {
					presets: [
					  [
						'@babel/preset-env',
						{
						  useBuiltIns: 'usage',
						  corejs: 3,
						  targets: '> 0.25%, not dead',
						},
					  ],
					  '@babel/preset-typescript',
					],
					plugins: ['@babel/plugin-transform-runtime'],
				  },
				},
			  },
			  {
				test: /\.s[ac]ss$/i,
				use: ['style-loader', 'css-loader', 'sass-loader'],
			  },
			  {
				test: /\.css$/i,
				use: ['style-loader', 'css-loader'],
			  },
			  {
				test: /\.(png|jpe?g|gif|svg|webp)$/i,
				type: 'asset/resource',
			  },
			  {
				test: /\.(woff2?|eot|ttf|otf)$/i,
				type: 'asset/resource',
			  },
			],
		  },
		  resolve: {
			extensions: ['.ts', '.tsx', '.js', '.jsx'],
		  },
		})
	  )	  
    .pipe(gulp.dest(paths.scripts.dest));

// Images
export const images = gulp.parallel(
  function optimizeImages() {
    return gulp
      .src(paths.images.src)
      .pipe(
        gulpif(
          production,
          imagemin([
            imagemin.mozjpeg({ quality: 80, progressive: true }),
            imagemin.optipng({ optimizationLevel: 5 }),
            imagemin.svgo({ plugins: [{ removeViewBox: false }, { cleanupIDs: false }] }),
          ])
        )
      )
      .pipe(gulp.dest(paths.images.dest));
  },
  function convertWebP() {
    return gulp
      .src(paths.images.src)
      .pipe(webp({ quality: 80 }))
      .pipe(gulp.dest(paths.images.dest));
  }
);

// Copy
export const copy = () => gulp.src(paths.other.src).pipe(gulp.dest(paths.other.dest));
export const copyFonts = () => gulp.src(paths.fonts.src).pipe(gulp.dest(paths.fonts.dest));
export const clean = () => del(['dist']);

// Watch
export const watch = () => {
  gulp.watch('src/assets/scss/**/*.scss', styles);
  gulp.watch('src/assets/js/**/*.js', gulp.series(scripts));
  gulp.watch('**/*.php');
  gulp.watch(paths.images.src, gulp.series(images));
  gulp.watch(paths.other.src, gulp.series(copy));
};

// Server
export const serve = (done) => {
  browser.init({
    proxy: 'https://profile.test/',
    https: {
      key: '/etc/ssl/localhost/localhost.key',
      cert: '/etc/ssl/localhost/localhost.crt',
    },
    notify: false,
  });
  done();
};

// Build & Dev
export const build = gulp.series(clean, gulp.parallel(styles, scripts, images, copy, copyFonts));
export const dev = gulp.series(build, serve, watch);

// Bundle Theme
export const bundle = gulp.series(build, () =>
  gulp
    .src(paths.package.src)
    .pipe(replace('_themename', info.name))
    .pipe(zip(`${info.name}.zip`))
    .pipe(gulp.dest(paths.package.dest))
);


export default dev;
