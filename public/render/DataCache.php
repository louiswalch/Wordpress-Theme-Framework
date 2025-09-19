<?php

namespace HelloFramework\Render;

use HelloFramework;

/*

DATA CACHE
Louis Walch / say@hellolouis.com

For documentation and examples of how to use this, visit:
https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.md

*/


class DataCache extends HelloFramework\Singleton {

    private $_upload_dir;
    private $_upload_url;


    // ------------------------------------------------------------

    public function __construct() {

        parent::__construct();
        
        $wp_uploads        = wp_upload_dir();
        $this->_upload_dir = $wp_uploads['basedir'] . '/cache';
        $this->_upload_url = $wp_uploads['baseurl'] . '/cache';
        
        if (!file_exists($this->_upload_dir)) wp_mkdir_p($this->_upload_dir);

    }

    private function _getFilePath($key) {
        return $this->_upload_dir . '/' . sanitize_file_name($key) . '.txt';
    }

    private function _getFileUrl($key) {
        return $this->_upload_url . '/' . sanitize_file_name($key) . '.txt';
    }


    // ------------------------------------------------------------
    // XXX:

    public function save($key, $source, $type='json') {

        $data = null;

        if (is_callable($source)) {
            $data = call_user_func($source);
        } elseif (is_string($source) && filter_var($source, FILTER_VALIDATE_URL)) {
            $data = @file_get_contents($source);
        } elseif (is_array($source)) {
            $data = $source;
            $type = 'json';
        } elseif (is_string($source)) {
            $data = $source;
            $type = 'text';
        }

        if ($data === null) return false;

        $payload = [
            'cached_at' => wp_date('Y-m-d h:i A'),
            'type'      => $type,
            'data'      => $type === 'json' && is_string($data) ? json_decode($data, true) : $data
        ];

        file_put_contents($this->_getFilePath($key), json_encode($payload));

        return $payload;

    }


    // ------------------------------------------------------------
    // XXX:

    public function load($key) {
        $file = $this->_getFilePath($key);
        if (!file_exists($file)) return false;
        $contents = file_get_contents($file);
        $payload = json_decode($contents, true);
        return $payload ?: false;
    }

    public function load_url($key) {
        $file = $this->_getFilePath($key);
        return file_exists($file) ? $this->_getFileUrl($key) : false;
    }

    public function load_data($key) {
        $payload = $this->load($key);
        return $payload ? $payload['data'] : false;
    }

    public function load_time($key) {
        $payload = $this->load($key);
        return $payload ? $payload['cached_at'] : false;
    }

}

