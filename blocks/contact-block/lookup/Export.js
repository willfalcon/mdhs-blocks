import { h, Fragment } from 'preact';
import { jsPDF } from 'jspdf';
import { useEffect, useRef } from 'preact/hooks';
import { createPopper } from '@popperjs/core';

export default function Export({ data }) {
  const { county, district, number, address, address2, website, email, icons } = data;
  const ref = useRef();
  const tooltip = useRef();
  function handleExport() {
    const doc = new jsPDF({
      unit: 'in',
      format: 'letter',
    });
    doc.text(district, 1, 1);
    doc.setFontSize(12);
    doc.text(
      `
    ${number ? number.formatNational() : ''}

    ${email ? email : ''}

    ${address ? address : ''}
    ${address2 ? address2 : ''}

    ${website ? website : ''}`,
      1,
      1.5
    );
    doc.save(`${district}.pdf`);
  }

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
    <button className="button contact__export" onClick={handleExport} ariaDescribedby="export-tooltip" ref={ref}>
      <i class="fa-solid fa-download"></i>
      <div class="contact__tooltip" id="export-tooltip" role="tooltip" ref={tooltip}>
        Export Contact Info
        <div class="tooltip-arrow" data-popper-arrow></div>
      </div>
    </button>
  );
}
