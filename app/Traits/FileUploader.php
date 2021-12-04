<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


trait FileUploader
{

    /**
     * @param Request $request
     * @param string $path
     * @param string $key
     * @return string
     */
    public function upload(Request $request,$key,$path){
        //Read the file
        $file = $request->file($key);
        $originalName = $file->getClientOriginalName();
        $storedName=sha1(time().$originalName).'_'.str_replace(' ', '_', $originalName);
        //save the file to the configured public storage at config/filesystem
        $disk = Storage::disk('public');
        //save as stream
        $disk->put($path.'/'.$storedName,fopen($file, 'r+'));
        return $storedName;
    }


    /**
     * resize or crop image to create thumbnail from
     * reference: https://stackoverflow.com/questions/50451911/laravel-5-6-create-image-thumbnails
     * @param int $max_width
     * @param int $max_height
     * @param string $source_file full absolute path for the source file
     * @param string $dst_dir  full absolute path for the output file
     * @param int $quality
     */
    function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
        $imgSize = getimagesize($source_file);
        $width = $imgSize[0];
        $height = $imgSize[1];
        $mime = $imgSize['mime'];

        switch($mime){
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if($width_new > $width){
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        }else{
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);

        if($dst_img)imagedestroy($dst_img);
        if($src_img)imagedestroy($src_img);
    }


}