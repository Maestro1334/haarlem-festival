// Scrolls to the sponsor section after clicking the down arrow on header
$("#scroll-arrow").click(function() {
  $('html,body').animate({
    scrollTop: $("#sponsors").offset().top - 80
  }, 'slow');
});



/* Google maps implementation*/
let map;
let markersArray = [];
// URL for all marker types
let iconBaseURL = window.location.origin;
if (window.location.origin == 'http://localhost') {
  iconBaseURL += '/haarlem_festival/img';
} else {
  iconBaseURL += '/img';
}

const markerIcons = {
  FOOD: {
    url: iconBaseURL + '/page/food_marker.png'
  },
  DANCE: {
    url: iconBaseURL + '/page/dance_marker.png'
  },
  JAZZ: {
    url: iconBaseURL + '/page/jazz_marker.png'
  },
  HISTORIC: {
    url: iconBaseURL + '/page/historic_marker.png'
  }
}
// Get the locations from the hidden input
let eventLocations = $('input[name=eventLocations]').val();
eventLocations = JSON.parse(eventLocations);


function initMap() {
  let haarlem = { lat: 52.379490, lng: 4.637720 };
  // Creating the map
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 15,
    center: haarlem
  });

  // Filling the legend
  var legend = document.getElementById('legend');
  for (let icon in markerIcons) {
    let name = icon.toLowerCase();
    let iconURL = markerIcons[icon].url;
    let div = document.createElement('div');
    div.innerHTML = '<img src="' + iconURL + '"> ' + name;
    legend.appendChild(div);
  }

  // Adding the legend
  map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

  // Adding all the locations
  eventLocations.forEach(location => {
    addMarker({ lat: parseFloat(location.lat), lng: parseFloat(location.lon) }, location.category);
  });
}

function addMarker(latLng, category) {
  let icon = markerIcons[category] || {};

  let marker = new google.maps.Marker({
    map: map,
    position: latLng,
    icon: {
      url: icon.url,
      scaledSize: new google.maps.Size(35, 53)
    }
  });

  markersArray.push(marker);
}

/* Slider */
$(document).ready(function() {
  $('.sponsor-logos').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 2000,
    arrows: false,
    dots: false,
    pauseOnHover: false,
    responsive: [{
      breakpoint: 768,
      settings: {
        slidesToShow: 4
      }
    }, {
      breakpoint: 520,
      settings: {
        slidesToShow: 3
      }
    }]
  });
});