<?php

namespace App\Http\Controllers;

use App\Function\LikeFunction;
use App\Models\Like;
use App\Models\Snack;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function index(Request $request){
        $items=Like::all();
        return view('like.index',['items'=>$items]);
    }



// //add or delete 'like'
//     //イイネ同期処理　は使わない
//     public function like_add_delete (Request $request){
//         //Middlewareを後処理にしないと、イイネ処理後にsuggestitemsを取得できないか？
//         //like処理ミドルウェアで行った後にここに来る。
//         return back()->withInput();
//     }




    //2023.6.5　うまくいったのでこっちをメインに使っていく。
    //イイネ非同期処理 jquery を使ったときの処理
    public function likes(Request $request)
    {
        $ses=$request->session()->all();  //1.member情報をsessionから取得
        $snack_id = $request->snack_id; //2.snack_idの取得
        $already_liked = Like::where('member_id', $ses['id'])->where('snack_id', $snack_id)->first(); //3.

        if (!$already_liked) { //もしこのユーザーがこのsnackにまだいいねしてなかったら
            $like = new Like; //4.Likeクラスのインスタンスを作成
            $like->snack_id = $snack_id; //Likeインスタンスにsnack_id,member_idをセット
            $like->member_id = $ses['id'];
            $like->save();
            $item=Snack::where('id',$snack_id)->increment('likes_cnt'); //そのsnackのlikes_cntを1増やす
        } else { //もしこのユーザーがこのsnackに既にいいねしてたらdelete
            Like::where('snack_id', $snack_id)->where('member_id', $ses['id'])->delete();
            $item=Snack::where('id',$snack_id)->decrement('likes_cnt'); //そのsnackのlikes_cntを1減らす
        }

        //snacksのlikes_cntは正常に処理されることを確認。
        //likesにも非同期でイイネのデータが保存されている
        //5.この投稿の最新の総いいね数を取得
        $snack= Snack::where('id',$snack_id)->first();
        $snack_likes_cnt=$snack['likes_cnt'];
        $param = [
            'snack_likes_cnt' => $snack_likes_cnt,
        ];
        return response()->json($param); //6.JSONデータをjQueryに返す
    }

}
