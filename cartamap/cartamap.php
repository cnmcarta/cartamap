<?php
/*
Plugin Name: Carta Map
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin. This plugin demonstrates using custom templates in a plugin
Version: The Plugin's Version Number, e.g.: 1.0
Author: Name Of The Plugin Author
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
.
Any other notes about the plugin go here
.
*/


// This will contain all functions specifically for the map
function create_map_location_posttype() {
    register_post_type( 'custom_map',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Custom Map' ),
                'singular_name' => __( 'Custom Map' )
            ),
            'taxonomies' => array('category' , 'post_tag'),
            'supports' => array( 'title', 'editor', 'thumbnail', 'revisions'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'has_archive' => true,
            'menu_position' => 5,
            'rewrite' => array('slug' => 'custom_map'),
        )
    );
}

//Any time a new section is needed it must be added here using the add_meta_box function
function map_location_metaboxes() {
    // builds form elements for new map location/points.
    // add_meta_box(div id, Title, function called, post-type, context, priority);
    add_meta_box('carta-map-point', 'New Map Element' , 'carta_map_meta_box_data' , 'custom_map' , 'normal' , 'high');
    add_meta_box('map-media-meta', 'Pin Media', 'carta_map_meta_box_media_upload', 'custom_map', 'normal', 'low');
    add_meta_box('map-marker-maker', 'On-click Placer', 'carta_map_meta_box_marker_generate', 'custom_map' , 'normal' , 'low');
    
}

//hello
function carta_map_meta_box_data(){
    // this will populate all the data inside the metabox.
    //Get data from $post['field-name'][0]
    //use the post data in order to populate the fields by echoing into the field
    //include the html file for the fields
    //id of div needs to be same for add_meta_box(div id)

    global $post;
    $custom = get_post_custom($post->ID);
	$map_location_address = $custom['map_location_address'][0];
	$map_location_state = $custom['map_location_state'][0];
	$map_location_city = $custom['map_location_city'][0];
	$map_location_zipcode = $custom["map_location_zipcode"][0];
	$map_location_lat = $custom['map_location_lat'][0];
	$map_location_lng = $custom['map_location_lng'][0];
    $map_location_media = $custom['map_location_media_id'][0];
    $map_location_media_type = $custom['map_location_media_type'][0];
    
    include(dirname(__FILE__) . "/cartametabox.php");
    
}
/// Code to add markers to be passed to location arrays
include 'cartamapinput.php';
function carta_map_meta_box_marker_generate(){}

function carta_map_meta_box_media_upload(){
    global $post;
    
    //url will link to the page that allows for selecting media.  When called will open up a new selection interface
    $upload_url = esc_url(get_upload_iframe_src('image', $post->ID));
    
    //Retreive the media id if it already exists
    $media_id = get_post_meta($post->ID, 'map_location_media_id', true);
    
    //retreive the url of the media file if it exists
    $media_source = wp_get_attachment_url($media_id);
    ?>
    
    <div id="map-media-meta">

        <div class="uploaded-image">
        <?php
        if($media_source != null || $media_source != ""){
            echo '<img src="'.$media_source.'" alt="" style="max-width:100%;" />';
        } ?>
        </div>
        
        <button class="upload-file <?php if($media_source != null){ echo 'hidden'; } ?>">Select Media</button>
        <button class="delete-file <?php if($media_source == null){ echo 'hidden'; } ?>">Delete Media</button>
        <input type="hidden" name="map_location_media_id" id="upload-img-id"></input>
    </div>
    
<?php
    //includes the necessary js if this function is called
    //wp_enqueue_script(name(whatever you want to call it), location, code dependencies)
    wp_enqueue_script('mediaUpload', plugin_dir_url(__FILE__).'js/mediaUpload.js', array('jquery'));
}

function save_carta_map_meta_box_data(){
    
        // This will be what happens when the post is saved/updated
        
        //Get the post that will be changed
        global $post;
        //update_post_meta(post id, field to update, posted data from the field(this will be $_POST['name-of-field'])
        
        //save meta latlong boxes
        update_post_meta($post->ID, 'map_location_lat', $_POST['map_location_lat']);
        update_post_meta($post->ID, 'map_location_lng', $_POST['map_location_lng']);
        //save meta address boxes
        update_post_meta($post->ID, 'map_location_address', $_POST['map_location_address']);
        update_post_meta($post->ID, 'map_location_city', $_POST['map_location_city']);
        update_post_meta($post->ID, 'map_location_state', $_POST['map_location_state']);
        update_post_meta($post->ID, 'map_location_zipcode', $_POST['map_location_zipcode']);
        //save meta media box
        update_post_meta($post->ID, 'map_location_media_id', $_POST['map_location_media_id']);
}

function generate_carta_map_json_file(){

    //json file will be created from data in the wordpress database(all posts with id of 'custom_map')
    
    if(!class_exists('CartaMapJsonGenerator')){
    include(dirname(__FILE__) . '/cartamapjson.php');
    }
    $jsonGenerator = new CartaMapJsonGenerator();
    
    $jsonGenerator->createjsonFile();
    
}

add_action( 'init', 'create_map_location_posttype');

add_action('add_meta_boxes', 'map_location_metaboxes');
add_action('save_post', 'save_carta_map_meta_box_data');
add_action('save_post', 'generate_carta_map_json_file');

add_action('delete_post', 'generate_carta_map_json_file');

// include statement to register shortcode file.
//TODO: This include should be inside of a function so that it is only called when the function is called.
include'cartamapshortcode.php';

// script for google map API
add_action( 'admin_enqueue_scripts', 'cartamaps_enqueue_assets' );

function cartamaps_enqueue_assets() {
	wp_enqueue_script( 
	  'google-maps', 
	  'https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyC7g2GaTrXHzZtxNsoZtRXt3ls0Rfx3UBM&v=3&callback=initMap', 
	  array(), 
	  '1.0', 
	  true 
	);
}

// Register style sheet.
// taken from : https://codex.wordpress.org/Function_Reference/wp_register_style
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
/**
 * Register style sheet.
 */
function register_plugin_styles() {
	wp_register_style( 'cartamap', plugins_url( 'cartamap/css/style.css' ) );
	wp_enqueue_style( 'cartamap' );
}

?>