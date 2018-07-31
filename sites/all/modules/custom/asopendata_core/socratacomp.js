var jQ = jQuery.noConflict(); 

(function (jQ) {
  if (!jQ) {
    console.error('jQuery appears not to be defined on this page!');
    return;
  }

  var header = jQ('#site-chrome-admin-header');
  var createMenu = jQ('#site-chrome-create-menu');

  header.find('[aria-haspopup]').
    on('click', toggleAdminDropdown).
    on('blur', blurAdminDropdown).
    on('keypress', keypressAdminDropdown).
    on('keydown', keydownAdminDropdown).
    on('keyup', keyupAdminDropdown);

  header.find('[role="menu"] li a').
    on('blur', blurAdminDropdown).
    on('keydown', keydownAdminDropdownItem).
    on('keyup', keyupAdminDropdownItem);

  if (getAppToken()) {
    createMenu.find('.create-story').on('click', clickCreateStory);
    createMenu.find('.create-measure').on('click', clickCreateMeasure);
  } else {
    createMenu.find('.create-story').hide();
    createMenu.find('.create-measure').hide();
  }

  // End script execution; only hoisted methods below.
  return;

  /**
   * - Toggle active class when any click
   *   occurs on the menu button.
   * - Toggle a11y aria-hidden.
   * - Focus the first menu item to assist navigation.
   */
  function toggleAdminDropdown(event) {
    var jQdropdown = jQ(event.target).closest('[aria-haspopup]');
    var jQmenu = jQdropdown.find('[role="menu"]');

    jQdropdown.toggleClass('active');

    jQmenu.
      // Is the menu showing?
      attr('aria-hidden', !jQdropdown.hasClass('active')).
      // Focus the first element of the menu.
      find('li a').first().focus();
  }

  /**
   * Catch and block SPACE and DOWN on the dropdown toggle.
   */
  function keydownAdminDropdown(event) {
    // 32 === SPACE, 40 === DOWN
    if (event.keyCode == 32 || event.keyCode == 40) {
      event.preventDefault();
      event.stopPropagation();
    }
  }

  /**
   * Toggle dropdown menu visibility on SPACE and DOWN.
   */
  function keyupAdminDropdown(event) {
    // 32 === SPACE, 40 === DOWN
    if (event.keyCode === 32 || event.keyCode === 40) {
      event.preventDefault();
      event.stopPropagation();

      toggleAdminDropdown(event);
    }
  }

  /**
   * Catch and block UP and DOWN on the dropdown item.
   */
  function keydownAdminDropdownItem(event) {
    // 40 === DOWN, 38 === UP
    if (event.keyCode === 40 || event.keyCode === 38) {
      event.preventDefault();
      event.stopPropagation();
    }
  }

  /**
   * Catch keypress and dispatch as click for accessibility purposes
   */
  function keypressAdminDropdown(event) {
    // 13 === ENTER, 32 === SPACE
    if ([13, 32].includes(event.which)) {
      var clickEvent = new Event('click', { 'bubbles': true });
      event.target.dispatchEvent(clickEvent);
    }
  }

  /**
   * - Chooses the next focusable dropdown item when DOWN
   *   is pressed. If there isn't one, the current item
   *   remains focused.
   * - Chooses the previous focusable dropdown item when
   *   UP is pressed. If there isn't one, the menu toggle
   *   is focused.
   */
  function keyupAdminDropdownItem(event) {
    var jQtarget = jQ(event.target);
    var keyCode = event.keyCode;

    // 40 === DOWN, 38 === UP
    if (keyCode === 40 || keyCode === 38) {
      event.preventDefault();
      event.stopPropagation();
    }

    if (keyCode === 40) {
      jQtarget.closest('li').next('li').find('a').focus();
    } else if (keyCode === 38) {
      var jQpreviousItem = jQtarget.closest('li').prev('li').find('a');

      if (jQpreviousItem.length) {
        jQpreviousItem.focus();
      } else {
        jQtarget.closest('[aria-haspopup]').focus();
      }
    }
  }

  /**
   * Wait and watch where focus goes to, if the focus
   * ends up in the same dropdown, don't do anything.
   * If it ends up anywhere else, close the dropdown right up.
   */
  function blurAdminDropdown(event) {
    var target = event.target;

    setTimeout(function() {
      var jQmenu = jQ(document.activeElement).closest('[aria-haspopup]');
      var jQtargetMenu = jQ(target).closest('[aria-haspopup]');

      if (jQmenu.length === 0 || jQmenu[0] !== jQtargetMenu[0]) {
        jQtargetMenu.removeClass('active');
        jQtargetMenu.find('[role="menu"]').
          attr('aria-hidden', 'true');
      }
    }, 1);
  }

  /**
   * Creates a story.
   */
  function clickCreateStory() {
    var metadata = {
      displayFormat: {},
      displayType: 'story',
      metadata: {
        availableDisplayTypes: ['story'],
        initialized: false,
        isStorytellerAsset: true,
        jsonQuery: {},
        renderTypeConfig: {
          visible: {
            story: true
          }
        },
        tileConfig: {}
      },
      name: generateDatedTitle(jQ(this).data('default-title')),
      query: {}
    };

    createPublishedView(metadata).then(
      function(id) { window.location.href = '/stories/s/' + id + '/create'; },
      console.error
    );
  }

  /**
   * Creates a measure.
   */
  function clickCreateMeasure() {
    var metadata = {
      displayFormat: {},
      displayType: 'measure',
      metadata: {
        availableDisplayTypes: ['measure'],
        jsonQuery: {},
        renderTypeConfig: {
          visible: {
            measure: true
          }
        }
      },
      name: generateDatedTitle(jQ(this).data('default-title')),
      query: {}
    };

    createPublishedView(metadata).then(
      function(id) { window.location.href = '/d/' + id; },
      console.error
    );
  }

  /**
   * Allocates and publishes a new asset. This helper allows us to share a
   * common workflow for stories and measures.
   *
   * NOTE: frontend/public/javascripts/screens/browse.js has a nearly-duplicated
   * implementation; the duplication was an improvement over having two totally
   * different implementations. Please try not to let the two implementations
   * drift too far apart before we're able to consolidate.
   *
   * NOTE: Promise wrapping is necessary in order to overcome the limitations of
   * jQuery 1.x AJAX capabilities. Promises are polyfilled in IE11.
   */
  function createPublishedView(metadata) {
    // You can't perform this operation without an app token.
    var appToken = getAppToken();
    if (!appToken) {
      return Promise.reject(new Error('AppToken is not accessible!'));
    }

    // Allocate a new asset.
    return new Promise(function(resolve, reject) {
      var allocationError = new Error(
        'View allocation failed; check network response for details.'
      );

      jQ.ajax({
        url: '/api/views.json',
        type: 'POST',
        data: JSON.stringify(metadata),
        headers: {
          'Content-type': 'application/json',
          'X-App-Token': appToken
        },
        success: function(response) {
          var valid = response.hasOwnProperty('id') && validate4x4(response.id);
          valid ? resolve(response.id) : reject(allocationError);
        },
        error: function() { reject(allocationError); }
      });
    }).then(function(id) {
      // If allocation was successful, publish the asset.
      return new Promise(function(resolve, reject) {
        var publicationError = new Error(
          'View publication failed; check network response for details.'
        );

        jQ.ajax({
          url: '/api/views/' + id + '/publication.json?accessType=WEBSITE',
          type: 'POST',
          headers: {
            'X-App-Token': appToken
          },
          success: function(response) {
            var valid = response.hasOwnProperty('id') && validate4x4(response.id);
            valid ? resolve(response.id) : reject(publicationError);
          },
          error: function() { reject(publicationError); }
        });
      });
    });
  }

  /**
   * Validates given string is 4x4 id
   * @param {String} testString
   */
  function validate4x4(testString) {
    return /^[a-z0-9]{4}-[a-z0-9]{4}jQ/i.test(testString);
  }

  function getAppToken() {
    var siteChromeAppToken = window.socrata && window.socrata.siteChrome && window.socrata.siteChrome.appToken;
    var blistAppToken = window.blist && window.blist.configuration && window.blist.configuration.appToken;

    return siteChromeAppToken || blistAppToken || null;
  }

  function generateDatedTitle(defaultTitle) {
    var now = new Date();
    var datePieces = [
      String(now.getMonth() + 1).padStart(2, 0),
      String(now.getDate()).padStart(2, 0),
      now.getFullYear()
    ];
    return defaultTitle + ' - ' + datePieces.join('-');
  }

})(jQuery);


(function (jQ) {
  if (!jQ) {
    return;
  }

  jQ(function() {
    jQ('.disablePreviewMode').click(function(evt) {
      evt.preventDefault();
      // Apparently this is how you delete cookies?
      if (jQ.cookies) {
        jQ.cookies.del('socrata_site_chrome_preview');
      } else {
        document.cookie = 'socrata_site_chrome_preview=deleted; expires=' + new Date(0).toUTCString();
      }
      window.location.reload();
    });
  });
})(jQuery);
/*eslint no-unused-vars:0*/


var siteChromeTemplate;
var jQsiteChromeHeader;
var jQsiteChromeHeaderDesktopNav;
var jQsiteChromeHeaderMobileNav;
var jQsiteChromeMobileMenu;
var navLinkFullWidth;
var navbarRightWidth;
var initialBodyOverflowY;

(function (jQ) {
  if (!jQ) {
    return;
  }

  jQ(function() {
    jQsiteChromeHeader = jQ('#site-chrome-header');
    jQsiteChromeHeaderDesktopNav = jQsiteChromeHeader.find('nav.desktop');
    jQsiteChromeHeaderMobileNav = jQsiteChromeHeader.find('nav.mobile');
    jQsiteChromeMobileMenu = jQsiteChromeHeader.find('.mobile-menu');
    siteChromeTemplate = jQsiteChromeHeader.attr('template');
    navLinkFullWidth = jQsiteChromeHeaderDesktopNav.find('.site-chrome-nav-links').width();

    if (siteChromeTemplate === 'evergreen')
      navbarRightWidth = jQsiteChromeHeader.find('.evergreen-link-cluster').width();
    else if (siteChromeTemplate === 'rally')
      navbarRightWidth = jQsiteChromeHeader.find('.navbar-right').width();

    initialBodyOverflowY = jQ('body').css('overflow-y') || 'visible';

  //  addAriaExpandedAttributeToSearchBox();
  //  verticallyPositionSearchbar();

    checkMobileBreakpoint();
    jQ(window).resize(checkMobileBreakpoint);

    // Show header nav. It has opacity set to 0 initially to prevent a flash of desktop styling on mobile.
    jQsiteChromeHeader.find('nav').css('opacity', 1);
  });
})(jQuery);

/*
jQ(function addAriaExpandedAttributeToSearchBox() {
  jQ('.searchbox').attr('aria-expanded', 'false');
})(jQuery);*/


function mobileMenuToggle() {
  if (jQsiteChromeMobileMenu.hasClass('active')) {
    closeMobileMenu();
  } else {
    openMobileMenu();
  }
}

function openMobileMenu() {
  jQsiteChromeMobileMenu.addClass('active');
  jQsiteChromeMobileMenu.attr('aria-expanded', 'true');
  // Disable body from scrolling while menu is open
  jQ('body').css('overflow-y', 'hidden');
  mobileLanguageSwitcher(jQ('.mobile-language-dropdown'));
}

function closeMobileMenu() {
  jQsiteChromeMobileMenu.removeClass('active');
  jQsiteChromeMobileMenu.attr('aria-expanded', 'false');
  jQ('body').css('overflow-y', initialBodyOverflowY);
}

function mobileLanguageSwitcher(jQdiv) {
  jQdiv.children('.mobile-language-dropdown-title').click(function() {
    jQdiv.children('.mobile-language-dropdown-options').slideToggle('fast');
    // Scroll down as the dropdown options div appears
    jQ('.mobile-menu').animate({
      scrollTop: jQ('.mobile-language-dropdown-options').offset().top
    }, 'fast');
  });
}

function toggleCollapsibleSearch(self) {
  var jQsearchbox = jQ(self).siblings('.searchbox');
  jQsearchbox.toggleClass('expanded');
  jQsearchbox.find('input').focus();

  if (jQsearchbox.hasClass('expanded')) {
    jQsearchbox.attr('aria-expanded', 'true');
  }

  // Close searchbox on click outside of box
  jQ(document).mouseup(function(e) {
    if (!jQsearchbox.is(e.target) && jQsearchbox.has(e.target).length === 0) {
      jQsearchbox.removeClass('expanded');
      jQsearchbox.attr('aria-expanded', 'false');
    }
  });

  // Close searchbox on ESCAPE key
  jQ(document).keyup(function(e) {
    if (e.keyCode === 27) {
      jQsearchbox.removeClass('expanded');
      jQsearchbox.attr('aria-expanded', 'false');
    }
  });
}

function toggleCollapsibleByKeypress(event) {
  // 13 === ENTER, 32 === SPACE
  if ([13, 32].includes(event.which)) {
    var clickEvent = new Event('click', { 'bubbles': true });
    event.target.dispatchEvent(clickEvent);
  }
}

// Button appears only if text has been entered.
function toggleSearchButton(self) {
  var jQsearchButton = jQ(self).closest('form').find('.search-button');
  if (self.value !== '') {
    jQsearchButton.fadeIn(50);
  } else {
    jQsearchButton.fadeOut(50);
  }
}

/**
 * Browsers like IE11 don't understand nested calc commands, which are used to position the searchbar
 * due to vertically aligning it with the dynamically sized logo.
 * Instead, we need to position it with javascript.
 */
/*function verticallyPositionSearchbar() {
  var isMSIE = navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0;
  var isSafari = navigator.userAgent.indexOf('Safari') !== -1;
  if (isMSIE || isSafari) {
    var jQsearchbox = jQ('header#site-chrome-header .collapsible-search .searchbox');
    var jQbanner = jQsiteChromeHeader.find('.banner');
    var positionTop = jQbanner.height() / 2 - jQsearchbox.height() / 2;

    jQsearchbox.css('top', positionTop);
  }
}
*/

/**
 * Check if the header should enter mobile mode based on the width of the navLinks
 * and the available width of the navbar based on the user's window size.
 */
function checkMobileBreakpoint() {
  var roomForNavLinks;
  if (siteChromeTemplate === 'evergreen') {
    var logoWidth = jQsiteChromeHeader.find('a.logo').width();
    var headerContentWidth = jQsiteChromeHeader.find('.header-content').width();
    var headerPadding = 26; // px
    roomForNavLinks = headerContentWidth - logoWidth - navbarRightWidth - headerPadding;

    if (navLinkFullWidth > roomForNavLinks) {
      showMobileHeaderNav();
    } else {
      showDesktopHeaderNav();
    }
  } else if (siteChromeTemplate === 'rally') {
    var rallyBottomWidth = jQsiteChromeHeader.find('.rally-bottom').width();
    roomForNavLinks = rallyBottomWidth - navbarRightWidth;

    var jQrallyTop = jQsiteChromeHeader.find('.rally-top');
    var roomForRallyTopContent = jQrallyTop.width();
    var rallyTopContentWidth =
      jQrallyTop.find('a.logo').width() +
      jQrallyTop.find('div.searchbox').width() +
      16; // padding

    if (navLinkFullWidth > roomForNavLinks || rallyTopContentWidth > roomForRallyTopContent) {
      showMobileHeaderNav();
    } else {
      showDesktopHeaderNav();
    }
  }

  // Undo initial hidden styling to hide searchbox during width calculations.
  // Prevents "flashing" of non-mobile search when on mobile.
  jQsiteChromeHeader.find('div.searchbox.hidden').removeClass('hidden');
}

function showDesktopHeaderNav() {
  // Hide mobile nav
//jQsiteChromeHeaderMobileNav.css('display', 'none');
//  jQsiteChromeHeaderMobileNav.attr('aria-hidden', 'true');
//  jQsiteChromeHeader.find('.mobile-menu').attr('aria-hidden', 'true').attr('hidden', 'true');
  // Close mobile menu if it is open
//  if (jQsiteChromeHeader.find('.mobile-menu').hasClass('active')) {
  //  closeMobileMenu();
//  }
  // Show desktop nav
  jQsiteChromeHeaderDesktopNav.css('display', 'block');
//  jQsiteChromeHeader.find('.rally-top .searchbox').show();
  jQsiteChromeHeaderDesktopNav.attr('aria-hidden', 'false');
}

function showMobileHeaderNav() {
  // Hide desktop nav
  jQsiteChromeHeaderDesktopNav.css('display', 'none');
  jQsiteChromeHeaderDesktopNav.attr('aria-hidden', 'true');
  // Show mobile nav
//  jQsiteChromeHeaderMobileNav.css('display', 'block');
  jQsiteChromeHeader.find('.rally-top .searchbox').hide();
  jQsiteChromeHeaderMobileNav.attr('aria-hidden', 'false');
  jQsiteChromeHeader.find('.mobile-menu').attr('aria-hidden', 'false').removeAttr('hidden');
}
;
// Stolen and butchered from styleguide.

var dropdownFactory = function(element) {
  if (!element) { return; }
  this.dropdowns = Array.prototype.slice.call(element.querySelectorAll('[data-dropdown]'));
  this.dropdowns.forEach(function(dropdown) {
    new Dropdown(dropdown);
  });
};

var Dropdown = function(element) {
  this.dd = element;
  this.orientation = this.dd.getAttribute('data-orientation') || 'bottom';
  this.selectable = this.dd.hasAttribute('data-selectable');

  this.dd.classList.add('dropdown-orientation-' + this.orientation);

  // Set the 'role' and 'aria-expanded' attributes for better ADA/508 compliance.
  this.dd.setAttribute('role', 'button');
  this.dd.setAttribute('aria-expanded', 'false');

  this.placeholder = this.dd.querySelector('span');
  this.opts = Array.prototype.slice.call(this.dd.querySelectorAll('.dropdown-options > li'));

  this.dd.dataset.value = '';
  this.dd.dataset.index = -1;

  this.initEvents();
};

Dropdown.prototype = {
  initEvents: function() {
    var obj = this;
    // Reposition dropdown if it's near the edge of the window to avoid
    // the dropdown making the window larger than we wanted
    positionDropdown();

    obj.dd.addEventListener('click', function() {
      positionDropdown();
      obj.dd.classList.toggle('active');

      if (obj.dd.classList.contains('active')) {
        obj.dd.setAttribute('aria-expanded', 'true');
      } else {
        obj.dd.setAttribute('aria-expanded', 'false');
      }

      return false;
    });

    if (obj.selectable) {
      obj.opts.forEach(function(opt) {
        opt.addEventListener('click', function(event) {
          event.preventDefault();

          var node = opt;
          var index = 0;

          while ((node = node.previousElementSibling) !== null) {
            index++;
          }

          obj.dd.dataset.value = opt.textContent;
          obj.dd.dataset.index = index;

          obj.placeholder.innerHTML = opt.innerText.trim();

          return false;
        });
      });
    }

    document.addEventListener('click', function(event) {
      var node = event.target;
      while (node.parentElement && !node.classList.contains('dropdown')) {
        node = node.parentElement;
      }

      if (node !== obj.dd) {
        obj.dd.classList.remove('active');
      }
    });

    window.addEventListener('resize', function() {
      positionDropdown();
    });

    function positionDropdown() {
      var optionsElement = obj.dd.querySelector('.dropdown-options');
      var optionsElementWidth = optionsElement.offsetWidth;
      var windowWidth = document.body.offsetWidth;

      // Get left to check if the dropdown options are hanging off the side of the page
      var node = optionsElement;
      var left = 0;

      do {
        left += node.offsetLeft;
      } while ((node = node.offsetParent) !== null);

      // Update dropdown options position if needed
      if (optionsElementWidth + left >= windowWidth || optionsElement.style.left) {
        var dropdownWidth = obj.dd.getBoundingClientRect().width;
        optionsElement.style.left = -(optionsElementWidth - dropdownWidth) + 'px';
      }
    }
  }
};

(function (jQ) {
  if (!jQ) {
    return;
  }

  jQ(function() {
    dropdownFactory(document.querySelector('#site-chrome-header'));
    dropdownFactory(document.querySelector('#site-chrome-footer'));
  });
})(jQuery);
// This is a manifest file that'll be compiled into application.js, which will include all the files
// listed below.
//
// Any JavaScript/Coffee file within this directory, lib/assets/javascripts, vendor/assets/javascripts,
// or any plugin's vendor/assets/javascripts directory can be referenced here using a relative path.
//
// It's not advisable to add code directly here, but if you do, it'll appear at the bottom of the
// compiled file.
//
// Read Sprockets README (https://github.com/rails/sprockets#sprockets-directives) for details
// about supported directives.
//


/**
  NOTE! this file is only included for the default site chrome case. There is also a *custom* site chrome
  option for a very limited number of customers.

  If you add new files to the tree, or require new node_modules, you ALSO need to manually add those items
  to the custom site chrome case, if applicable.
  Do this in the `site_chrome_javascript_tag` method in lib/site_chrome_consumer_helpers.rb
*/
