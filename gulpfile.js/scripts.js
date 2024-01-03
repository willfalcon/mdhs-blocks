const { src, dest } = require('gulp');
const named = require('vinyl-named');
const compiler = require('webpack');
const webpack = require('webpack-stream');
const livereload = require('gulp-livereload');

const scriptSrces = [
  'src/scripts/index.js',
  'src/scripts/editor.js',
  'src/scripts/contact-block/contact-block-editor.js',
  'src/scripts/contact-block/contact-block-options-page.js',
  'src/scripts/procurement.js',
];

const jsScript = (sources, dev = false) => {
  const srcArray = sources.constructor === Array ? sources : [sources];
  return src(srcArray)
    .pipe(named())
    .pipe(
      webpack(
        {
          devtool: dev ? 'eval-cheap-module-source-map' : 'source-map',
          mode: dev ? 'development' : 'production',
          output: {
            filename: dev ? '[name].js' : '[name].min.js',
          },
          module: {
            rules: [
              {
                test: /\.js$/,
                use: {
                  loader: 'babel-loader',
                  options: {
                    presets: ['@babel/preset-env'],
                    plugins: [
                      [
                        '@babel/plugin-transform-react-jsx',
                        {
                          pragma: 'h',
                          pragmaFrag: 'Fragment',
                        },
                      ],
                    ],
                  },
                },
                exclude: /node_modules/,
              },
            ],
          },
          resolve: {
            alias: {
              react: 'preact/compat',
              'react-dom/test-utils': 'preact/test-utils',
              'react-dom': 'preact/compat', // Must be below test-utils
              'react/jsx-runtime': 'preact/jsx-runtime',
            },
          },
          externals: {
            // only define the dependencies you are NOT using as externals!
            canvg: 'canvg',
            html2canvas: 'html2canvas',
            dompurify: 'dompurify',
          },
        },
        compiler
      )
    )
    .pipe(dest('dist/'))
    .pipe(livereload());
};

const blockJsScript = (sources, block, dev = false) => {
  const srcArray = sources.constructor === Array ? sources : [sources];

  return src(srcArray)
    .pipe(named())
    .pipe(
      webpack(
        {
          devtool: dev ? 'eval-cheap-module-source-map' : 'source-map',
          mode: dev ? 'development' : 'production',
          output: {
            filename: dev ? `${block}/[name].js` : `${block}/[name].min.js`,
          },
          module: {
            rules: [
              {
                test: /\.js$/,
                use: {
                  loader: 'babel-loader',
                  options: {
                    presets: ['@babel/preset-env'],
                    plugins: [
                      [
                        '@babel/plugin-transform-react-jsx',
                        {
                          pragma: 'h',
                          pragmaFrag: 'Fragment',
                        },
                      ],
                    ],
                  },
                },
                exclude: /node_modules/,
              },
            ],
          },
          resolve: {
            alias: {
              react: 'preact/compat',
              'react-dom/test-utils': 'preact/test-utils',
              'react-dom': 'preact/compat', // Must be below test-utils
              'react/jsx-runtime': 'preact/jsx-runtime',
            },
          },
          externals: {
            // only define the dependencies you are NOT using as externals!
            canvg: 'canvg',
            html2canvas: 'html2canvas',
            dompurify: 'dompurify',
          },
        },
        compiler
      )
    )
    .pipe(dest('dist/'))
    .pipe(livereload());
};

exports.devMainScript = function devMainScript() {
  return jsScript(scriptSrces, true);
};

exports.prodMainScript = function prodMainScript() {
  return jsScript(scriptSrces);
};

exports.jsScript = jsScript;
exports.blockJsScript = blockJsScript;
