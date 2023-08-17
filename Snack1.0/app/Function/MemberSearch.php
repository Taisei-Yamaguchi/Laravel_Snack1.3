<?php
namespace App\Function;

use App\Models\Member;
use Illuminate\Support\Facades\Facade;

class MemberSearch extends Facade
{
//member_search for administrator
    public static function member_search ($keyword,$order)
    {   //引数の$orderは、"name,asc"のように指定する。
        //whereを追加することで、条件をさらに絞った検索ができる 
        //2023.3.8 order指定すると、順番をどのようにするかをorder->valueから、配列で取り出して指定する。
        //したがって、記述は一つ。
        if(!isset($order)){    //デフォルト順表示
            $members=Member::where('name','like','%'.$keyword.'%')
            ->orWhere('mail','like','%'.$keyword.'%')
            ->simplePaginate(5);
        }else{
            $order_array=explode(',',$order);
            $members=Member::where('name','like','%'.$keyword.'%')
            ->orWhere('mail','like','%'.$keyword.'%')
            ->orderBy($order_array[0],$order_array[1])
            ->simplePaginate(5);
        }
        return $members;
    }
}