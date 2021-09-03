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
import ytPlayer from "./modules/ytplayer.js";
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

//Dynamic Form Label
const input = document.querySelectorAll('.custom-input');

// Find players from DOM and load if found
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
      iv_load_policy: 3,
      start: 15,
      // end: 60,
      playlist: player.dataset.videoId,
    },
  });
});

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

// Dynamic form label
var textfield = document.getElementById('search');
textfield.addEventListener('input', function () {

  if ( this.value == "" ) {
    this.parentNode.classList.remove('filled');
    this.parentNode.classList.remove('focused');
  } else {
    this.parentNode.classList.add('filled');
  }

});

document.addEventListener('DOMContentLoaded', function () {

  // Dynamic form label
  textfield.addEventListener('focus', function() {
    this.parentNode.classList.add('focused');
  });

  textfield.addEventListener('blur', function() {
    if ( this.value == "" ) {
      this.parentNode.classList.remove('filled');
      this.parentNode.classList.remove('focused');
    } else {
      this.parentNode.classList.add('filled');
    }
  })

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


