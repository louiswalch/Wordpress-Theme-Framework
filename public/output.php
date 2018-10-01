<?php

namespace {

    // ---------------------------------------------------------------------------
    // Protect email addresses from bots.

    function safemail($email, $text = '', $title = '', $class = '') {
        
        // Make email address as default text
        $text   = $text == '' ? $email : $text; 

        return '<a href="'.safemail_encode('mailto:'.$email).'" '.($title ? 'title="'.safemail_encode($title).'"' : '').' '.($class ? 'class="'.$class.'"' : '').' style="unicode-bidi:bidi-override;direction:rtl">'.strrev($text).'</a>';
    }

    function safemail_encode($text) {
        $output = '';
        for ($i = 0; $i < strlen($text); $i++) { 
            $output .= '&#'.ord($text[$i]).';'; 
        } 

        return $output;
    }


    // ---------------------------------------------------------------------------

    function widont($str = '') {
        $str = rtrim($str);
        $space = strrpos($str, ' ');
        if ($space !== false) {
            $str = substr($str, 0, $space).'&nbsp;'.substr($str, $space + 1);
        }
        return $str;
    }


    // ---------------------------------------------------------------------------

    function format_plain_text($string) {

        $string = strip_tags($string);
        $string = str_replace('"', '', $string);
        $string = preg_replace( "/\r|\n/", "", $string);

        return $string;

    }

    function get_excerpt($field = 'snippet', $count=90, $settings=array()){

        global $post;

        $settings = array_merge(array(
            'read_more'             => true,
            'read_more_link'        => true,
            'p'                     => true,
            'elipsis'               => true,
            ), $settings);


        $usingsnippet = false;

        if (get_field($field, $post->ID)) {
            // Use custom field value for snippet text
            $excerpt                = get_field($field, $post->ID);
            // Shouldn't have an elipsis when using custom snippet
            $settings['elipsis']    = false;
            //set flag so we can override the <p> wrapper on snippets. 
            $usingsnippet = true;
        } else {

            // Use post content for snippet text
            $excerpt                = get_the_content();
            $excerpt                = strip_tags($excerpt);

            // Ensure that it aligns with the expected size
            $excerpt = substr($excerpt, 0, $count);
            $excerpt = substr($excerpt, 0, strripos($excerpt, " "));

        }

        if ($settings['elipsis'] === true) {
            $excerpt .= '...';
        }

        if ($settings['read_more'] === true) {

            if ($settings['read_more_link'] === true) {
                $excerpt .= ' <a class="ibm-bold" href="'. get_permalink($post->ID) .'">Read More</a>';
            } else {
                $excerpt .= ' <strong class="read_more">Read More</strong>';
            }

        }  

        //only add <p> tag if settings are for p AND this isn't using the snippet
        if ($settings['p'] === true && !$usingsnippet) {
            $excerpt = '<p>' . $excerpt . '</p>';
        }

        // Go get 'em tiger
        echo $excerpt;

    }


    // ---------------------------------------------------------------------------
    // Cycle through echo values
    // http://baylorrae.com/creating-a-cycle-function-in-php

    /**
     * Cycles through each argument added
     * Based on Rails `cycle` method
     * 
     * if last argument begins with ":" 
     * then it will change the namespace
     * (allows multiple cycle calls within a loop)
     * 
     * @param mixed $values infinite amount can be added
     * @return mixed
     * @author Baylor Rae'
     */
    
    function cycle($first_value, $values = '*') {
      // keeps up with all counters
      static $count = array();
      // get all arguments passed
      $values = func_get_args();
      // set the default name to use
      $name = 'default';
      // check if last items begins with ":"
      $last_item = end($values);
      if( substr($last_item, 0, 1) === ':' ) {
        // change the name to the new one
        $name = substr($last_item, 1);
        // remove the last item from the array
        array_pop($values);
      }
      // make sure counter starts at zero for the name
      if( !isset($count[$name]) )
        $count[$name] = 0;
      // get the current item for its index
      $index = $count[$name] % count($values);
      // update the count and return the value
      $count[$name]++;
      return $values[$index];  
    }

    // ---------------------------------------------------------------------------
    // Super handy debugging shortcut.

    function pr($val, $title=false, $echo=true) {

        $tag    = 'pre';
        $title  = ($title ? '<strong>'.$title.' =</strong> ' : '');
        $start  = '<'.$tag.' style="font-size: 10px; background-color: white; color: black;">' . $title;
        $end    = '</'.$tag.'>';
        $value  = '';

        if (is_array($val)) {
            $value  = print_r($val, true);
        } else if (is_object($val)) {
            $value  = 'ERROR: The pr() helper does not work with Objects in "return" mode.';            
        } else if (is_bool($val)) {
            $value  = '(bool) ' . ($val ? 'true' : 'false');
        } else {
            $value  = $val;
        }
        
        if ($echo) {
            echo $start;
            if (is_object($val)) {
                var_dump($val);
            } else {
                echo $value;
            }
            echo $end;
        } else {
            return $start . $value . $end;
        }
    
    }

}