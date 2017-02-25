<?php
// This file defines a short code to generate the carta interactive map
//
add_shortcode( 'cartamap', 'build_custom_map_func' );
add_action( 'admin_enqueue_scripts', 'cartamaps_enqueue_assets' );
function build_custom_map_func() {
	ob_start();
	?>
	
	<div id='map'></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7g2GaTrXHzZtxNsoZtRXt3ls0Rfx3UBM&v=3&callback=initMap" async defer></script>
	<script type='text/javascript'>
	var map;
	function initMap() {
	  map = new google.maps.Map(document.getElementById('map'), {
	    center: {lat: 31.772543, lng: -106.460953},
	    scrollwheel: false,
	    zoom: 6
	  });
	}
	</script>

	<?php
	$output = ob_get_clean();
	return $output;
}
?>