const blocks = document.querySelectorAll('.report-block');
if (blocks) {
  blocks.forEach(block => {
    const button = block.querySelector('.report-block__button');

    button.addEventListener('click', () => {
      block.classList.toggle('open');
    });
  });
}
