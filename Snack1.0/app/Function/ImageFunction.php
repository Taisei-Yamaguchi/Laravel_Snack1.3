<?php
namespace App\Function;

use Illuminate\Support\Facades\Facade;

// Not use now
class ImageFunction extends Facade
{
    public static function image_check($file)
    {
        $extension=$file->getClientOriginalExtension();

        $filename="";
        $errors_image="";

        if($extension =='jpg' || $extension=='png' || $extension=='gif'){
            $original=$file->getClientOriginalName();
            $filename=date('Ymd_His').'_'.$original;
        }else{
            $errors_image="不適切なファイルです";
        }

        return [$filename,$errors_image];
    }
}