<?php
// This file defines a short code to generate the carta interactive map
//


function build_custom_map_func() {
    ob_start();
	?>
<script>
  var map;
  // Create a new blank array for all the brewery markers.
  var markers = [];
  function initMap() {
    var myLatlng = {lat: 35.1054925422785, lng: -106.647530693516};
    // Constructor creates a new map - only center and zoom are required.
    map = new google.maps.Map(document.getElementById('map'), {
      center: myLatlng,
      zoom: 11,
      mapTypeControl: true
    });
  }
</script>
<div id="map">
  <p>Something should be here</p>
</div>
	<!--// Using Stephen's Google MAPs API KEY. Need to find a new one.-->
	<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyC7g2GaTrXHzZtxNsoZtRXt3ls0Rfx3UBM&v=3&callback=initMap"></script>
	<?php
	return ob_get_clean();
}

add_shortcode( 'cartamap', 'build_custom_map_func' );



?>