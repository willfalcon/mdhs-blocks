export default function getFieldEls(block) {
  const districtEl = block.querySelector('.contact__name');
  const numberEl = block.querySelector('.contact__phone');
  const addressEl = block.querySelector('.contact__address .line-1');
  const addressEl2 = block.querySelector('.contact__address .line-2');
  const websiteEl = block.querySelector('.contact__website');
  const countyEl = block.querySelector('.contact__location');
  const iconsEl = block.querySelector('.contact__icons');

  return {
    districtEl,
    numberEl,
    addressEl,
    addressEl2,
    websiteEl,
    countyEl,
    iconsEl,
  };
}
