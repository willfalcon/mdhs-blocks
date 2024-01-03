import { parsePhoneNumber } from 'libphonenumber-js';
import { createPopper } from '@popperjs/core';

import counties from '../../counties';
import { iconSwitch } from '../../utils';

function debounce(func, wait, immediate) {
  var timeout;
  return function () {
    var context = this,
      args = arguments;
    var later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

function toTitleCase(str) {
  if (!str) return;
  return str
    .replace(/\w\S*/g, function (txt) {
      return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    })
    .replace(/\s+$/, '');
}

export default function initLocations(location) {
  const input = document.querySelector('#location-search');
  input.addEventListener('input', doSearch);
}

function attachListener(button) {
  const block = document.querySelector('#location-status');
  const sheet = block.dataset.sheet;
  const searchField = block.dataset.searchField;
  const body = document.querySelector('.location__body-wrapper');
  const locationResult = block.querySelector('.location-result');
  const locationResultCounty = locationResult.querySelector('.location-result__county');

  button.addEventListener('click', async e => {
    locationResultCounty.innerText = toTitleCase(button.id) + ' County';
    body.classList.add('loading');

    const rows = await fetch(
      `${document.body.dataset.root}/wp-json/smartsheet/v1/county-search?sheet=${sheet}&counties=${button.id}&searchField=${searchField}`
    ).then(res => res.json());
    const contactBlock = await fetch(`${document.body.dataset.root}/wp-json/wp/v2/contact-block/${block.dataset.contactBlock}`).then(res =>
      res.json()
    );
    const iconLinks = await fetch(`${document.body.dataset.root}/wp-json/mdhs/v1/get-field/contact_options/icons_links`).then(res =>
      res.json()
    );

    const typeId = contactBlock.acf.type_of_office ? parseInt(contactBlock.acf.type_of_office) : null;
    const contactCard = contactBlock.acf.contact_card;
    const nameId = contactCard.primary_label ? parseInt(contactCard.primary_label) : null;
    const phoneId = contactCard.phone ? parseInt(contactCard.phone) : null;
    const emailId = contactCard.email ? parseInt(contactCard.email) : null;
    const iconsId = contactBlock.acf.icons_column ? parseInt(contactBlock.acf.icons_column) : null;

    const { county, locations } = rows[0];

    const cardContent = `
      <h3 class="location-result__county">${toTitleCase(button.id) + ' County'}</h3>
      <button class="location-result__back">Back</button>
      <ul class="location-result__list">
        ${Object.values(locations)
          .map(({ cells }) => {
            const type = cells.find(c => c.columnId === typeId)?.displayValue;
            const name = cells.find(c => c.columnId === nameId)?.displayValue;
            const phone = cells.find(c => c.columnId === phoneId)?.displayValue;
            const number = phone ? parsePhoneNumber(phone, 'US') : null;
            const email = cells.find(c => c.columnId === emailId).displayValue;
            const icons = cells.find(c => c.columnId === iconsId).displayValue;
            const iconList = icons.split(', ');
            return ` 
            <li class="location-result__item"> 
              <h4 class="location-result__type">${type}</h4>
              <ul class="location-result__icons">
                ${iconList
                  .map(iconName => {
                    if (!iconName) {
                      return;
                    }
                    const icon = iconSwitch(iconName);
                    const link = iconLinks.find(link => link.icon_option == iconName)?.link;
                    if (!icon) {
                      return;
                    }
                    return `
                    <li class="location-result__icon" aria-describedby="${iconName}-tooltip">
                      <a href="${link}" style="position: relative;">
                        ${icon}
                      </a>
                      <div class="location-result__tooltip" id="${iconName}-tooltip" role="tooltip">
                        ${iconName}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                      </div>
                    </li>
                    `;
                  })
                  .join('')}
              </ul>
              <h5 class="location-result__name">${name}</h5>
              ${number ? `<a class="location-result__call" href="${number.getURI()}">${number.formatNational()}</a>` : ``}
              ${email ? `<a class="location-result__email" href="mailto:${email}">${email}</a>` : ``}
            </li>
          `;
          })
          .join('')}
      </u>
    `;
    locationResult.innerHTML = cardContent;

    const tooltips = locationResult.querySelectorAll('.location-result__icon').forEach(item => {
      const tooltip = item.querySelector('.location-result__tooltip');
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
    });

    body.classList.add('result');
    body.classList.remove('loading');

    const back = locationResult.querySelector('.location-result__back');
    back.addEventListener('click', () => {
      locationResult.innerHTML = `<h3 class="location-result__county">${toTitleCase(button.id)}</h3>`;
      body.classList.add('result-leaving');
      body.classList.remove('result');
      setTimeout(() => {
        body.classList.remove('result-leaving');
      }, 250);
    });
  });
}

function doSearch(e) {
  const input = document.querySelector('#location-search');

  const query = e.target.value;
  const list = document.querySelector('.location__list');
  if (query) {
    list.innerHTML = '';

    const matchedCounties = counties.filter(county => county.toLowerCase().includes(query.toLowerCase()));

    if (matchedCounties.length < 6) {
      const newList = matchedCounties.map(
        item => `
        <li class="location__item" data-location="${item}">
          <button id="${item.toLowerCase()}">
            ${item} <i class="fa-solid fa-right-long"></i>
          </button>
        </li>
      `
      );
      list.innerHTML = newList.join('');
      const newItems = document.querySelectorAll('.location__item button');
      newItems.forEach(attachListener);
    }

    const suggestion = document.querySelector('.location__input-suggestion');

    function keypressListener(e) {
      if (e.key === 'Tab') {
        e.preventDefault();
        input.value = matchedCounties[0];
      }
      console.log(e);
      if (e.key === 'Enter') {
        e.preventDefault();
        const listItemButton = list.querySelector('li button');
        listItemButton.click();
        input.removeEventListener('keydown', keypressListener);
      }
    }

    if (matchedCounties.length === 1) {
      input.value = toTitleCase(input.value);
      suggestion.innerText = matchedCounties[0];
      input.addEventListener('keydown', keypressListener);
    } else {
      suggestion.innerText = '';
    }
  } else {
    list.innerHTML = '';
  }
}
