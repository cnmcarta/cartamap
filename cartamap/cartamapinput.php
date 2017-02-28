    <?php /*
    
      create a function in cartamap.php you will include this file(cartamapinput.php)
      add_meta_box(div id, Title, function called, post-type, context, priority) in map_location_metaboxes();
    
    */ ?>
    
   <div id="">
    
    <script type='text/javascript'>

      // In the following example, markers appear when the user clicks on the map.
      // The markers are stored in an array.
      // The user can then click an option to hide, show or delete the markers.
      var map;
      var markers = [];

      function initMap() {
        var caminoreal = {lat: 35.113281, lng: -106.621216};

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: caminoreal,
          mapTypeId: 'terrain'
        });

        // This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
          addMarker(event.latLng);
        });

        // Adds a marker at the center of the map.
        addMarker(caminoreal);
      }

      // Adds a marker to the map and push to the array.
      function addMarker(location) {
        var marker = new google.maps.Marker({
          position: location,
          map: map
        });
        markers.push(marker);
      }

      // Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows any markers currently in the array.
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7g2GaTrXHzZtxNsoZtRXt3ls0Rfx3UBM&v=3&callback=initMap">
    </script>
    
    </div> 