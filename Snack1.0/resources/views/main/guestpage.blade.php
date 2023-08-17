@extends('layouts.snackapp_mypage')

@section('title','Guest')


@section('subbar')
<div class=upper-links>
<a href="../mypage/login">&raquo Login screen...</a><br>
<p>お菓子のイイネ、投稿にはサインアップが必要です。</p>

</div><!--upper-links-->


        
@endsection

@section('suggests')

            <div class="suggest_items">
            @if(count($suggest_items)>0)
            <br>
            <p class="suggest-message">↓Recomend You↓</p>

            @foreach($suggest_items as $item)
            <table class="suggest-items-table" align="left">
            <tr><th>
            <!--suggest item のテーブル -->
            <span class="likes">
            <i class="fas snack-like" data-snack_id="{{ $item->id }}">★</i>
            <span class="like-counter">{{$item->likes_cnt}}</span>
            </th></tr>
            <tr><td><a href="{{$item->url}}" target="_blank" style="text-decoration:none;">{{$item->name}}</a></td></tr>
            <tr><td>{{$item->company}}</td></tr>
            <tr><td><a href='recomender_search?recomend={{$item->member_id}}' style="text-decoration:none;">{{$item->member->name}}</a></td>
            <tr><td><img src="../storage/snack_images/{{$item->image}}" width="70" height="85" alt=""></td></tr>
            </table>
            @endforeach

        @endif
</div>
@endsection



<hr>



@section('content')
<form action="search" method=post align="left">
            {{csrf_field()}}
            <div class="same-width-list">
                <select name="snack_type" >
                    <option value="" selected>Choose type!</option>
                    <option value="グミ">グミ gummi</option><!-- comment -->
                    <option value="アメ">アメ candy</option><!-- comment -->
                    <option value="ラムネ">ラムネ ramune</option><!-- comment -->
                    <option value="チョコレート">チョコレート chocolate</option><!-- comment -->
                    <option value="クッキー">クッキー cookie</option>
                    <option value="ガム">ガム chewing gum</option>
                    <option value="ビスケット">ビスケット bisketto</option><!-- comment -->
                    <option value="ソフトキャンディー">ソフトキャンディー soft candy</option>
                    <option value="アイス">アイス ice cream</option><!-- comment -->
                    <option Value="スナック菓子">スナック菓子 snack food </option><!-- comment -->
                    <option value="せんべい">せんべい rice cookie</option>
                    <option value="その他">その他 other</option>    
                </select>
            </div>
            <div class="same-width-list">
                <select name="country" >
                    <option value="" selected>Choose Country!</option>
                    <option value="日本">日本 Japan</option><!-- comment -->
                    <option value="Canada">Canada</option><!-- comment -->
                    <option value="その他">その他 other</option>    
                </select>
            </div>
            <div class="same-width-list">
                <select name="order">
                    <option value="" selected>Choose the order of search!</option>
                    <option value="likes_cnt,desc">priority of  popularity</option>
                    <option value="likes_cnt,asc">rarity</option>
                    <option value="name,asc">name</option>
                    <option value="country,asc">country</option>
                    <option value="company,asc">company</option>
                    <option value="created_at,desc">recent</option>
                    <option value="created_at,asc">old</option>
                </select>
            </div><!-- same-width-list-->
            <input type="text" name="keyword" placeholder="検索">
            <input type="submit" value="search" class="search">
</form>
        
        <hr>

<h2>Show Result of Search Here </h2>

        @if(isset($recomender_info))
            <img class="member_image" src="../storage/member_images/{{$recomender_info['image']}}" width="70" height="85" alt="" align='left'>
            <p>{{$recomender_info['name']}} recommends those snacks!</p>
            <br>
            <br>
        @endif    

        @if(isset($items))
            @foreach($items as $item)
            

            <table class="result-search">
            <tr><th>
            <span class="likes">
                <!-- like-toggleを無効にして、イイネできないようにする。 -->
                <i class="fas snack-like" data-snack_id="{{ $item->id }}">★</i>
                <span class="like-counter">{{$item->likes_cnt}}</span>
            </span><!-- /.likes -->
           
            </th><td></td></tr>
            
            <tr><th>Name:</th><td><a href="{{$item->url}}" target="_blank" style="text-decoration:none;">{{$item->name}}</a>
            
            </td></tr>
            
            <tr><th>Company:</th><td>{{$item->company}}</td></tr>
            <tr><th>Comment:</th><td>{{$item->coment}}</td></tr>
            <tr><th>Recommender:</th><td><a href='recomender_search?recomend={{$item->member_id}}' style="text-decoration:none;">{{$item->member->name}}</a></td>
            <tr><th>Image</th><td><img src="../storage/snack_images/{{$item->image}}" width="70" height="85" alt=""></td></tr>
            </table>
            <br>
            @endforeach

            {{$items->links()}}
            <!--検索したitemsが空かどうかを判定。最初のitemの中身でチェック-->
            @if(empty($items[0]))  
    
                <h3>not found...</h3>
                <h3>Let's Recommend your favorite snacks from <a href="recomend_add">"Recommend snacks"</a>link!</h3>
            @endif

        @else
            <h3>ここに検索結果が表示されます。</h3>
            <h3>上の検索バーから探しているお菓子の種類、生産国などを選んで検索してみてね！</h3>
            
            
        @endif

        
       
        
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection