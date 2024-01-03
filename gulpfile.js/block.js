const { src, dest, series, watch } = require('gulp');
const livereload = require('gulp-livereload');
const fs = require('fs');
const { readdir } = require('fs/promises');
const argv = require('yargs').argv;

const { styleScript } = require('./styles');
const { jsScript, blockJsScript } = require('./scripts');

async function buildBlockStyles() {
  const dev = !!argv.D;
  const styleSource = `blocks/${argv.block}/${argv.block}.scss`;
  const editorStyleSource = `blocks/${argv.block}/${argv.block}-editor.scss`;
  const styleDest = `./dist/${argv.block}`;
  await fs.access(styleSource, err => {
    if (err) {
      console.log('no default styles');
      return;
    } else {
      return styleScript(styleSource, styleDest, dev);
    }
  });
  await fs.access(editorStyleSource, err => {
    if (err) {
      console.log('no editor styles');
      return;
    } else {
      return styleScript(editorStyleSource, styleDest, dev);
    }
  });
}

function refresh() {
  return src('index.php').pipe(livereload());
}

exports.developBlock = function developBlock() {
  if (!argv.block) {
    console.log('Must set option --block');
    return src('index.php');
  }

  const block = argv.block;
  console.log(block);

  const styleSource = `blocks/${block}/${block}.scss`;
  const styleWatch = [`blocks/${block}/**/*.scss`, `!blocks/${block}/**/*-editor.scss`];
  const editorStyleWatch = `blocks/${block}/**/*-editor.scss`;
  const editorStyleSource = `blocks/${block}/${block}-editor.scss`;
  const styleDest = `./dist/${block}`;
  const scriptSource = argv.block ? `blocks/${argv.block}/${argv.block}.js` : 'blocks/**/*.js';
  const scriptWatch = argv.block ? `blocks/${argv.block}/**/*.js` : 'blocks/**/*.js';
  const editorScriptSource = argv.block ? `blocks/${argv.block}/${argv.block}-editor.js` : 'blocks/**/*.js';
  const editorScriptWatch = argv.block ? `blocks/${argv.block}/**/*-editor.js` : 'blocks/**/*.js';

  livereload.listen();

  watch(styleWatch, () => styleScript(styleSource, styleDest, true));
  watch(editorStyleWatch, () => styleScript(editorStyleSource, styleDest, true));
  watch('src/styles/editor-styles.scss', () => styleScript('src/styles/editor-styles.scss', 'dist/', true));
  watch(scriptWatch, () => blockJsScript(scriptSource, block));
  watch(editorScriptWatch, () => blockJsScript(editorScriptSource, block, true));
  watch(['**/*.php|json'], refresh);
};

// const buildBlock = series(buildBlockScript, buildBlockStyles);
const buildBlock = series(buildBlockStyles);

exports.buildBlockStyles = buildBlockStyles;
exports.buildBlock = buildBlockStyles;

async function buildBlocks() {
  const dev = !!argv.D;
  const blocks = await readdir('blocks/');
  const exclude = ['blocks.php', '_block-import.scss', '.DS_Store'];
  const blockNames = blocks.filter(block => !exclude.includes(block));

  await Promise.all(
    blockNames.map(async block => {
      const styleSource = `blocks/${block}/${block}.scss`;
      const editorStyleSource = `blocks/${block}/${block}-editor.scss`;
      const styleDest = `./dist/${block}`;
      const scriptSource = `blocks/${block}/${block}.js`;
      const editorScriptSource = `blocks/${block}/${block}-editor.js`;
      console.log(`building ${block}`);
      await fs.access(styleSource, err => {
        if (err) {
          console.log('no default styles');
          return;
        } else {
          return styleScript(styleSource, styleDest, dev);
        }
      });
      await fs.access(editorStyleSource, err => {
        if (err) {
          console.log('no editor styles');
          return;
        } else {
          return styleScript(editorStyleSource, styleDest, dev);
        }
      });
      await fs.access(scriptSource, err => {
        if (err) {
          console.log('no default script');
          return;
        } else {
          return blockJsScript(scriptSource, block, dev);
        }
      });
      await fs.access(editorScriptSource, err => {
        if (err) {
          console.log('no editor script');
          return;
        } else {
          return blockJsScript(editorScriptSource, block, dev);
        }
      });
    })
  );
}

async function developBlocks() {
  const blocks = await readdir('blocks/');
  const exclude = ['blocks.php', '_block-import.scss', '.DS_Store'];
  const blockNames = blocks.filter(block => !exclude.includes(block));

  livereload.listen();

  const styleWatcher = watch(['blocks/**/*.scss']);
  function runStyleScript(path) {
    const block = blockNames.filter(name => path.includes(name))[0];
    console.log(`building ${block} styles`);
    styleScript(path, `./dist/${block}`, true);
  }
  styleWatcher.on('change', runStyleScript);

  const jsWatcher = watch(['blocks/**/*.js']);
  function runJsScript(path) {
    const block = blockNames.filter(name => path.includes(name))[0];
    console.log(`building ${block} script`);
    blockJsScript(path, block, false);
  }
  jsWatcher.on('change', runJsScript);

  watch(['blocks/**/*.json|php'], refresh);
}

exports.buildBlocks = buildBlocks;
exports.developBlocks = developBlocks;
