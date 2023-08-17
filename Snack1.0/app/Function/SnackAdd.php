<?php
namespace App\Function;

use App\Function\ImageFunction;
use App\Models\Snack;
use Illuminate\Support\Facades\Facade;

//Not use now
class SnackAdd extends Facade
{
    public static function add($form)
    {
        //2023.2.28 画像ファイルの名前を抽出して保存。ファイル自体もmoveメソッドで保存。
        if(isset($form['image'])){
            $file=$request->file('image');
            $image_check=ImageFunction::image_check($file);
            $form['image']=$image_check['filename'];
            $errors['image']=$image_check['errors_image'];
        }

        //最後に、errorがなければ、ファイルのアップロード、データベースへの保存を行う。
        if(empty($errors)){
            $form['keyword']=$form['keyword'].','.$form['name']
            .','.$form['company'].','.$form['type'].','
            .$form['country'].','.$form['coment'];

            if(isset($file)){
                $file->move('snack_images',$filename);
            }
            $item->fill($form)->save();
        }//errorがあれば、表示。ここでは、error_othersはすでに配列。

        return $errors;
    }
}