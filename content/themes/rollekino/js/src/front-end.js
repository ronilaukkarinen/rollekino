/**
 * Air theme JavaScript.
 */

// Import modules (comment to disable)
import MoveTo from 'moveto';
import LazyLoad from 'vanilla-lazyload';
import reframe from 'reframe.js';
import getLocalization from './modules/localization';
import { styleExternalLinks, getChildAltText } from './modules/external-link';
import { setFigureWidths, setLazyLoadedFigureWidth } from './modules/gutenberg-helpers';
import ytPlayer from './modules/ytplayer.js';
import MediaBox from 'mediabox';
import 'what-input';

// Navigation
import './modules/navigation.js';

// Define Javascript is active by changing the body class
document.body.classList.remove('no-js');
document.body.classList.add('js');

// Fit video embeds to container
reframe('.wp-has-aspect-ratio iframe');

// Style external links
styleExternalLinks();

// Set non-lazyloaded figures width so captions in aligned images will be same width as image
const figures = document.querySelectorAll('figure');
setFigureWidths(figures);

// Init lazyload
// Usage example on template side when air-helper enabled:
// <?php vanilla_lazyload_tag( get_post_thumbnail_id( $post->ID ) ); ?>
// Refer to documentation:
// 1) https://github.com/digitoimistodude/air-helper#image-lazyloading-1
// 2) https://github.com/verlok/vanilla-lazyload#-getting-started---html
var rollekino_LazyLoad = new LazyLoad({
  callback_loaded: setLazyLoadedFigureWidth,
});

// After your content has changed...
rollekino_LazyLoad.update();

// Dynamic Form Label
const input = document.querySelectorAll('.custom-input');

// Open YouTube videos full screen from button
MediaBox('.mediabox', {
  start:  0,
  autoplay: 1,
  modestbranding: 1,
  autohide: 1,
  mute: 0,
  loop: 0,
  controls: 1,
  showinfo: 0,
  disablekb: 1,
  enablejsapi: 1,
});

// Find players from DOM and load if found
// But only on desktop
if ( window.innerWidth > 600 ) {
  const players = document.querySelectorAll(".youtube-player");

  players.forEach((player) => {
    new ytPlayer(player, {
      height: player.dataset.height ? player.dataset.height : 480,
      width: player.dataset.width ? player.dataset.width : 854,
      autoplay: player.dataset.autoplay ? player.dataset.autoplay : false,
      videoId: player.dataset.videoId ? player.dataset.videoId : false,
      playerVars: {
        autoplay: 1,
        modestbranding: 1,
        autohide: 1,
        mute: 1,
        loop: 1,
        controls: 0,
        showinfo: 0,
        rel: 0,
        disablekb: 1,
        enablejsapi: 1,
        cc_load_policy: 0,
        iv_load_policy: 3,
        start: 15,
        end: 120,
        playlist: player.dataset.videoId,
      },
    });
  });
}

// jQuery start
(function ($) {
  // Accessibility: Ensure back to top is right color on right background
  // Note: Needs .has-light-bg or .has-dark-bg class on all blocks
  var stickyOffset = $('.back-to-top').offset();
  var $contentDivs = $('.block, .site-footer');
  $(document).scroll(function () {
    $contentDivs.each(function (k) {
      var _thisOffset = $(this).offset();
      var _actPosition = _thisOffset.top - $(window).scrollTop();
      if (
        _actPosition < stickyOffset.top &&
        _actPosition + $(this).height() > 0
      ) {
        $('.back-to-top')
          .removeClass('has-light-bg has-dark-bg')
          .addClass(
            $(this).hasClass('has-light-bg') ? 'has-light-bg' : 'has-dark-bg'
          );
        return false;
      }
    });
  });

  // Detect Visible section on viewport from bottom
  // @link https://codepen.io/BoyWithSilverWings/pen/MJgQqR
  $.fn.isInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
  };

  // Accessibility: Ensure back to top is right color on right background
  $(window).on('resize scroll', function () {
    $('.block, .site-footer').each(function () {
      if ($(this).isInViewport()) {
        $('.back-to-top')
          .removeClass('has-light-bg has-dark-bg')
          .addClass(
            $(this).hasClass('has-light-bg') ? 'has-light-bg' : 'has-dark-bg'
          );
      }
    });
  });

  // Hide or show the 'back to top' link
  $(window).scroll(function () {
    // Back to top
    var offset = 300; // Browser window scroll (in pixels) after which the 'back to top' link is shown
    var offset_opacity = 1200; // Browser window scroll (in pixels) after which the link opacity is reduced
    var scroll_top_duration = 700; // Duration of the top scrolling animation (in ms)
    var link_class = '.back-to-top';

    if ($(this).scrollTop() > offset) {
      $(link_class).addClass('is-visible');
    } else {
      $(link_class).removeClass('is-visible');
    }

    if ($(this).scrollTop() > offset_opacity) {
      $(link_class).addClass('fade-out');
    } else {
      $(link_class).removeClass('fade-out');
    }
  });

  // Document ready start
  $(function () {
    // Your JavaScript here
  });
})(jQuery);

document.addEventListener('DOMContentLoaded', function () {

  // Search modal stuff
  var openModalButton = document.getElementById('open-search');
  var openModalButtonMobile = document.getElementById('open-search-mobile');
  var searchField = document.getElementById('search-field');
  var searchModal = document.getElementById('search-modal');

  // Open modal by click or enter
  openModalButton.onclick = function () {
    searchModal.classList.toggle('is-visible');
    openModalButton.classList.toggle('toggled');
    searchField.focus();

    if ( openModalButton['aria-expanded'].value == 'true"') {
      openModalButton.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('is-search-on');
      document.body.classList.remove('hide-containers');
    } else {
      openModalButton.setAttribute('aria-expanded', 'true');
    }

    if ( searchModal['aria-hidden'].value == 'true"') {
      searchModal.setAttribute('aria-hidden', 'false');
    } else {
      searchModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('is-search-on');
      document.body.classList.remove('hide-containers');
    }
  }

  openModalButtonMobile.onclick = function () {
    searchModal.classList.toggle('is-visible');
    openModalButtonMobile.classList.toggle('toggled');
    searchField.focus();

    if ( openModalButtonMobile['aria-expanded'].value == 'true"') {
      openModalButtonMobile.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('is-search-on');
      document.body.classList.remove('hide-containers');
    } else {
      openModalButtonMobile.setAttribute('aria-expanded', 'true');
    }

    if ( searchModal['aria-hidden'].value == 'true"') {
      searchModal.setAttribute('aria-hidden', 'false');
    } else {
      searchModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('is-search-on');
      document.body.classList.remove('hide-containers');
    }
  }

  document.addEventListener('keydown', function(event){
    if ( event.key === 'Escape' ) {
      searchModal.classList.remove('is-visible');
      openModalButton.classList.remove('toggled');

      if ( openModalButton['aria-expanded'].value == 'true"') {
        openModalButton.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('is-search-on');
        document.body.classList.remove('hide-containers');
      } else {
        openModalButton.setAttribute('aria-expanded', 'true');
      }

      if ( searchModal['aria-hidden'].value == 'true"') {
        searchModal.setAttribute('aria-hidden', 'false');
      } else {
        searchModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('is-search-on');
        document.body.classList.remove('hide-containers');
      }
    }
  });

  // Spoiler warning tooltip on load
  const spoilerWarningItems = document.querySelectorAll('.spoiler-warning');
  spoilerWarningItems.forEach((span) => {

    const originalHTML = span.innerHTML;
    const spoilerWrapperHTML = '<span class="spoiler-warning-wrapper">' + originalHTML + '</span>';
    span.innerHTML = spoilerWrapperHTML;

    span.onclick = function () {
      this.classList.toggle('visible');
    }

    // span.addEventListener('mouseover', function () {
    // });

    // span.addEventListener('mouseleave', function () {
    // });
  });

  function RemoveClass(elem, newClass) {
      elem.className = elem.className.replace(/(?:^|\s)newClass(?!\S)/g, '')
  }

  const easeFunctions = {
    easeInQuad: function (t, b, c, d) {
      t /= d;
      return c * t * t + b;
    },
    easeOutQuad: function (t, b, c, d) {
      t /= d;
      return -c * t * (t - 2) + b;
    }
  };
  const moveTo = new MoveTo({
      ease: 'easeInQuad'
    },
    easeFunctions
  );
  const triggers = document.getElementsByClassName('js-trigger');
  for (var i = 0; i < triggers.length; i++) {
    moveTo.registerTrigger(triggers[i]);
  }
});

// Add aria-labels to links without text or aria-labels and contain image with alt text
const links = [...document.querySelectorAll('a')];
const linksWithImgChildren = links.forEach(link => {
  // If link already has text content or an aria label no need to add aria-label
  if (link.textContent.trim() !== '' || link.ariaLabel) {
    return;
  }

  const ariaLabel = getChildAltText(link);
  if (ariaLabel !== '') {
    link.ariaLabel = ariaLabel;
  }
});
