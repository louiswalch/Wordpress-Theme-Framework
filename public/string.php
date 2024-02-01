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


}
