<?php

use App\Models\language;
use Illuminate\Support\Facades\Config;

function get_languages(){
   return language::active() -> select() -> get();
}

function get_default_language(){
    return Config::get('app.locale');
}

function uploadImage($folder,$image){
    $image->store('/',$folder);
    $filename = $image->hashName();
    $path = 'images/'.$folder.'/'.$filename;
    return $path;
}
