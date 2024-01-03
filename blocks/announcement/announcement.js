import Cookies from 'js-cookie';

const announcements = document.querySelectorAll('.announcement');

announcements.forEach(announcement => {
  const expander = announcement.querySelector('.announcement__expander');

  const key = announcement.dataset.key;

  expander.addEventListener('click', () => {
    announcement.classList.toggle('closed');
    if (!Cookies.get(key) && announcement.classList.contains('closed')) {
      Cookies.set(key, true, { expires: 7 });
    }
  });

  const bodyHeight = announcement.querySelector('.announcement__body').getBoundingClientRect().height;

  announcement.querySelector('.announcement__body').style.setProperty('--height', `${bodyHeight}px`);
  announcement.classList.add('init');

  const alreadyClosed = Cookies.get(key) && Cookies.get(key) === 'true';

  if (announcement.classList.contains('delay') && !alreadyClosed) {
    const delay = parseInt(announcement.dataset.delay);
    setTimeout(() => {
      announcement.classList.remove('closed');
    }, delay * 1000);
  } else if (announcement.classList.contains('viewport') && !alreadyClosed) {
    const observer = new IntersectionObserver(
      entries => {
        if (entries[0].isIntersecting) {
          announcement.classList.remove('closed');
          observer.unobserve(announcement);
        }
      },
      {
        // threshold: 1,
        rootMargin: `-25% 0% -25% 0%`,
      }
    );
    observer.observe(announcement);
  } else if (!alreadyClosed) {
    announcement.classList.remove('closed');
  }
});
