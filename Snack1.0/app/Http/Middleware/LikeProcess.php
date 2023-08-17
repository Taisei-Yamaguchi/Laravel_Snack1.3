<?php

//非同期通信にイイネ処理を任せたので、このミドルウェアは使わない。

namespace App\Http\Middleware;

use App\Function\LikeFunction;
use App\Models\Like;
use App\Models\Snack;
use Closure;
use Illuminate\Http\Request;

class LikeProcess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //まずクエリを取得する
        //Middlewareを後処理にしないと、イイネ処理後にsuggestitemsを取得できないか？
        //like処理ミドルウェアで行った後にここに来る。
        $snack_id=$request->snack_id;
        $ses=$request->session()->all();
        //using 'LikeFunction'
        //2023.3.9 共通関数にした
        LikeFunction::like_add_delete($ses['id'],$snack_id);
        return $next($request);
    }
}
