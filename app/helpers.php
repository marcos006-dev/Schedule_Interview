<?php

use Illuminate\Support\Facades\File;

if (!function_exists('loadJson')) {
    function loadJson($route) {
      $jsonLoaded = File::get($route);
      return json_decode(cleanJson($jsonLoaded), true);
    }
}

if (!function_exists('cleanJson')) {
    function cleanJson($json) {
        return preg_replace('/,\s*([\]}])/m', '$1', $json);
    }
}