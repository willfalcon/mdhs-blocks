import { h, Fragment } from 'preact';
import { useEffect, useState } from 'preact/hooks';
import classNames from 'classnames';
import CountySearch from './CountySearch';
import ContactCard from './ContactCard';
import filterRows from '../js/filterRows';
import getColumnIds from '../js/getColumnIds';
import counties from '../../counties';
import MissingCounty from './MissingCounty';

export default function Block({ heading, sheet, dataset }) {
  const [openCounty, setOpenCounty] = useState(null);
  const [flip, setFlip] = useState(false);
  const [rows, setRows] = useState(null);
  const [searchFieldColumnId, setSearchFieldColumnId] = useState(null);
  const [columnIds, setColumnIds] = useState(null);
  const [missingCounties, setMissingCounties] = useState([]);
  const transitionDuration = 800;
  useEffect(() => {
    async function getData() {
      const res = await fetch(`/wp-json/smartsheet/v1/sheets/${sheet}`).then(res => res.json());
      const { columns, rows } = res;
      const filteredRows = filterRows(rows, dataset);
      setRows(filteredRows);

      const searchField = dataset.searchField;
      const searchFieldColumn = columns.find(col => col.id == searchField);
      setSearchFieldColumnId(searchFieldColumn.id);

      const columnIds = await getColumnIds(dataset.postId, columns);
      setColumnIds(columnIds);

      const missingCounties = counties.filter(
        county =>
          !filteredRows.some(row => {
            return row.cells.find(cell => cell.columnId === searchFieldColumn.id)?.value.includes(county);
          })
      );
      setMissingCounties(missingCounties);
    }
    getData();
  }, []);
  return (
    <>
      <h2 className="contact-block__heading">{heading}</h2>
      <div className={classNames('flip-card', { flip })} style={{ transitionDuration: `${transitionDuration / 1000}s` }}>
        <div className="flip-card-inner">
          <CountySearch setOpenCounty={setOpenCounty} missingCounties={missingCounties} />
          {openCounty &&
            (openCounty === 'missing' ? (
              <MissingCounty setOpenCounty={setOpenCounty} setFlip={setFlip} transitionDuration={transitionDuration} dataset={dataset} />
            ) : (
              <ContactCard
                openCounty={openCounty}
                setOpenCounty={setOpenCounty}
                setFlip={setFlip}
                rows={rows}
                searchFieldColumnId={searchFieldColumnId}
                columnIds={columnIds}
                transitionDuration={transitionDuration}
              />
            ))}
        </div>
      </div>
    </>
  );
}
