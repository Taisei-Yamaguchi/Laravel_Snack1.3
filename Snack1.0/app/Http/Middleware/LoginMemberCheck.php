<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**session_idが存在するかどうかチェック。session_idがなく、
マイページにアクセスするとログイン画面に戻される。
guest pageではこのミドルウェアを使わない。
Check if there is session_id or not. If there is not session_id,
when accessed Mypage,you wil be back to Login Screen.

*/

class LoginMemberCheck
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
        $ses_id=$request->session()->get('id');

        if(isset($ses_id)){
            return $next($request);    
        }else{
            return redirect('mypage/login');
        }

    }
}
