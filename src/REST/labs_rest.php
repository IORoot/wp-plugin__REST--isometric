<?php

namespace andyp\labsstack\REST;

use \WP_REST_Request;


class labs_rest {

    public $posts;

    public $result;

    public $count    = 5;
    public $order    = 'date';
    public $posttype = 'tutorial';
    public $category;
    public $classes  = '';
    public $content;
    public $endpoint = "https://parkourlabs.com/wp-json/wp/v2";


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

    public function set_content($content)
    {
        $this->content = $content;
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

    }



    /**
     * https://rudrastyh.com/wordpress/rest-api-get-posts.html
     */
    private function REST_call()
    {
        $transient = \get_transient( 'labsrest-'.$this->posttype );
        if( ! empty( $transient ) ) { 
            $this->posts = json_decode($transient);
            return; 
        }

        $category_name = '';
        if (!empty($this->category)){
            $category_name = $this->posttype . '_category';
        }    

        $response = \wp_remote_get( add_query_arg( array(
            'per_page' => $this->count,
            'orderby' => $this->order,
            $category_name => $this->category,
        ), $this->endpoint.'/'.$this->posttype ) );


        if (is_wp_error($response)) { return; }
        
        $this->posts = json_decode( $response['body'] ); // our posts are here

        \set_transient( 'labsrest-'.$this->posttype, json_encode( $this->posts ), HOUR_IN_SECONDS );
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