import { createPopper } from '@popperjs/core';

export default function attachPopper(item) {
  const tooltip = item.querySelector('.contact__tooltip');
  const popperInstance = createPopper(item, tooltip, {
    placement: 'top-start',
    modifiers: [
      {
        name: 'offset',
        options: {
          offset: [0, 8],
        },
      },
    ],
  });

  let timeoutId;

  item.addEventListener('mouseenter', () => {
    timeoutId = setTimeout(() => {
      tooltip.setAttribute('data-show', '');
      popperInstance.setOptions(options => ({
        ...options,
        modifiers: [...options.modifiers, { name: 'eventListeners', enabled: true }],
      }));
      popperInstance.update();
    }, 500);
  });
  item.addEventListener('mouseleave', () => {
    clearTimeout(timeoutId);
    tooltip.removeAttribute('data-show');
    popperInstance.setOptions(options => ({
      ...options,
      modifiers: [...options.modifiers, { name: 'eventListeners', enabled: false }],
    }));
  });
}
