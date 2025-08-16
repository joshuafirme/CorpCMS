<?php

use App\Helper\Utils;

if (!function_exists('settings')) {
    function settings()
    {
        $path = "settings.json";

        if (Storage::exists($path)) {
            $data = Storage::get($path);
            $data = json_decode($data);
            return $data;
        } 
        else {
            $data['logo'] = '';
            $data['app_name'] = '';
            return json_encode($data);
        }
    }
}


function readPageContent($type) {
    $path = "page-content/$type.json";

    $data = null;

    if (Storage::exists($path)) {
        $data = Storage::get($path);
        $data = json_decode($data);
    }

    return $data;
}

function hexToRgb($hex, $alpha = false) {
    $hex      = str_replace('#', '', $hex);
    $length   = strlen($hex);
    $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
    if ( $alpha ) {
       $rgb['a'] = $alpha;
    }
    return $rgb;
 }