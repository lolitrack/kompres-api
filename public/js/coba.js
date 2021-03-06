// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
// 
// 
function initAutocomplete(){

    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: -6.271194, lng: 106.894547},
      zoom:13
    });

    var input = document.getElementById('searchInput');
    // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29),
    });

     autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = this.getPlace();
          if (!place.geometry) {
              window.alert("Autocomplete's returned place contains no geometry");
              return;
          }
          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
          } else {
              map.setCenter(place.geometry.location);
              map.setZoom(17);
          }
          marker.setIcon(({
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);
              var address = []
            var placeSearch, autocomplete;
            var componentForm = {
              street_number: 'long_name',
              route: 'long_name',
              administrative_area_level_1: 'long_name',
              administrative_area_level_2: 'long_name',
              administrative_area_level_3: 'long_name',
              administrative_area_level_4: 'long_name',
              country: 'long_name',
              postal_code: 'short_name'
            };

         
            for (var component in componentForm) {
              document.getElementById(component).value = "";
              document.getElementById(component).disabled = false;
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            console.log(place)
            for (var i = 0; i < place.address_components.length; i++) {
              var addressType = place.address_components[i].types[0];
              console.log(addressType)
              if (componentForm[addressType]) {
                 console.log(componentForm[addressType])
                var val = place.address_components[i][componentForm[addressType]];
                 console.log(val + "<br>")
                document.getElementById(addressType).value = val;
                address.push(place.address_components[i] && val);
              }
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);

            var lat = place.geometry.location.lat()
            var lng = place.geometry.location.lng()
            console.log(lat +" " + lng)
              document.getElementById('location').innerHTML = place.formatted_address;
            document.getElementById('lat').value = lat
            document.getElementById('lng').value = lng
     });
}
