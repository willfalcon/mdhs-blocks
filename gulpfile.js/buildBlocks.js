const argv = require('yargs').argv;
const { readdir } = require('fs/promises');
const fs = require('fs');
const { styleScript } = require('./styles');
const { blockJsScript } = require('./scripts');

module.exports = async function buildBlocks() {
  const dev = !!argv.D;
  const blocks = await readdir('blocks/');
  const exclude = ['blocks.php', '_block-import.scss', '.DS_Store'];
  const blockNames = blocks.filter(block => !exclude.includes(block));

  const externalSources = ['node_modules/swiper/swiper-bundle.css'];

  styleScript(externalSources, './dist', dev);

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
};
