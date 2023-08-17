<?php
namespace App\Function;

use Illuminate\Support\Facades\Facade;
use App\Models\Like;
use App\Models\Snack;
class LikeFunction extends Facade
{


//イイネ処理を非同期通信で行うので、この共通関数は使わない。
//Check if the memer already 'like' the snacks or not.
//各itemsについて、likeしてるかをlike_checkする
    // public static function like_check ($member_id,$items)
    // {
    //     $like_check=array();
    //     foreach ($items as $item)
    //     {
    //         $like_check[$item->id]=Like::where('member_id',$member_id)
    //         ->where('snack_id',$item->id)
    //         ->first();
    //     }
    //     return $like_check;
    // }


//add or delete 'like'
//一つの機能として、共通関数化した。
//2023.3.9 likeを追加or削除
    public static function like_add_delete ($member_id,$snack_id)
    {
        //2023.3.1 まず、指定のlikeがあるかチェックする。
        $like_check=Like::where('member_id',$member_id)
        ->where('snack_id',$snack_id)
        ->first();

        if(!isset($like_check)){
            //'like' add
            //2023.3.1 　session_idがないとアクセスできないようにする。
            //Middlewareの利用。
            $like=new Like;
            $like->member_id=$member_id;
            $like->snack_id=$snack_id;
            $like->save();
            //2023.3.2 Snackテーブルにもlike_cntを加算する。
            $item=Snack::where('id',$snack_id)->increment('likes_cnt');
        }else{
            //'like' delete
            Like::where('member_id',$member_id)
            ->where('snack_id',$snack_id)
            ->delete();
            //2023.3.2 Snackテーブルにもlike_cntを減算する。
            $item=Snack::where('id',$snack_id)->decrement('likes_cnt');
        }
    }
   
}