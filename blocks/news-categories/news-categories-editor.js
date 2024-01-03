function tryUntilSomethingHappens() {
  const links = document.querySelectorAll('.wp-block[data-type="cdhq/news-categories"] .menu-item a');
  if (!links.length) {
    setTimeout(() => {
      tryUntilSomethingHappens();
    }, 500);
  } else {
    links.forEach(link => {
      link.href = '';
      link.addEventListener('click', e => {
        e.preventDefault();
      });
    });
  }
}
tryUntilSomethingHappens();
