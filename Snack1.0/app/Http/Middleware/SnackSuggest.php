<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Snack;
use App\Models\Like;
 

/*session_idをもとに、そのユーザーの'like'傾向から、自動でsnacks をsuggestする。
Based on the session_id, automatically suggest snacks based on the user's 'like' trend.
*/
class SnackSuggest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    //2023.3.7 ログイン処理をされて、セッションidが登録された後に、このmiddlewareで、suggestする。
    public function handle(Request $request, Closure $next)
    {   
        //リクエストされる前に処理
        $suggest_items=array();
        $ses_id=$request->session()->get('id');
        
        //2023.3.3 ユーザーのお気に入り登録情報から、ランダムで紹介する機能。
            //これ、いいねしたことない人が通るとエラー？
            //→解決策：まず、いいねしたことあるか判定する。
            $my_likes_bool=Like::where('member_id',$ses_id)->exists();
            //次に、trueなら、ユーザーがいいねした商品のsnack_idを配列で取得。
            $keys=array();



            //イイネがある時はこの処理
            if($my_likes_bool){
            $my_likes=Like::where('member_id',$ses_id)->pluck('snack_id');
            //ここ、ランダムで決める。
            $i=rand(1,5);
            switch($i){
                case 1: //いいねしたtypeの傾向からsugest
                    $my_likes_judge=Snack::whereIn('id',$my_likes)->pluck('type');
                    //$my_likes_judgeのtype配列の中で、最も出現回数の多い要素を取得する。
                    //配列内の最頻値を取得する。
                    //modeは配列の要素のうち、最頻値を配列として取得する。
                    $keys=$my_likes_judge->mode();
                    //$keysに当てはまるもの、かつまだlikeしてないもの、かつ自分がrecomendしたもの以外のものを取得。
                    //inRandomOrder でランダムな順番で取り出す。
                    $suggest_items=Snack::whereNotIn('id',$my_likes)
                    //->whereNotIn('member_id',$ses_id)
                    ->whereIn('type',$keys)
                    ->where('deletion',0) //2023.5.5 ここ訂正
                    ->orderBy('likes_cnt','desc')
                    ->limit(4) //2023.5.5 ここまだ少な目に
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                    break;

                case 2: //いいねしたcountryの傾向からsugest
                    $my_likes_judge=Snack::whereIn('id',$my_likes)->pluck('country');
                    //$my_likes_judgeのcountry配列の中で、最も出現回数の多い要素を取得する。
                    //配列内の最頻値を取得する。
                    //modeは配列の要素のうち、最頻値を配列として取得する。
                    $keys=$my_likes_judge->mode();
                    //$keysに当てはまるもの、かつまだlikeしてないもの、かつ自分がrecomendしたもの以外のものを取得。
                    $suggest_items=Snack::whereNotIn('id',$my_likes)
                    //->whereNotIn('member_id',$ses_id)
                    ->where('deletion',0) //2023.5.5
                    ->whereIn('country',$keys)
                    ->orderBy('likes_cnt','desc')
                    ->limit(4)
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                    break;

                case 3: //いいねしたcompanyの傾向からsugest
                    $my_likes_judge=Snack::whereIn('id',$my_likes)->pluck('company');
                    //$my_likes_judgeのcompany配列の中で、最も出現回数の多い要素を取得する。
                    //配列内の最頻値を取得する。
                    //modeは配列の要素のうち、最頻値を配列として取得する。
                    $keys=$my_likes_judge->mode();
                    //$keysに当てはまるもの、かつまだlikeしてない、かつ自分がrecomendしたもの以外のものを取得。
                    $suggest_items=Snack::whereNotIn('id',$my_likes)
                    //->whereNotIn('member_id',$ses_id)
                    ->where('deletion',0) //2023.5.5
                    ->whereIn('company',$keys)
                    ->orderBy('likes_cnt','desc')
                    ->limit(4) //2023.5.5
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                    break;

                    case 4: //いいねしたrecommenderの傾向からsugest
                        $my_likes_judge=Snack::whereIn('id',$my_likes)->pluck('member_id');
                        //$my_likes_judgeのmember配列の中で、最も出現回数の多い要素を取得する。
                        //配列内の最頻値を取得する。
                        //modeは配列の要素のうち、最頻値を配列として取得する。
                        $keys=$my_likes_judge->mode();
                        //$keysに当てはまるもの、かつまだlikeしてないもの、かつ自分がrecomendしたもの以外のものを取得。
                        //inRandomOrder でランダムな順番で取り出す。
                        $suggest_items=Snack::whereNotIn('id',$my_likes)
                        //->whereNotIn('member_id',$ses_id)
                        ->whereIn('member_id',$keys)
                        ->where('deletion',0) //2023.5.5 ここ訂正
                        ->orderBy('likes_cnt','desc')
                        ->limit(4) //2023.5.5 ここまだ少な目に
                        ->inRandomOrder()
                        ->limit(3)
                        ->get();
                        break;

                case 5: //いいねの傾向に関係なく、いいねしたもの以外を完全ランダムでsugest
                    //まだlikeしてないもの、かつ自分がrecomendしたもの以外のものを取得。
                    $suggest_items=Snack::whereNotIn('id',$my_likes)
                    //->whereNotIn('member',$ses_id)
                    ->where('deletion',0) //2023.5.5
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                    break;
                }   
            }else{ //イイネがまだされてない、guest用
                $suggest_items=Snack::where('deletion',0) //2023.5.5
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
            }

            $request->merge(['suggest_items'=>$suggest_items]);
            return $next($request);
        
    }
}
