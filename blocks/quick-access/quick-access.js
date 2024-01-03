import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import viewportSize from 'viewport-size';

const width = viewportSize.getWidth();
const ipad = width >= 820 && width < 1024;

const wrapper = document.querySelector('.quick-access-wrapper');
const isPreview = wrapper ? wrapper.classList.contains('is-preview') : false;

if (width > 768 || isPreview) {
  const swiper = new Swiper('.swiper', {
    // configure Swiper to use modules
    modules: [Navigation],
    slidesPerView: ipad ? 2 : 3,
    spaceBetween: 30,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    grabCursor: true,
  });
}
