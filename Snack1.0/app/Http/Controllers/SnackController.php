<?php

namespace App\Http\Controllers;

use App\Function\LikeFunction;
use App\Function\SnackSearch;
use App\Function\ImageFunction;
use App\Models\Snack;
use App\Models\Like;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;

class SnackController extends Controller
{

//
    public function index(Request $request)
    {
        $items=Snack::all();
        return view('snack.index',['items'=>$items]);
    }



//to Recommend Snacks Screen
    public function add(Request $request)
    {
        $ses_id=$request->session()->get('id');
        return view('snack.recomend_index',["member_id"=>$ses_id]);
    }



//recommend snacks ここもっとコンパクトに？？？
    public function create(Request $request)
    {   
        $this->validate($request,Snack::$rules);
        $item=new Snack;
        $form=$request->all();
        unset($form['_token']);
        //初めに配列を用意
        $errors=array();
        //2023.2.28 画像ファイルの名前を抽出して保存。ファイル自体もmoveメソッドで保存。
        if(isset($form['image'])){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();

            if($extension =='jpg' || $extension=='png' || $extension=='gif'|| $extension=="jfif"
            ||$extension=='JPG'||$extension=='TIFF'||$extension=='PNG'||$extension=='GIF'
            ||$extension=='JPEG'||$extension=='tiff'){
                $original=$file->getClientOriginalName();
                $filename=date('Ymd_His').'_'.$original;
                $form['image']=$filename;
            }
            else{
                $errors['image']="不適切なファイルです";
            }
        }

        //最後に、errorがなければ、ファイルのアップロード、データベースへの保存を行う。
        if(empty($errors)){
            $form['keyword']=$form['keyword'].','.$form['name']
            .','.$form['company'].','.$form['type'].','
            .$form['country'].','.$form['coment'];

            if(isset($file)){
                $file->move('../storage/app/public/snack_images',$filename);
            }
            $item->fill($form)->save();
            return view('snack.recomend');
        }
        //errorがあれば、表示。ここでは、error_othersはすでに配列。
        else{
            $ses_id=$request->session()->get('id');
            return view('snack.recomend_index',[
            'error_others'=>$errors,
            'member_id'=>$ses_id,
            ]);
        }

    }

    




//search snacks
    public function search(Request $request){
        //2023.3.10 ペジネーションすると、これらのクエリが引き継がれない問題
        //
        $request->session()->put('keyword',$request->keyword);
        $request->session()->put('snack_type',$request->snack_type);
        $request->session()->put('country',$request->country);
        $request->session()->put('order',$request->order);
        $ses=$request->session()->all();

        $suggest_items=$request->suggest_items;
        //AND OR検索　を考える????
        //2023.3.9 検索機能は共通関数に
        $items=SnackSearch::snack_search($ses['keyword'],$ses['snack_type'],$ses['country'],$ses['order']);
        //2023.3.1 セッションmemberがそのsnackをすでにlike しているかチェック。
        // $like_check=LikeFunction::like_check($ses['id'],$items);

        return view('main.mypage',[
            'member'=>$ses,
            'items'=>$items,
            // 'like_check'=>$like_check,
            'suggest_items'=>$suggest_items,
        ]);
        
    }




//search snacks(for GET)
    public function get_search(Request $request)
    {
        $ses=$request->session()->all();
        $suggest_items=$request->suggest_items;
        //AND OR検索　を考える????
        //2023.3.9 検索機能は共通関数に
        $items=SnackSearch::snack_search($ses['keyword'],$ses['snack_type'],$ses['country'],$ses['order']);
        //2023.3.1 セッションmemberがそれぞれのsnackをすでにlike しているかチェック。
        //不要$like_check=LikeFunction::like_check($ses['id'],$items);

        return view('main.mypage',[
            'member'=>$ses,
            'items'=>$items,
            // 'like_check'=>$like_check,
            'suggest_items'=>$suggest_items,
        ]);
        
    }


 



//search 'likes' snacks 自分がイイネしたスナックだけを表示
    public function like_search(Request $request)
    {
        $ses=$request->session()->all();
        //suggest_items で広告。ミドルウェア由来
        $suggest_items=$request->suggest_items;
        //2023.3.1 ここで、自分がlikeしてるものだけ表示する
        //2023.3.2 やり方検討!!!
        //whereInが大切！！
        $my_likes=Like::where('member_id',$ses['id'])->pluck('snack_id');
        $items=Snack::whereIn('id',$my_likes)
        ->where('deletion',0)
        ->simplePaginate(5); //2023.5.5 ここsimplePaginate
        //ここで、セッションmemberがそのsnackをすでにlike しているかチェック。
        //2023.3.8　共通関数を使ってみる。
        //不要　$like_check=LikeFunction::like_check($ses['id'],$items);
    
        return view('main.mypage',[
        'member'=>$ses,
        'items'=>$items,
        // 'like_check'=>$like_check,
        'suggest_items'=>$suggest_items,
        ]);
    }





//search by recommender
//2023.3.20 recomenderの名前も表記
    public function recomend_search(Request $request)
    {
        $ses=$request->session()->all();
        //suggest_itemsで広告
        $suggest_items=$request->suggest_items;
        //2023.3.3　特定の人がリコメンドしたもののみを取得

        //2023.5.5 
        if(isset($request->recomend)){
            $request->session()->put('recomend',$request->recomend);
        }
        $recomend=$request->session()->get('recomend');


       //2023.5.5 $recomend=$request->recomend;
        //2023.6.21 $recomend の形式変更。
        $items=SnackSearch::recomend_search($recomend);
        //ここで、セッションmemberがそのsnackをすでにlike しているかチェック。
        //共通関数
        // $like_check=LikeFunction::like_check($ses['id'],$items);

        //recomenderの情報
        $recomender_info=Member::where('id',$recomend)->first();

         return view('main.mypage',[
         'member'=>$ses,
         'items'=>$items,
        //  'like_check'=>$like_check,
         'suggest_items'=>$suggest_items,
         'recomender_info'=>$recomender_info,
         ]);
}




 



//to Snack Delete Screen　そのユーザーじゃないとアクセスできないようにする。
    public function delete_index(Request $request)
    {
        $snack_id=$request->snack_id;
        $ses=$request->session()->all();
        $item=Snack::where('id',$snack_id)->first();

        return view('snack.delete_index',
        [
            'member_id'=>$ses['id'],
            'item'=>$item,
        ]);
    }





//delete snack
    public function delete(Request $request)
    {
        $snack_id=$request->snack_id;
        $snack_image=$request->snack_image;
        $ses=$request->session()->all();
        $suggest_items=$request->suggest_items;

        $item=Snack::where('id',$snack_id)->delete();
        Storage::disk('public')->delete('snack_images/'.$snack_image);

        return redirect('mypage/home');
    }





//to Snack Edit Screen　そのユーザーじゃないとアクセスできないようにする。
    public function edit_index(Request $request)
    {
        $snack_id=$request->snack_id;
        $ses=$request->session()->all();
        $item=Snack::where('id',$snack_id)->first();

        return view('snack.edit_index',
        [
            'member_id'=>$ses['id'],
            'item'=>$item,
        ]);
        
    }




//edit screen /selectのデフォルト表記を考える必要ありか？？？
    public function edit (Request $request)
    {
        $this->validate($request,Snack::$rules);
        $form=$request->all();
        unset($form['_token']);
        $snack=Snack::find($request->id);
            
        //初めに配列を用意
        $error=array();
        //2023.3.20 画像ファイルの名前を抽出して保存。ファイル自体もmoveメソッドで保存。
            if(isset($form['image'])){
                $file=$request->file('image');
                $extension=$file->getClientOriginalExtension();

                if($extension =='jpg' || $extension=='png' || $extension=='gif'|| $extension=='jfif'
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
            //2023.3.20 イメージが選択されない場合は、既存のものに戻す。
            else{
                $form['image']=$request->current_image;
            }

            //2023.3.20 キーワードの追加
            $current_keyword=Snack::where('id',$request->id)->first('keyword');
            $form['keyword']=$current_keyword['keyword'].'、'.$form['keyword'];

            if(empty($error)){
                if(isset($file)){
                    $request->session()->put('image',$filename);
                    $file->move('../storage/app/public/snack_images',$filename);
                    Storage::disk('public')->delete('snack_images/'.$request->current_image);
                }
                $snack->fill($form)->save();
                return view('snack.edit');
            }else{
                return view('snack.edit_index',
                ['error_others'=>$error],
            );
            }
    }







//2023.6.21 以下はguest用のsnack_search
       //search snacks
       public function guest_search(Request $request){
        //2023.3.10 ペジネーションすると、これらのクエリが引き継がれない問題
        //
        $request->session()->put('keyword',$request->keyword);
        $request->session()->put('snack_type',$request->snack_type);
        $request->session()->put('country',$request->country);
        $request->session()->put('order',$request->order);
        $ses=$request->session()->all();

        $suggest_items=$request->suggest_items;
        //AND OR検索　を考える????
        //2023.3.9 検索機能は共通関数に
        $items=SnackSearch::snack_search($ses['keyword'],$ses['snack_type'],$ses['country'],$ses['order']);
        //2023.3.1 セッションmemberがそのsnackをすでにlike しているかチェック。
      
        return view('main.guestpage',[
            'member'=>$ses,
            'items'=>$items,
           
            'suggest_items'=>$suggest_items,
        ]);
        
    }



//ここからは、guest用のsnackサーチ
    //search snacks(for GET)
        public function get_guest_search(Request $request)
        {
            $ses=$request->session()->all();
            $suggest_items=$request->suggest_items;
            //AND OR検索　を考える????
            //2023.3.9 検索機能は共通関数に
            $items=SnackSearch::snack_search($ses['keyword'],$ses['snack_type'],$ses['country'],$ses['order']);
            //2023.3.1 セッションmemberがそれぞれのsnackをすでにlike しているかチェック。
        

            return view('main.guestpage',[
                'member'=>$ses,
                'items'=>$items,
                'suggest_items'=>$suggest_items,
            ]);
            
        }


        //search by recommender
    //2023.3.20 recomenderをguestでsearch
    public function guest_recomend_search(Request $request)
    {
    
        //suggest_itemsで広告
        $suggest_items=$request->suggest_items;
        //2023.3.3　特定の人がリコメンドしたもののみを取得

        //2023.5.5 
        if(isset($request->recomend)){
            $request->session()->put('recomend',$request->recomend);
        }
        $recomend=$request->session()->get('recomend');

        //$recomendはmember_idのみを指定。
        $items=SnackSearch::recomend_search($recomend);
        //recomenderの情報
        $recomender_info=Member::where('id',$recomend)->first();

        return view('main.guestpage',[
        'items'=>$items,
        'suggest_items'=>$suggest_items,
        'recomender_info'=>$recomender_info,
        ]);
    }





}
