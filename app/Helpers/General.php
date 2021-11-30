<?php

use Illuminate\Support\Str;

function convertDate($date){
    return date('d M Y', strtotime($date));
}

if (! function_exists('shrinkText')) {
    function shrinkText($text) {
        return Str::limit($text, 225, '...');
    }
}

if (! function_exists('shrinkTitle')) {
    function shrinkTitle($text) {
        return Str::limit($text, 50, '...');
    }
}
