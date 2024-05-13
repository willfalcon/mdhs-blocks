import { extract } from '@extractus/oembed-extractor';

function initPlaylist(playlist) {
  const videoWrapper = playlist.querySelector('.video-playlist__video-wrapper');

  // set data-playlist-id on default video
  const firstIframe = videoWrapper.querySelector('iframe');
  firstIframe.dataset.playlistId = 0;

  function getVideo(id) {
    return new Promise(async (resolve, reject) => {
      const existingTarget = playlist.querySelector(`iframe[data-playlist-id="${id}"`);

      if (existingTarget) {
        resolve(existingTarget);
        return;
      }
      const url = playlist.querySelector(`.video-playlist__button[data-id="${id}"]`).dataset.url;
      const embed = await extract(url);
      videoWrapper.insertAdjacentHTML('beforeend', embed.html);
      const added = videoWrapper.querySelector(':not([data-playlist-id])');

      if (!added) {
        reject(added);
        return;
      }
      added.dataset.playlistId = id;
      resolve(added);
      return;
    });
  }

  async function handleButtonClick(e) {
    const targetButton = e.target;
    const activeButton = Array.from(buttons).find(button => button.classList.contains('active'));
    const targetId = targetButton.dataset.id;

    // if target video is same as current video, return without doing anything
    if (activeButton.dataset.id == targetId) {
      return;
    }

    // remove active from old button, add it to new target
    activeButton.classList.remove('active');
    targetButton.classList.add('active');

    // get the target video if it's already been added
    const targetVideo = await getVideo(targetId);

    // create and add it if it's not there
    if (targetVideo) {
      const activeIframe = playlist.querySelector(`iframe[data-playlist-id="${activeButton.dataset.id}"]`);

      activeIframe.style.display = 'none';
      targetVideo.style.display = 'block';
    }
  }

  const buttons = playlist.querySelectorAll('.video-playlist__button');
  buttons.forEach(button => {
    button.addEventListener('click', handleButtonClick);
  });

  const expandButton = playlist.querySelector('.video-playlist__expand');
  expandButton.addEventListener('click', e => {
    playlist.classList.toggle('video-expanded');
  });
}

const playlists = document.querySelectorAll('.video-playlist');
playlists.forEach(playlist => {
  initPlaylist(playlist);
});
