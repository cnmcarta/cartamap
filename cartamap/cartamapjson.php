<?php

class CartaMapJsonGenerator{
    
    private $jsonfile;
    
    function __construct(){
        
    }
    
    public function populate_text_array(){

        $args = array('post_type' => 'custom_map');

        $map_points = new WP_Query($args);

        
        if($map_points->have_posts()){

                $map_points->thepost();
                
        ?>
                
                <h1> <?php echo the_title(); ?></h1>
                <h1> <?php echo get_post_meta(get_the_ID(), 'map_location_media_id', true); ?></h1>
        
        <?php     
        }
        
    }
}

?>