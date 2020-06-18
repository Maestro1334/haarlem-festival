$(document).ready(function() {
  // Get custom navbar scroll activation. Use if is set on page
  let customNavbarActivation = $('#customNavbarActivation');
  let scrollDistance = (customNavbarActivation.length == 1) ? parseInt(customNavbarActivation.val()) : 120;

  // Get custom navbar background object. Use if is set
  let navbarBackground = $('#enableNavbarBackground');
  if (navbarBackground.length == 1) {
    $('#navigation').addClass('colored-background');
  }

  let url = window.location.href;
  let baseURL = window.location.origin;
  if (window.location.origin == 'http://localhost') {
    baseURL += '/haarlem_festival';
  }
  let homeLinkItem = $('#navbarNav ul li').first();
  let homeBackupLink = $(homeLinkItem).children().data('linkbackup');
  if (url.includes(homeBackupLink) || url.includes(homeBackupLink.split('/')[1])) {
    homeLinkItem.addClass('active');
  }

  $('#navbarNav ul li a').each(function() {
    let redirect = this.href.split(baseURL).pop();
    let redirectParts = redirect.replace(/^\/+/, '').split('/');
    let linkURL = baseURL + '/' + redirectParts[0];

    if (url.includes(linkURL.replace(/\/$/, ''))) {
      // Check if its the homepage by comparing the 'controller' part of the url
      let splittedWindowURL = url.split(baseURL).pop().replace(/^\/+/, '').split('/').join(',').split('?').join(',').split(',');

      if (splittedWindowURL[0] == redirectParts[0]) {
        // It's no homepage
        // Check if it't a link with a hastag like the user icon which doesn't redirect
        const iterator = redirectParts.values();
        let hasHastag = false;
        for (const value of iterator) { if (value.includes('#')) { hasHastag = true; } }
        if (!hasHastag) {
          $(this).closest('li').addClass('active');
        }
      }
    }

  });

  $(document).on('scroll', function() {
    if ($(document).scrollTop() > scrollDistance) {
      $('#navigation').addClass('shrink');
    } else {
      $('#navigation').removeClass('shrink');
    }
  });

  //Show cookie modal if it hasn't shown before
  let allowCookies = getCookie('HaarlemFestival_Use_Cookies');
  if (allowCookies == null) {
    $('#cookieModal').modal('show');
  }

  $('#cookieModal #accept-cookies').on('click', function() {
    //Setting the cookie and closing the modal
    setCookie('HaarlemFestival_Use_Cookies', true, 30);
    $('#cookieModal').modal('hide');
  });
});

function setCookie(cname, cvalue, exdays) {
  let d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = 'expires=' + d.toUTCString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ";path=/";
}

function getCookie(name) {
  var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
  return v ? v[2] : null;
}