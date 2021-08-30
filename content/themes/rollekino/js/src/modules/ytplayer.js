/* eslint-disable no-shadow */
/* eslint-disable no-use-before-define */
/**
 * A simple library to load Youtube video's and play them.
 *
 * Automatically loads the Youtube API
 * @param {*} target The target element for the video
 * @param {*} args   Video args
 */
 export default function init(target, args) {
  // Create event for api being ready and make a listerer for it
  const APIReadyEvent = new Event('youtube-api-ready');
  window.addEventListener('youtube-api-ready', () => loadPlayer(target, args));

  // The callback function for Youtube iFrame API
  window.onYouTubeIframeAPIReady = () => window.dispatchEvent(APIReadyEvent);

  // Check if we loaded the API script already
  if (!window.isYouTubeIframeAPILoaded) {
    // Load the script
    loadAPI();
  }
}

// Load API script
const loadAPI = () => {
  const tag = document.createElement('script');
  tag.src = 'https://www.youtube.com/iframe_api';
  const firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
  window.isYouTubeIframeAPILoaded = true;
};

// API loaded, start loading player
const loadPlayer = (target, args) => {
  const playerArgs = {
    events: {
      onReady: onPlayerReady(false, target),
      onStateChange: onStateChange(),
    },
    ...args,
  };
  // Create a dummy element for the video
  const elem = document.createElement('div');
  target.appendChild(elem);

  // Initialize player
  // eslint-disable-next-line no-undef, no-param-reassign
  target.ytPlayer = new YT.Player(elem, playerArgs);
};

/**
 * Callback for onPlayerReady event
 * @param {*} event  Youtube player event
 * @param {*} target Video target element
 */
const onPlayerReady = (event, target) => {

  // Set a class so the play button is visible when video has loaded successfully
  target.parentNode.classList.add('loaded');

  // Find the play button
  const playButton = target.dataset.playButton
    ? document.getElementById(target.dataset.playButton)
    : null;

  if ( ! playButton ) {
    return false;
  }

  // Return the callback function
  // eslint-disable-next-line no-shadow
  return (event) => {
    // Play YouTube video instantly if it is found
    if (event.target.getDuration() <= 0) {
      // console.log('Trailer is likely to be removed...');
    } else {
      playYTVideo(event, target);
    }

    // Add event listener
    playButton.addEventListener('click', () => toggleYTVideo(event, target));
  };
};

// Play the video
const playYTVideo = (player, target) => {
  player.target.mute();
  player.target.playVideo();

  // Delay adding class so we will not see the loading animation
  setTimeout(function() {
    target.parentNode.classList.add('playing');
  }, 700);
};

const toggleYTVideo = (player, target) => {
  player.target.mute();

  if ( target.parentNode.classList.contains('playing') ) {
    player.target.pauseVideo();
    target.parentNode.classList.remove('playing');
  } else {
    player.target.playVideo();
    target.parentNode.classList.add('playing');
  }
};

// eslint-disable-next-line no-unused-vars
const onStateChange = (event) => (event) => {
  const currentPlayer = event.target.getIframe().parentNode;
  // eslint-disable-next-line no-undef
  if (event.data === YT.PlayerState.PLAYING) {
    // Check if other players are playing and pause them
    const allPlayers = [...document.querySelectorAll('[data-video-id]')];
    const otherPlayers = allPlayers.filter((player) => currentPlayer !== player);
    otherPlayers.forEach((otherPlayer) => {
      if (typeof otherPlayer.ytPlayer !== 'undefined') {
        otherPlayer.ytPlayer.pauseVideo();
      }
    });
  }
};
