<?php
namespace App\Function;

use App\Models\Snack;
use Illuminate\Support\Facades\Facade;

class SnackSearch extends Facade
{
//snack_search
    public static function snack_search ($keyword,$snack_type,$country,$order)
    {
        //引数の$orderは、"name,asc"のように指定する。
        //whereを追加することで、条件をさらに絞った検索ができる 
        //2023.3.2 デフォルトはいいね順で表示する
        //2023.3.8 order指定すると、順番をどのようにするかをorder->valueから、配列で取り出して指定する。
        //したがって、記述は一つ。
        if(!isset($order)){
            $items=Snack::where('keyword','like','%'.$keyword.'%')
            ->where('type','like','%'.$snack_type.'%')
            ->where('country','like','%'.$country.'%')
            ->where('deletion',0)
            ->simplePaginate(5);
        }else{
            $order_array=explode(',',$order);
            $items=Snack::where('keyword','like','%'.$keyword.'%')
            ->where('type','like','%'.$snack_type.'%')
            ->where('country','like','%'.$country.'%')
            ->where('deletion',0)
            ->orderBy($order_array[0],$order_array[1])
            ->simplePaginate(5);
        }
        return $items;
    }

//recomender snack_search
    public static function recomend_search($recomend)
    {
        //2023.6.21 recomendの形式変更
        $items=Snack::where('member_id',$recomend)
        ->where('deletion',0)
        ->simplePaginate(5);
        return $items;
    }


//administrator snack_search
    public static function administrator_snack_search($keyword,$snack_type,$country,$order)
    {
        //引数の$orderは、"name,asc"のように指定する。
        //whereを追加することで、条件をさらに絞った検索ができる 
        //2023.3.2 デフォルトはいいね順で表示する
        //2023.3.8 order指定すると、順番をどのようにするかをorder->valueから、配列で取り出して指定する。
        //したがって、記述は一つ。
        if(!isset($order)){
            $items=Snack::where('keyword','like','%'.$keyword.'%')
            ->where('type','like','%'.$snack_type.'%')
            ->where('country','like','%'.$country.'%')
            ->simplePaginate(5);
        }else{
            $order_array=explode(',',$order);
            $items=Snack::where('keyword','like','%'.$keyword.'%')
            ->where('type','like','%'.$snack_type.'%')
            ->where('country','like','%'.$country.'%')
            ->orderBy($order_array[0],$order_array[1])
            ->simplePaginate(5);
        }
        return $items;
    }

//administrator_recomender snack_search
    public static function administrator_recomend_search($recomend)
    {
        //2023.6.21 $recomendの形式をmember_idのみのシンプルな形に
        $items=Snack::where('member_id',$recomend)->simplePaginate(5);
        return $items;
    }
}