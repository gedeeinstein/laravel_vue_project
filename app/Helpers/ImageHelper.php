<?php
namespace App\Helpers;

use Intervention\Image\Facades\Image;

class ImageHelper
{
    public static function uploadImage($file, $fileName = null) {
        if(empty($file)){
            return null;
        }

        $image = $file;
        if(empty($fileName)){
            $image_name = time() . '.' . $file->getClientOriginalExtension();
        } else {
            $image_name = $fileName . '.' . $image->getClientOriginalExtension();
        }
        $directory = 'uploads/';
        $path = public_path($directory . $image_name);

        Image::make($image->getRealPath())->orientate()->save($path);

        return $directory . $image_name;
    }

    public static function removeImage($path){
        if(file_exists(public_path($path)) && !empty($path)){
            unlink(public_path($path));
        }
    }

    public static function updateImage($file, $path, $is_remove = "false", $fileName = null){
        if($is_remove == "true"){
            self::removeImage($path);
            return null;
        }

        if(empty($file)){
            return $path;
        }

        self::removeImage($path);
        return self::uploadImage($file, $fileName);
    }
}
