<?php

namespace App\Http\Controllers;

use App\Function\SnackSearch;
use App\Function\LikeFunction;
use App\Function\MemberSearch;
use App\Function\LoginFunction;
use App\Models\Snack;
use App\Models\Member;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{


    

//to Login Screen
    public function login_index(Request $request){
        $request->session()->forget('id');
        $request->session()->forget('name');
        $request->session()->forget('mail');
        $request->session()->forget('image');
        $request->session()->forget('keyword');
        $request->session()->forget('snack_type');
        $request->session()->forget('country');
        $request->session()->forget('order');
        $request->session()->forget('form');
        return view('main.index');
    }





//login processing & to Mypage
    public function login(Request $request){
        $mail=$request->mail;
        $pass=$request->pass;
        //mailとpassを渡して、ログインできるメンバーを取得
        $member=LoginFunction::login($mail,$pass);
        //ここにifを入れて、一致するメンバーがいたらマイページへ。いなければエラーメッセージ。
        if(isset($member)){
            if($member['deletion']==0){//ここで、初めて、メンバーが制限されているかを確認する。
                $request->session()->put('id',$member->id);
                $request->session()->put('name',$member->name);
                $request->session()->put('image',$member->image);
                $request->session()->put('mail',$member->mail);
                
                $suggest_items=array();
                $ses=$request->session()->all(); 
            
                //$suggest_items=array();
            //2023.3.7 ここミドルウェアに移す？？
                return view('main.mypage',[
                    'member'=>$ses,
                    'suggest_items'=>$suggest_items,
                ]);
            }else{
                return view('main.index',['mess'=>'このアカウントは管理人の権限により一時的に利用が制限されています。お手数ですが管理人にお問い合わせお願いします。']);
            }
        }else{
            return view('main.index',['mess'=>'ログイン失敗。mailかpasswordが間違っています。']);
        }
    }





//to Mypage(for GET). But session_id which is added when logined is necessary.
    public function mypage_index(Request $request)
    {
        $request->session()->forget('keyword');
        $request->session()->forget('snack_type');
        $request->session()->forget('country');
        $request->session()->forget('order');
        $ses=$request->session()->all();
        //↓ミドルウェアから取得
        $suggest_items=$request->suggest_items;

        return view('main.mypage',[
            'member'=>$ses,
            'suggest_items'=>$suggest_items,
        ]);
    }




//to Administrator Login page. But session_id has to be '1'.
    public function administrator_index(Request $request)
    {
        $request->session()->forget('keyword');
        $request->session()->forget('snack_type');
        $request->session()->forget('country');
        $request->session()->forget('order');
        $ses_id=$request->session()->get('id');
        return view('main.administrator_index',[
            'member_id'=>$ses_id,
        ]);
    }





//administrator login & to snacks administration page.
    public function administrator_1(Request $request)
    {
        $name=$request->name;
        $love_girl=$request->love_girl;
        $neko=$request->neko;

        if(($name=="山口泰生")&&($neko=="わらび"))
        {
            $ses_id=$request->session()->get('id');

            return view('main.administrator_1',[
            'member_id'=>$ses_id,
            ]);
        }else{
            $error="情報が違います。";

            return view('main.administrator_index',[
                'error'=>$error,
            ]);
        }
    }

    



//to snacks administration page　(for GET)
    public function administrator_1_1(Request $request)
    {
        $request->session()->forget('keyword');
        $request->session()->forget('snack_type');
        $request->session()->forget('country');
        $request->session()->forget('order');
        $ses_id=$request->session()->get('id');
        return view('main.administrator_1',[
            'member_id'=>$ses_id,
        ]);
    }





//search snacks for administration
    public function administrator_snack(Request $request)
    {
        $ses_id=$request->session()->get('id');
        $keyword=$request->keyword;
        $snack_type=$request->snack_type;
        $country=$request->country;
        $order=$request->order;

        $request->session()->put('keyword',$keyword);
        $request->session()->put('snack_type',$snack_type);
        $request->session()->put('country',$country);
        $request->session()->put('order',$order);
        //sessionに追加することで、ページ移動しても検索の情報を残す。
        //AND OR検索　を考える???
        //2023.3.9 検索機能は共通関数に
        $items=SnackSearch::administrator_snack_search($keyword,$snack_type,$country,$order);
        return view('main.administrator_1',[
            'member_id'=>$ses_id,
            'items'=>$items,
        ]);
    }




//search snacks for administration (for GET)
public function get_administrator_snack(Request $request)
{
    $ses_id=$request->session()->get('id');
    $keyword=$request->session()->get('keyword');
    $snack_type=$request->session()->get('snack_type');
    $country=$request->session()->get('country');
    $order=$request->session()->get('order');
    //AND OR検索　を考える???
    //2023.3.9 検索機能は共通関数に
    $items=SnackSearch::administrator_snack_search($keyword,$snack_type,$country,$order);
    return view('main.administrator_1',[
        'member_id'=>$ses_id,
        'items'=>$items,
    ]);
}



//recomender_search snack for administartor
public function administrator_snack_recomender(Request $request)
{
    $ses_id=$request->session()->get('id');

    //recomendクエリの情報をセッションに入れて置き、ページングで移動しても、recomender_searchの情報を引き継ぐ。
    if(isset($request->recomend)){
        $request->session()->put('recomend',$request->recomend);
    }
    $recomend=$request->session()->get('recomend');

    //2023.6.21 $recomendの中身はmember_idのみとシンプルにする。
    //SNackSearchでは、'member_id'で、Memberの情報を取得するなら、'id'を指定する。
    $items=SnackSearch::administrator_recomend_search($recomend);
    $recomender_info=Member::where('id',$recomend)->first();
    return view('main.administrator_1',[
        'member_id'=>$ses_id,
        'items'=>$items,
        'recomender_info'=>$recomender_info,
    ]);
}





//2023.6.10 非同期によるsnack limit 処理。
    public function snack_limit_process(Request $request)
    {
        $snack_id = $request->snack_id; //2.snack_idの取得
        $already_limit = Snack::where('id',$snack_id)->where('deletion', 1)->first(); //3.

        $newStatus;
        if (!$already_limit) { //もしそのsnackがまだlimitされていなかったら
            $param=[
                'deletion'=>1,
            ];
            $item=Snack::where('id',$snack_id)->update($param);
            $newStatus="表示する";
        } else { //もしこのsnackが既にlimitされていたら
            $param=[
                'deletion'=>0,
            ];
            $item=Snack::where('id',$snack_id)->update($param);
            $newStatus="非表示にする";
        }
        $param = [
            'newStatus' => $newStatus,
        ];
        //5.そのsnackの最終的なlimitの値を返す？アクセスする度に、0か1に変わるだけだから必要なし
        return response()->json($param); //6.jQueryに返す

    }




//to member administration page
    public function administrator_2(Request $request)
    {
        $request->session()->forget('keyword');
        $request->session()->forget('snack_type');
        $request->session()->forget('country');
        $request->session()->forget('order');
        $ses_id=$request->session()->get('id');

        return view('main.administrator_2',[
            'member_id'=>$ses_id,
        ]);
    }



//search members for administration
    public function administrator_member(Request $request)
    {
        $ses_id=$request->session()->get('id');
        $keyword=$request->keyword;
        $order=$request->order;

        $request->session()->put('keyword',$keyword);
        $request->session()->put('order',$order);
        $members=MemberSearch::member_search($keyword,$order);
        return view('main.administrator_2',[
            'member_id'=>$ses_id,
            'members'=>$members,
        ]);
    }




//search members for administration(for Get)
    public function get_administrator_member(Request $request)
    {
        $ses_id=$request->session()->get('id');
        $keyword=$request->session()->get('keyword');
        $order=$request->session()->get('order');

        $request->session()->put('keyword',$keyword);
        $request->session()->put('order',$order);
        $members=MemberSearch::member_search($keyword,$order);
        return view('main.administrator_2',[
            'member_id'=>$ses_id,
            'members'=>$members,
        ]);
    }


//2023.6.11 member limit 非同期処理
    public function member_limit_process(Request $request)
    {
        $member_id = $request->member_id; //2.snack_idの取得
        $already_limit = Member::where('id',$member_id)->where('deletion', 1)->first(); //3.

        $newStatus;
        if (!$already_limit) { //もしそのsnackがまだlimitされていなかったら
            $param=[
                'deletion'=>1,
            ];
            $item=Member::where('id',$member_id)->update($param);
            $newStatus="制限解除する";
        } else { //もしこのsnackが既にlimitされていたら
            $param=[
                'deletion'=>0,
            ];
            $item=Member::where('id',$member_id)->update($param);
            $newStatus="制限する";
        }
        $param = [
            'newStatus' => $newStatus,
        ];
        //5.そのsnackの最終的なlimitの値を返す？アクセスする度に、0か1に変わるだけだから必要なし
        return response()->json($param); //6.jQueryに返す

    }



    
    //to Mypage(for GET). 
//go home as a guest
public function guest_index(Request $request)
{
    //↓ミドルウェアから取得
    $suggest_items=$request->suggest_items;

    return view('main.guestpage',[
        'suggest_items'=>$suggest_items,
    ]);
}




    //limit snacks　同期処理　使わない
    // public function snack_limit(Request $request)
    // {
    //     $ses_id=$request->session()->get('id');
    //     $id=$request->snack_id;
    //     $param=[
    //         'deletion'=>1,
    //     ];

    //     $item=Snack::where('id',$id)->update($param);
    //     return back()->withInput();
    // }




//unlimit snacks 同期処理　使わない
    // public function snack_unlimit(Request $request)
    // {
    //     $ses_id=$request->session()->get('id');
    //     $id=$request->snack_id;
    //     $param=[
    //         'deletion'=>0,
    //     ];

    //     $item=Snack::where('id',$id)->update($param);
    //     return back()->withInput();
    // }



//limit members　同期処理　使わない
    // public function member_limit(Request $request)
    // {
    //     $ses_id=$request->session()->get('id');
    //     $id=$request->id;
    //     $param=[
    //         'deletion'=>1,
    //     ];

    //     $item=Member::where('id',$id)->update($param);
    //     return back()->withInput();
    // }




//unlimit members　同期処理　使わない
//     public function member_unlimit(Request $request)
//     {
//         $ses_id=$request->session()->get('id');
//         $id=$request->id;
//         $param=[
//             'deletion'=>0,
//         ];

//         $item=Member::where('id',$id)->update($param);
//         return back()->withInput();
//     }


}

