/* eslint-disable no-undef */
/* eslint-disable func-names */
// eslint-disable-next-line func-names
(function($) {
  const helper = {
    // custom helper function for debounce - how to work see https://codepen.io/Hyubert/pen/abZmXjm
    /**
     * Debounce
     * need for once call function
     *
     * @param { function } - callback function
     * @param { number } - timeout time (ms)
     * @return { function }
     */
    debounce(func, timeout) {
      let timeoutID;
      // eslint-disable-next-line no-param-reassign
      timeout = timeout || 200;
      return function() {
        const scope = this;
        // eslint-disable-next-line prefer-rest-params
        const args = arguments;
        clearTimeout(timeoutID);
        timeoutID = setTimeout(function() {
          func.apply(scope, Array.prototype.slice.call(args));
        }, timeout);
      };
    },
    /**
     * Helper if element exist then call function
     */
    isElementExist(_el, _cb, _argCb) {
      const elem = document.querySelector(_el);
      if (document.body.contains(elem)) {
        try {
          if (arguments.length <= 2) {
            _cb();
          } else {
            _cb(..._argCb);
          }
        } catch (e) {
          // eslint-disable-next-line no-console
          console.log(e);
        }
      }
    },

    /**
     *  viewportCheckerAnimate function
     *
     * @param whatElement - element name
     * @param whatClassAdded - class name if element is in viewport
     * @param classAfterAnimate - class name after element animates
     */
    viewportCheckerAnimate(whatElement, whatClassAdded, classAfterAnimate) {
      jQuery(whatElement)
        .addClass('a-hidden')
        .viewportChecker({
          classToRemove: 'a-hidden',
          classToAdd: `animated ${whatClassAdded}`,
          offset: 100,
          callbackFunction(elem) {
            if (classAfterAnimate) {
              elem.on('animationend', () => {
                elem.addClass('animation-end');
              });
            }
          }
        });
    },
    // helpler windowResize
    windowResize(functName) {
      const self = this;
      $(window).on('resize orientationchange', self.debounce(functName, 200));
    },
    /**
     * Init slick slider only on mobile device
     *
     * @param {DOM} $slider
     * @param {array} option - slick slider option
     */
    mobileSlider($slider, option) {
      if (window.matchMedia('(max-width: 768px)').matches) {
        if (!$slider.hasClass('slick-initialized')) {
          $slider.slick(option);
        }
      } else if ($slider.hasClass('slick-initialized')) {
        $slider.slick('unslick');
      }
    },
    /**
     * Set cookie
     *
     * @param {string} name
     * @param {string} value
     * @param {int} days
     */
    setCookie(name, value, days) {
      var expires = '';
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = '; expires=' + date.toUTCString();
      }
      document.cookie = name + '=' + (value || '') + expires + '; path=/';
    },
    /**
     *Get Cookie
     *
     * @param {string} name
     * @return {string}
     */
    getCookie(name) {
      var nameEQ = name + '=';
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    },
    /**
     * Erase Cookie,
     *
     * @param {string} name
     */
    eraseCookie(name) {
      document.cookie =
        name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
  };

  const theme = {
    /**
     * Main init function
     */
    init() {
      this.plugins(); // Init all plugins
      this.bindEvents(); // Bind all events
      this.initAnimations(); // Init all animations
    },

    /**
     * Init External Plugins
     */
    plugins() {
      // inline svg
      inlineSVG.init({
        svgSelector: 'img.inline-svg' // the class attached to all images that should be inlined
      });
    },

    /**
     * Bind all events here
     *
     */
    bindEvents() {
      const self = this;
      /** * Run on Document Ready ** */
      $(document).on('ready', function() {
        self.smoothScrollLinks();

        helper.isElementExist('.hero', theme.initHero);
        helper.isElementExist('.team', theme.initTeam);
        helper.isElementExist('.cta', theme.initCta);
        helper.isElementExist('.header-nav', theme.initHeaderNav);
        helper.isElementExist('.header-conversation', theme.initHeaderConversation);
      });
      /** * Run on Window Load ** */
      $(window).on('scroll', function() {
        if ($(window).scrollTop() >= 10)
          $('.header').addClass('header--sticky');
        else $('.header').removeClass('header--sticky');
      });
    },

    /**
     * init scroll revealing animations function
     */
    initAnimations() {
      helper.viewportCheckerAnimate('.a-up', 'fadeInUp', true);
      helper.viewportCheckerAnimate('.a-down', 'fadeInDown');
      helper.viewportCheckerAnimate('.a-left', 'fadeInLeft');
      helper.viewportCheckerAnimate('.a-right', 'fadeInRight');
      helper.viewportCheckerAnimate('.a-op', 'fade');
    },

    /**
     * Smooth Scroll link
     */
    smoothScrollLinks() {
      $('a[href^="#"').on('click touchstart', function() {
        const target = $(this).attr('href');
        if (target !== '#' && $(target).length > 0) {
          const offset = $(target).offset().top - $('header').outerHeight();
          $('html, body').animate(
            {
              scrollTop: offset
            },
            500
          );
        }
        return false;
      });
    },
    /**
     * init hero
     */
    initHero() {
      function fadeWords(quotewords) {
        setTimeout(() => {
          Array.prototype.forEach.call(quotewords, function(word, index) {
            let animate = word.animate(
              [
                {
                  opacity: 0,
                  filter: 'blur(5px)'
                },
                {
                  opacity: 1,
                  filter: 'blur(0px)'
                }
              ],
              {
                duration: 1500,
                delay: 2000 * index,
                fill: 'forwards'
              }
            );
          });
        }, 1000);
      }
      function splitWords() {
        let quote = document.querySelector('.split-text'),
          quotewords = quote.innerText.split(' '),
          wordCount = quotewords.length;
        quote.innerHTML = '';
        for (let i = 0; i < wordCount; i++) {
          quote.innerHTML += '<span>' + quotewords[i] + '</span>';
          if (i < quotewords.length - 1) {
            quote.innerHTML += ' ';
          }
        }
        quotewords = document.querySelectorAll('.split-text span');
        console.log(quotewords);
        fadeWords(quotewords);
      }
      splitWords();
    },
    /**
     * init team popup
     */
    initTeam() {
      // show team popup
      $('.team-popup__btn').on('click', function() {
        const target = $(this).attr('data-target');
        $(target).addClass('is-opened');
        $('.team').addClass('is-opened');
        $('html, body').css('overflow', 'hidden');
      });
      // close team popup
      $('.team-popup__close').on('click', function() {
        $(this)
          .closest('.team-popup')
          .removeClass('is-opened');
        $('.team').removeClass('is-opened')
        $('html, body').removeAttr('style');
      });
      // close popup on click outside
      $(document).on('mouseup', function(e) {
        const popup = $('.team-popup');
        if (!popup.is(e.target) && popup.has(e.target).length === 0) {
          $('.team-popup').removeClass('is-opened');
          $('.team').removeClass('is-opened')
          $('html, body').removeAttr('style');
        }
      });
      $('.team-carousel').slick({
        dots: false,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 1000,
        variableWidth: true,
        centerMode: true,
        arrows: false
      });
    },
    /**
     * init Parallax effect
     */
    initCta() {
      // create parallax video effect of .cta section with .cta-bg element
      function parallaxVideo() {
        const ctaBg = $('.cta-bg');
        if (ctaBg.length) {
          const ctaBgHeight = ctaBg.outerHeight();
          const ctaOffsetTop = ctaBg.offset().top;
          const ctaBgBottom = ctaBgHeight + ctaOffsetTop;
          if (
            $(window).scrollTop() + $(window).height() >= ctaOffsetTop &&
            $(window).scrollTop() <= ctaBgBottom
          ) {
            const calc = $(window).scrollTop() - ctaOffsetTop;
            ctaBg.css({
              transform: `translate3d(0, ${calc / 5}px, 0)`
            });
          }
        }
      }
      parallaxVideo();
      $(window).on('scroll', function() {
        parallaxVideo();
      });
    },
    initHeaderNav() {
      $('.hamburger').on('click', function() {
        $('.header-nav').addClass('is-opened')
        $('html, body').css('overflow', 'hidden');
      })
      $('.header-nav__close').on('click', function() {
        $(this)
          .closest('.header-nav')
          .removeClass('is-opened');
          $('html, body').removeAttr('style');
      });
    },
    initHeaderConversation() {
      $('.header-link').on('click', function() {
        $('.header-conversation').addClass('is-opened')
        $('html, body').css('overflow', 'hidden');
      })
      $('.header-conversation__close').on('click', function() {
        $(this)
          .closest('.header-conversation')
          .removeClass('is-opened');
          $('html, body').removeAttr('style');
      });
    }
  };

  // Initialize Theme
  theme.init();
})(jQuery);
