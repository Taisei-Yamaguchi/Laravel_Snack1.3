<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

//session_id=1であることを確認。そうでなければ、administrator login　画面にアクセスできない。
//check if session_id=1 or not.

class AdministratorCheck
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
        $ses_name=$request->session()->get('name');
        $ses_mail=$request->session()->get('mail');
        $ses_image=$request->session()->get('image');

        if($ses_id==1){
            return $next($request);    
        }else{
            return redirect('mypage/login');
        }
    }
}
