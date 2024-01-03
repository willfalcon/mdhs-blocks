import { parsePhoneNumber } from 'libphonenumber-js';
import { h, Fragment } from 'preact';
import { useEffect } from 'preact/hooks';

export default function MissingCounty({ setOpenCounty, setFlip, transitionDuration, dataset }) {
  useEffect(() => {
    setFlip(true);
  }, []);

  const number = dataset.missingContact ? parsePhoneNumber(dataset.missingContact, 'US') : parsePhoneNumber(dataset.defaultPhone, 'US');

  return (
    <div className="flip-card-back contact">
      <div className="contact__wrapper">
        <button
          className="contact__back"
          onClick={() => {
            setFlip(false);
            setTimeout(() => {
              setOpenCounty(null);
            }, transitionDuration);
          }}
        >
          <i class="fa-solid fa-left-long"></i> Back
        </button>

        <p class="contact__missing-text">
          We don't have any info for your county. Call <a href={number.getURI()}>{number.formatNational()}</a> for help.
        </p>
      </div>
    </div>
  );
}
