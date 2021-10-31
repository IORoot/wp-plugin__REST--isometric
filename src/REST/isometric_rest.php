<?php

namespace andyp\isometricrest\REST;

use \WP_REST_Request;

use \andyp\isometricrest\view\render_isometric;


class isometric_rest {

    public $posts;

    public $result;

    public $count    = 30;
    public $order    = 'date';
    public $posttype = 'pulse';
    public $category = 'pulse_category';
    public $cat_id   = 4;
    public $classes  = '';
    public $content  = '';
    public $endpoint = "https://parkourpulse.com/wp-json/wp/v2";


    public function set_count($count)
    {
        $this->count = $count;
    }

    public function set_order($order)
    {
        $this->order = $order;
    }

    public function set_posttype($posttype)
    {
        $this->posttype = $posttype;
    }

    public function set_classes($classes)
    {
        $this->classes = $classes;
    }

    public function set_category($category)
    {
        $this->category = $category;
    }

    public function set_cat_id($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function set_content($content)
    {
        $this->content = $content;
    }

    public function set_endpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }



    public function run()
    {
        $this->REST_call();
        
        
        
        if (empty($this->content)){
            $this->render_link();
        }

        if (!empty($this->content)){
            $this->render_content();
        }

        $this->iso = new render_isometric($this->posts);
        $this->result = $this->iso->render();
    }



    /**
     * https://rudrastyh.com/wordpress/rest-api-get-posts.html
     */
    private function REST_call()
    {
        // $transient = \get_transient( 'isometricrest-'.$this->posttype );

        if( ! empty( $transient ) ) { 
            $this->posts = json_decode($transient);
            return; 
        } 

        $response = \wp_remote_get( add_query_arg( array(
            'per_page' => $this->count,
            'orderby' => $this->order,
            $this->category => $this->cat_id,
        ), $this->endpoint.'/'.$this->posttype ) );


        if (is_wp_error($response)) { return; }
        
        $this->posts = json_decode( $response['body'] ); // our posts are here

        // \set_transient( 'isometricrest-'.$this->posttype, json_encode( $this->posts ), HOUR_IN_SECONDS );
    }




    public function render_link()
    {
        if (!isset($this->posts)){ return; }

        $output = '';

        foreach($this->posts as $key => $post )
        {
            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT); 
            $id = $f->format($key); // 1 = 'one', 12 = 'twelve'

            $output .= '<a target="_blank" href="'.$post->link.'">';
                $output .= '<img class="lazyload image-'.$id.' '.$this->classes.'" src="'.$post->imageURL.'" alt="'.$post->title->rendered.'" width="'.$post->imageWidth.'" height="'.$post->imageHeight.'">';
            $output .= '</a>';

        }

        $this->result = $output;
    }





    private function render_content()
    {
        if (!isset($this->posts)){ return; }

        $output = '';

        foreach($this->posts as $this->current_post )
        {
            $this->result = $this->replace_moustaches($this->content);
        }

    }



    private function replace_moustaches($content)
    {
        $this->new_content = json_decode(json_encode($content), true); // convert stdClass to arrays

        preg_match_all('/{{(.*?)}}/', $this->new_content, $moustaches);

        foreach ($moustaches[1] as $key => $field)
        {
            if (!property_exists($this->current_post, $field)) {
                continue;
            }
            
            if (is_array($this->current_post->$field)){
                $this->new_content = str_replace($moustaches[0][$key], $this->current_post->$field[0], $this->new_content);
                continue;
            }

            if (is_object($this->current_post->$field)){
                $this->new_content = str_replace($moustaches[0][$key], $this->current_post->$field->rendered, $this->new_content);
                continue;
            }

            $this->new_content = str_replace($moustaches[0][$key], $this->current_post->$field, $this->new_content);
            
            
        }

        return $this->new_content;
    }





    public function out()
    {
        return $this->result;
    }

}