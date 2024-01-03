import { h } from 'preact';
import { createPopper } from '@popperjs/core';
import { iconSwitch } from './iconSwich';
import { useEffect, useRef } from 'preact/hooks';

function Icon(iconName) {
  const icon = iconSwitch(iconName);
  const ref = useRef();
  const tooltip = useRef();
  useEffect(() => {
    if (ref.current && tooltip.current) {
      const popperInstance = createPopper(ref.current, tooltip.current, {
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
      ref.current.addEventListener('mouseenter', () => {
        timeoutId = setTimeout(() => {
          tooltip.current.setAttribute('data-show', '');
          popperInstance.setOptions(options => ({
            ...options,
            modifiers: [...options.modifiers, { name: 'eventListeners', enabled: true }],
          }));
          popperInstance.update();
        }, 500);
      });
      ref.current.addEventListener('mouseleave', () => {
        clearTimeout(timeoutId);
        tooltip.current.removeAttribute('data-show');
        popperInstance.setOptions(options => ({
          ...options,
          modifiers: [...options.modifiers, { name: 'eventListeners', enabled: false }],
        }));
      });
    }
  }, [ref.current, tooltip.current]);
  return (
    <li class="contact__icon" ariaDescribedby={`${iconName}-tooltip`} ref={ref}>
      {icon}
      <div class="contact__tooltip" id={`${iconName}-tooltip`} role="tooltip" ref={tooltip}>
        {iconName}
        <div class="tooltip-arrow" data-popper-arrow></div>
      </div>
    </li>
  );
}

export default function Icons({ icons }) {
  const iconList = icons.split(', ');
  return <ul class="contact__icons">{iconList.map(Icon)}</ul>;
}
