import initLocations from './locations/locations';

const location = document.querySelector('#location-status');
if (location) {
  initLocations(location);
}
