import { h } from 'preact';
import { useRef, useState } from 'preact/hooks';
import { debounce } from '../../utils';
import counties from '../../counties';
import { toTitleCase } from '../js/utils';
import classNames from 'classnames';

export default function CountySearch({ setOpenCounty, missingCounties, rows, searchFieldColumnId, searchResultFieldColumnId }) {
  const [countyResults, setCounties] = useState([]);
  const [query, setQuery] = useState('');
  const [suggestion, setSuggestion] = useState('');
  const ref = useRef();

  function keypressListener(e) {
    if (countyResults && countyResults.length === 1) {
      if (e.key === 'Tab') {
        e.preventDefault();
        e.target.value = countyResults[0].cells.find(cell =>
          searchResultFieldColumnId ? cell.columnId === searchResultFieldColumnId : cell.columnId === searchFieldColumnId
        ).value;
      }
      if (e.key === 'Enter') {
        e.preventDefault();
        const listItemButton = ref.current.querySelector('li button');
        listItemButton.click();
        // e.target.removeEventListener('keydown', keypressListener);
      }
    }
  }

  function searchCounties(e) {
    const query = e.target.value;
    setQuery(query);
    if (query) {
      // const matchedCounties = counties.filter(county => county.toLowerCase().includes(query.toLowerCase()));
      const matchedCounties = rows.filter(row =>
        row.cells
          .find(cell => cell.columnId === searchFieldColumnId)
          ?.value?.toLowerCase()
          .includes(query.toLowerCase())
      );

      if (matchedCounties.length < 6) {
        setCounties(matchedCounties);
      }

      if (matchedCounties.length === 1) {
        setQuery(toTitleCase(query));
        setSuggestion(matchedCounties[0]);
        // e.target.addEventListener('keydown', keypressListener);
      } else {
        setSuggestion('');
      }
    } else {
      setCounties([]);
    }
  }

  return (
    <div className="flip-card-front">
      <label htmlFor="location-search">Start typing your county name:</label>
      <div className="county-search-wrap">
        <input
          name="location-search"
          id="location-search"
          type="text"
          onInput={debounce(searchCounties, 200)}
          value={query}
          onKeyDown={keypressListener}
        />
        {suggestion && <span className="location-search-suggestion">{suggestion}</span>}
      </div>
      <ul className="contact-block__location-list" ref={ref}>
        {countyResults.map(county => {
          const missing = missingCounties.includes(county);
          const label = county.cells.find(cell =>
            searchResultFieldColumnId ? cell.columnId === searchResultFieldColumnId : cell.columnId === searchFieldColumnId
          ).value;
          return (
            <li key={county.id} className={classNames({ missing })}>
              <button
                onClick={() => {
                  setOpenCounty(missing ? 'missing' : county.id);
                }}
              >
                {label}
                {missing ? <i class="fa-solid fa-exclamation"></i> : <i class="fa-solid fa-right-long"></i>}
              </button>
            </li>
          );
        })}
      </ul>
    </div>
  );
}
