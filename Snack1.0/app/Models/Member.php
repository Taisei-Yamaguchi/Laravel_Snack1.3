<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

/**
 * Memberクラス
 */
class Member extends Model
{
    protected $guarded=array('id');

    public static $rules=array(
        'name'=>'required|max:255',
        'mail'=>'required|max:255', //本当は 'required'じゃなくて 'email'
        'pass'=>'required|min:5|max:20',
        'image'=>'required',
        'deletion'=>'',
    );

    public static $rules_edit=array(
        'name'=>'required|max:255',
        'image'=>'',
        'deletion'=>'',
    );

    public static $rule_pass_change=array(
        'pass'=>'required|min:5|max:20',
    );

    public static $rules_integrate=array(
        'mail'=>'required|max:255',
        'pass'=>'required|min:5|max:20',
    );



    public function getData(){
        return $this->id.':'.$this->name;
    }

    public function likes(){
        return $this->hasMany('App\Models\Like');
    }
}
