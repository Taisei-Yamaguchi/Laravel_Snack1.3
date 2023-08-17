<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Snack;
use App\Models\Like;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $items=Member::all();
        return view('member.index',['items'=>$items]);
    }



    
//to New Registration Screen
    public function add(Request $request)
    {
        //登録確認画面から戻った場合、アップデートされたimageを削除する処理を入れる。
        return view('member.register_index');
    }



//add New Member
//2023.2.26add 処理：メールのダブりを防ぐ。パスワードの二重確認、name,mail,passwordは必須にする。
//もっとコンパクトに？？？
    public function create(Request $request)
    {   
        //メールアドレスのチェック状況に関わらず、ruleは調べる。
        $this->validate($request,Member::$rules);
        $form=$request->all();
        unset($form['_token']);
        $mail_check =Member::where('mail',$form['mail'])
        ->first();
        //初めに配列を用意
        $member=new Member;
        $error=array();

        if(isset($mail_check)){
            $error['mail']="このメールアドレスは使われています。";
        }
        if($form['pass']!=$form['pass2']){
            $error['pass']="確認用パスワードと一致しません";
            //これリアルタイムで出したい
        }
        //2023.2.27 画像ファイルの名前を抽出して保存。ファイル自体もmoveメソッドで保存。
        if(isset($form['image'])){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();

            if($extension =='jpg' || $extension=='png' || $extension=='gif' || $extension=='jfif'
                ||$extension=='JPG'||$extension=='TIFF'||$extension=='PNG'||$extension=='GIF'
                ||$extension=='JPEG'||$extension=='tiff'){
                $original=$file->getClientOriginalName();
                $filename=date('Ymd_His').'_'.$original;
                $form['image']=$filename;
            }
            else{
                $error['image']="不適切なファイルです";
            }
        }

        if(empty($error)){
            if(isset($file)){
                $file->move('../storage/app/public/member_images',$filename);
            }
            $member->fill($form)->save();
            return view('member.register');
        }else{
            //flashとoldでフォームに入力してたものを一時的に記憶させる
            $request->flash();
            return view('member.register_index',
            ['error_others'=>$error],
        );
        }
    }



//to Member Delete Screen
    public function delete_index(Request $request)
    {
        $ses=$request->session()->all();
        return view('member.delete_index',[
            'member'=>$ses,
        ]);
    }



//delete Member
    public function delete(Request $request)
    {
        $member_id=$request->member_id;

        $member=Member::where('id',$member_id)->delete();
        //このメンバーがリコメンドしたスナックの情報も消す。
        $items=Snack::where('member_id',$member_id)->delete();
        //このメンバーがイイネした情報も消す
        $likes=Like::where('member_id',$member_id)->delete();
        //メンバーの写真も消す
        Storage::disk('public')->delete('member_images/'.$request->session()->get('image'));

        return view('main.index');
    }

    


//to Member Edit Screen 
        public function edit_index (Request $request)
        {
            $ses=$request->session()->all();
            return view('member.edit_index',[
            'member'=>$ses,
        ]);
        }




//edit member　改良の余地あり？？？？？
//2023.4.6 画像ファイルが選択されなければ、$request->imageを取り出さないようにする。
//↑後でやる
        public function edit (Request $request)
        {
            $this->validate($request,Member::$rules_edit);
            $form=$request->all();
            unset($form['_token']);
            $member=Member::find($request->id);

            $current_image=$request->session()->get('image');
            
            //初めに配列を用意
            $error=array();
            //2023.2.27 画像ファイルの名前を抽出して保存。ファイル自体もmoveメソッドで保存。
            if(isset($form['image'])){
                $file=$request->file('image');
                $extension=$file->getClientOriginalExtension();

                if($extension=='jpg' || $extension=='png' || $extension=='gif' ||$extension=='jfif'
                ||$extension=='JPG'||$extension=='TIFF'||$extension=='PNG'||$extension=='GIF'
                ||$extension=='JPEG'||$extension=='tiff'){
                    $original=$file->getClientOriginalName();
                    $filename=date('Ymd_His').'_'.$original;
                    $form['image']=$filename;
                }
                else{
                    $error['image']="不適切なファイルです";
                }
            }else{
                $form['image']=$current_image;
            }

            if(empty($error)){
                if(isset($file)){
                    $request->session()->put('image',$filename);
                    //ファイルのアップロード
                    Storage::disk('public')
                    ->putFileAs('member_images',$file,$filename);
                    //既存ファイルの削除
                    Storage::disk('public')
                    ->delete('member_images/'.$current_image);
                }
                $request->session()->put('name',$form['name']);
                $member->fill($form)->save();
                return view('member.edit');
            }else{
                return view('member.edit_index',
                [
                    'error_others'=>$error,
                    'member'=>$member,
                ]);
            }
        }




//to Password Change Screen
        public function pass_change_index (Request $request)
        {
            $ses=$request->session()->all();
            return view('member.pass_change_index',[
            'member'=>$ses,
        ]);
        }



//change password
        public function pass_change (Request $request)
        {
            $this->validate($request,Member::$rule_pass_change);
            $form=$request->all();
            unset($form['_token']);
            $ses_id=$request->session()->get('id');
            $member=Member::find($ses_id);
            $error=array();

            $pass=$member->where('id',$ses_id)->first('pass');

            if($form['current_pass']!=$pass['pass']){
                $error['pass1']="現在のパスワードが違います。忘れた場合は管理人に連絡を。";
            }
            
            if($form['pass']!=$form['pass2']){
                $error['pass2']="確認用パスワードと一致しません";
                //これリアルタイムで出したい
            }

            if(empty($error)){
                $member->fill($form)->save();
                return view('member.pass_change');
            }else{
                return view('member.pass_change_index',
                ['error_others'=>$error],
            );
            }
        }

}
