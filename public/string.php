<?php

namespace {

    if (!function_exists('str_starts_with')) {
        function str_starts_with($haystack, $needle) {
            return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
        }
    }
    if (!function_exists('str_ends_with')) {
        function str_ends_with($haystack, $needle) {
            return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
        }
    }
    if (!function_exists('str_contains')) {
        function str_contains($haystack, $needle) {
            return $needle !== '' && mb_strpos($haystack, $needle) !== false;
        }
    }

    if (!function_exists('bool_to_str')) {
        function bool_to_str($bool = false) {
            return ($bool === true) ? 'true' : 'false';
        }
    }
    if (!function_exists('bool_to_int')) {
        function bool_to_int($incoming = false) {
            if (!is_bool($incoming)) return $incoming;
            return ($incoming === true) ? 1 : 0;
        }
    }
    if (!function_exists('str_to_bool')) {
        function str_to_bool($incoming = '0') {
            if (is_bool($incoming)) return $incoming;
            return ($incoming === '1');
        }
    }

    /*

        echo element('div', [ 'class'=>'hello' ], 'Hello World');
        echo element('div', 'Hello World', [ 'class'=>'hello' ]);
        echo element('h1', get_the_title());

    */
    function str_element($tag, $param2=null, $param3=null) {
        
        $attributes     = [];
        $content        = '';

        if (is_array($param2)) {
            $attributes = $param2;
            $content    = (string) $param3;
        } elseif ( is_string($param2) || is_numeric($param2)) {
            $content    = (string) $param2;
            if (is_array($param3)) {
                $attributes = $param3;
            }
        }

        $attributes_str = '';
        if (!empty($attributes)) {
            $attributes_str = ' ' . implode(' ', array_map( fn($k, $v) => sprintf('%s="%s"', esc_attr($k), esc_attr($v)), array_keys($attributes), $attributes) );
        }

        return sprintf('<%1$s%2$s>%3$s</%1$s>', esc_html($tag), $attributes_str, esc_html($content));

    }
    if (!function_exists('element')) {
        function element($tag, $param2=null, $param3=null) {
            return str_element($tag, $param2, $param3);
        }
    }
         

}
