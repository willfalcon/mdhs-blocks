import { createPopper } from '@popperjs/core';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

const tables = document.querySelectorAll('.contact-block-table');
tables.forEach(tableExport);

function tableExport(table) {
  const button = document.querySelector(`#table-export-${table.dataset.postId}`);
  const tooltip = button.querySelector('.contact__tooltip');

  const popperInstance = createPopper(button, tooltip, {
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
  button.addEventListener('mouseenter', () => {
    timeoutId = setTimeout(() => {
      tooltip.setAttribute('data-show', '');
      popperInstance.setOptions(options => ({
        ...options,
        modifiers: [...options.modifiers, { name: 'eventListeners', enabled: true }],
      }));
      popperInstance.update();
    }, 500);
  });
  button.addEventListener('mouseleave', () => {
    clearTimeout(timeoutId);
    tooltip.removeAttribute('data-show');
    popperInstance.setOptions(options => ({
      ...options,
      modifiers: [...options.modifiers, { name: 'eventListeners', enabled: false }],
    }));
  });

  button.addEventListener('click', () => {
    const doc = new jsPDF({
      unit: 'in',
      format: 'letter',
    });
    const title = table.querySelector('.contact-block__heading').innerText;
    const tableId = table.querySelector('table').id;

    doc.text(title, 0.5, 1);
    autoTable(doc, { html: `#${tableId}`, startY: 1.5 });
    doc.save(`${title}.pdf`);
  });
}
