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

function setToAuto(e) {
  const panel = e.target;
  panel.style.setProperty('--height', 'auto');
  panel.removeEventListener('transitionend', setToAuto);
}

expanders.forEach(expander => {
  // console.log(expander);
  const panel = expander.querySelector('.expander__panel');

  panel.style.setProperty('--height', `${panel.scrollHeight}px`);
  panel.classList.add('init');

  const button = expander.querySelector('.expander__heading');

  button.addEventListener('click', () => {
    if (expander.classList.contains('expanded')) {
      panel.style.setProperty('--height', `${panel.scrollHeight}px`);
      setTimeout(() => {
        expander.classList.remove('expanded');
      });
    } else {
      panel.addEventListener('transitionend', setToAuto);
      expander.classList.add('expanded');
    }
  });

  if (window.location.hash === `#${button.id}`) {
    expander.classList.toggle('expanded');
  }
});

wp.domReady(() => {
  const hash = location.hash?.toLowerCase();
  if (hash) {
    const target = document.querySelector(hash);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      target.classList.add('expanded');
    }
  }
});
