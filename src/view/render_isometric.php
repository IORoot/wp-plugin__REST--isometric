<?php

namespace andyp\isometricrest\view;



class render_isometric
{

    public $posts;

    public $result;


    public function __construct($posts)
    {
        $this->posts = $posts;
    }



    public function render()
    {
        if (!isset($this->posts)){
            return;
        }

        shuffle($this->posts);

        $output = '<ul class="labs grid grid-cols-6 grid-rows-4 gap-4 iso-2">';

        foreach($this->posts as $key => $post )
        {
            // 1 = 'one', 12 = 'twelve'
            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
            $id = $f->format($key);
            $zebra = ($key++%2==1) ? 'odd' : 'even';

            $output .= '<li class="'.$id.' '.$zebra.' w-full h-40 bg-smoke">';
                $output .= '<div class="w-full h-full mb-2">';
                    $output .= '<img class="lazyload object-cover h-full" src="'.$post->imageURL.'" alt="'.$post->title->rendered.'" width="'.$post->imageWidth.'" height="'.$post->imageHeight.'">';
                $output .= '</div>';
            $output .= '</li>';
        }

        $output .= '</ul>';

        return $output;
    }


}