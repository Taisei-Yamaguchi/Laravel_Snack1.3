<?php
namespace App\Function;

use App\Models\Snack;
use App\Models\Member;
use Illuminate\Support\Facades\Facade;


//login function 
class LoginFunction extends Facade
{
    public static function login($mail,$pass)
    {
        $member =Member::where('mail',$mail)
        ->where('pass',$pass)
        ->first();

        return $member;
    }
}