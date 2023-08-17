<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Snack;

//Not use now
class SnackEditCheck
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
        $snack_id=$request->snack_id;

        $snack=Snack::where('id',$snack_id)->first();
        if(isset($snack)){
            if($snack['member_id']==$ses_id){
                return $next($request);    
            }else{
                return redirect('mypage/home');
            }
        }else{
            return redirect('mypage/home');
        }
    }
}
