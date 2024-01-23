import { render, h } from 'preact';
import Block from './Block';

const blocks = document.querySelectorAll('.contact-block');

blocks.forEach(block => {
  const heading = block.querySelector('.contact-block__heading').innerText;
  const sheet = block.dataset.smartsheetId;
  const RootComponent = <Block block={block} heading={heading} sheet={sheet} dataset={block.dataset} />;

  render(RootComponent, block);
});
