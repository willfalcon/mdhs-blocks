import { parsePhoneNumber } from 'libphonenumber-js';
import { h, Fragment } from 'preact';
import { useEffect, useState } from 'preact/hooks';
import Icons from './Icons';
import Export from './Export';

export default function ContactCard({ openCounty, setOpenCounty, setFlip, rows, searchFieldColumnId, columnIds, transitionDuration }) {
  const [data, setData] = useState(null);

  useEffect(() => {
    // const row = rows.find(row => {
    //   const rowCounties = row.cells.find(cell => cell.columnId === searchFieldColumnId);
    //   return rowCounties.value?.toLowerCase().includes(openCounty);
    // });

    const row = rows.find(row => row.id === openCounty);

    const { districtColumnId, numberColumnId, addressColumnId, addressColumn2Id, emailColumnId, websiteColumnId, iconsColumnId } =
      columnIds;

    const county = row.cells.find(cell => cell.columnId === searchFieldColumnId).displayValue;
    const district = row.cells.find(cell => cell.columnId === districtColumnId).displayValue;
    const numberValue = numberColumnId ? row.cells.find(cell => cell.columnId === numberColumnId).displayValue : false;
    const number = numberValue ? parsePhoneNumber(numberValue, 'US') : null;
    const address = addressColumnId ? row.cells.find(cell => cell.columnId === addressColumnId).displayValue : false;
    const address2 = addressColumn2Id ? row.cells.find(cell => cell.columnId === addressColumn2Id).displayValue : false;
    const website = websiteColumnId ? row.cells.find(cell => cell.columnId === websiteColumnId).displayValue : false;
    const email = emailColumnId ? row.cells.find(cell => cell.columnId === emailColumnId).displayValue : false;
    const icons = iconsColumnId ? row.cells.find(cell => cell.columnId === iconsColumnId).displayValue : false;

    setData({
      county,
      district,
      number,
      address,
      address2,
      website,
      email,
      icons,
    });
    setFlip(true);
  }, [openCounty, rows]);

  return (
    <div className="flip-card-back contact">
      <div className="contact__wrapper">
        <button
          className="contact__back"
          onClick={() => {
            setFlip(false);
            setTimeout(() => {
              setData(null);
              setOpenCounty(null);
            }, transitionDuration);
          }}
        >
          <i class="fa-solid fa-left-long"></i> Back
        </button>
        {data && (
          <>
            <p class="contact__location">{data?.county}</p>
            <h3 class="contact__name">{data?.district}</h3>
            {data.icons && <Icons icons={data.icons} />}
            {data.number && (
              <a class="contact__phone" href={data.number.getURI()}>
                {data.number.formatNational()}
              </a>
            )}
            {data.email && (
              <a class="contact__email" href={`mailto:${data.email}`}>
                {data.email}
              </a>
            )}
            {(data.address || data.address2) && (
              <address class="contact__address">
                <a
                  target="_blank"
                  href={`https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(`${data.address} ${data.address2}`)}`}
                >
                  {data.address && <span class="line-1">{data.address}</span>}
                  {data.address2 && <span class="line-2">{data.address2}</span>}
                </a>
              </address>
            )}
            {data.website && (
              <a class="contact__website" href={data.website} target="_blank" rel="noopener">
                {data.website}
              </a>
            )}
            <Export data={data} />
          </>
        )}
      </div>
    </div>
  );
}
