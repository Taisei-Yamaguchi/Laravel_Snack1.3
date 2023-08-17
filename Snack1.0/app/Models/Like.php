<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded=array('id');

    public static $rules=array(
        'member_id'=>'required',
        'snack_id'=>'required',
    );

    public function getData()
    {
        return $this->id .':'.$this->member_id .'&'.$this->snack_id ;
    }

    public function member(){
        return $this->belongsTo('App\Models\Member');
    }

    public function snack(){
        return $this->belongsTo('App\Models\Snack');
    }

}
