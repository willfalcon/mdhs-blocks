const expanders = document.querySelectorAll('.expander');

document.querySelectorAll('a').forEach(el => {
  if (el.hash && el.pathname == location.pathname) {
    el.addEventListener('click', e => {
      e.preventDefault();
      const target = document.querySelector(el.hash);
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
      target.classList.add('expanded');
      const url = new URL(location);
      url.hash = el.hash;
      history.pushState({}, '', url);
    });
  }
});

expanders.forEach(expander => {
  // console.log(expander);
  const panel = expander.querySelector('.expander__panel');

  panel.style.setProperty('--height', `${panel.scrollHeight}px`);
  panel.classList.add('init');

  const button = expander.querySelector('.expander__heading');
  button.addEventListener('click', () => {
    expander.classList.toggle('expanded');
  });

  if (window.location.hash === `#${button.id}`) {
    expander.classList.toggle('expanded');
  }
});

wp.domReady(() => {
  const hash = location.hash;
  if (hash) {
    const target = document.querySelector(hash);
    target.classList.add('expanded');
  }
});
