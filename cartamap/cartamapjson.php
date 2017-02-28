<?php

class CartaMapJsonGenerator{
    
    private $jsonfile;
    
    function __construct(){
        
    }
    
    public function createjsonFile(){

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
          return;
        }

        $args = array('post_type' => 'custom_map');

        $map_points = get_posts($args);
                
        $jsonFile .=
        '{
        "markers": [
        ';
        
        foreach($map_points as $map_point){
                
                $jsonFile .= '
            {
                "ID" : "' . $map_point->ID . '",
                "name" : "' . $map_point->post_title . '",
                "category" : "' . $map_point->post_category[0] . '",
                "content" : "' . $map_point->post_content . '",
                "address" : "' . get_post_meta($map_point->ID, 'map_location_address', true) . '",
                "state" : "' . get_post_meta($map_point->ID, 'map_location_state', true) . '",
                "city" : "' . get_post_meta($map_point->ID, 'map_location_city', true) . '",
                "zipcode" : "' . get_post_meta($map_point->ID, 'map_location_zipcode', true) . '",
                "location" :{
                    "lat" : "' . get_post_meta($map_point->ID, 'map_location_lat', true) . '",
                    "long" : "' . get_post_meta($map_point->ID, 'map_location_lng', true) . '"
                }
            }';
                if(end($map_points)->ID !== $map_point->ID ){
                    $jsonFile .= ',';
                }
        };
    $jsonFile .='
        ]
    }';

        file_put_contents(dirname(__FILE__) . '/cartamap.json', $jsonFile);
        
         
    }
        
}

?>>