<?php

//DBに関する処理をコントローラに直接記述ではなく、Modelsに書くようにする。

namespace App\Models;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

class Snack extends Model
{
    protected $guarded=array('id');

    public static $rules=array(
        'name'=>'required|max:200',
        'company'=>'required|max:200',
        'member_id'=>'required',
        'image'=>'',
        'url'=>'nullable|url',
        'type'=>'required',
        'coment'=>'nullable|max:50',
        'keyword'=>'nullable|max:50',
        'country'=>'required',
        'deletion'=>'',
    );

    public function member(){
        return $this->belongsTo('App\Models\Member');
    }

    public function like(){
        return $this->hasOne('App\Models\Like');
    }

    public function getData(){
        return $this->id.':'.$this->name.'('
        .$this->member->name.')';
    }

    //指定したmember_idについて、すでにそのsnackにイイネを押しているかをここで判定する。
    public function isLikedBy($member_id):bool{
        return Like::where('member_id',$member_id)
        ->where('snack_id',$this->id)
        ->first() !==null;
    }


}
