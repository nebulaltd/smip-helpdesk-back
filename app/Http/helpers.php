<?php

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

function storeOriginalImage($file, $folder = 'uploads')
{
    $path = $file->hashName('public/' . $folder);
    $serverPath = $file->hashName($folder);

    $image = Image::make($file);

    Storage::put($path, (string)$image->encode());

    return $serverPath;
}

function storeImage($file, $folder = 'uploads', $width = 870, $height = 1200)
{
    $path = $file->hashName('public/' . $folder);
    $serverPath = $file->hashName($folder);

    $image = Image::make($file);
    
    $image->fit($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });


    Storage::put($path, (string)$image->encode());

    return $serverPath;
}

function symImagePath()
{
    return "storage/";
}

function symImageFullPath()
{
    return config('url') . "/storage/";
}


function storeThumb($file,$folder = 'profile',$width = 120, $height = 120)
{

    $server = $folder."/" . md5(time() * time()) . ".jpeg";
    $path = "public/" . $server;

    $image = Image::make($file);
    $image->fit($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });

    Storage::put($path, (string)$image->encode());
    return $server;
}

function storeNotify($message){
    $notification = array(
        'message' => $message.' saved successfully!',
        'alert-type' => 'success'
    );

    return $notification;
}

function updateNotify($message){
    $notification = array(
        'message' => $message.' updated successfully!',
        'alert-type' => 'success'
    );

    return $notification;
}

function deleteNotify($message){
    $notification = array(
        'message' => $message.' deleted successfully!',
        'alert-type' => 'success'
    );

    return $notification;
}

function errorNotify($message){
    $notification = array(
        'message' => 'Whoops! '.$message.' failed!',
        'alert-type' => 'error'
    );

    return $notification;
}

function emailNotify($message){
    $notification = array(
        'message' => $message.' successfully!',
        'alert-type' => 'success'
    );

    return $notification;
}

function demoNotify($message){
    $notification = array(
        'message' => $message,
        'alert-type' => 'warning'
    );

    return $notification;
}

function mailNotify($message){
    $notification = array(
        'message' => $message.' successfully sent!',
        'alert-type' => 'success'
    );

    return $notification;
}

function mailWarning($message){
    $notification = array(
        'message' => $message,
        'alert-type' => 'error'
    );

    return $notification;
}