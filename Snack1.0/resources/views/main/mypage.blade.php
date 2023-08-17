@extends('layouts.snackapp_mypage')

@section('title','MyPage')


@section('subbar')
<div class=upper-links>
<a href="login">&raquo Login screen...</a><br>
        @if($member['id']==1)
        <a href="../administrator/index">&raquo管理者ログイン画面</a><br>
        @else
        <a href="member_delete" class="deletion">&raquoAccount Deletion</a>&nbsp;
        <a href="member_edit" class="edit">&raquoAccount Edit</a>&nbsp;
        <a href="member_pass_change" class="edit">&raquopassword change</a>&nbsp;<br>
        @endif
<!--       メンバーインフォはいらない 
<div class='member_infomation'>
        @if(isset($member))
        <img class="member_image" src="../storage/member_images/{{$member['image']}}" width="70" height="85" alt="" align='left'>
        <p>Name: {{$member['name']}} </p>
        <p>Mail: {{$member['mail']}} </p>
        @endif
</div>
-->
<a href="recomend_add">&raquo Recommend snacks!</a>
</div><!--upper-links-->

<div class="form_conf">


            <form action="like_search" method="post">
            {{csrf_field()}}
                <input type="submit" value="★" class="my_like_button" align="left">
            </form>
            <form action="recomend_search" method="post" >
            {{csrf_field()}}
            <!-- recomendの値を変更。シンプルにmember_idのみ -->
                <input type="hidden" name="recomend" value="{{$member['id']}}">
                <input type="submit" value="R" class="recomended_button">
            </form>
        </div>
        
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
            @if(!$item->isLikedBy($member['id']))
            <span class="likes">
            <i class="fas snack-like like-toggle" data-snack_id="{{ $item->id }}">★</i>
            <span class="like-counter">{{$item->likes_cnt}}</span>
            </span><!-- /.likes -->
            @else
            <span class="likes">
            <i class="fas snack-like star like-toggle liked" data-snack_id="{{ $item->id }}">★</i>
            <span class="like-counter">{{$item->likes_cnt}}</span>
            </span><!-- /.likes -->
            @endif
            <!--イイネ処理をjqueryとajaxによる非同期通信に変更する
            <tr><th> 
            @if(!isset($like_check[$item->id]))
            <a class="before_nice" href="like?snack_id={{$item->id}}">☆</a>
            @else
            <a class="after_nice" href="like?snack_id={{$item->id}}">★</a>
            @endif
            </th><td>
            count of likes {{$item->likes_cnt}}
            </td></tr>
-->
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
            <!--ここを変えて、イイネ処理を非同期にする。イイネを押した後、jqueryを介す
            <tr><th>
            @if(!isset($like_check[$item->id]))
            <a class="before_nice" href="like?snack_id={{$item->id}}" class="before_nice">☆</a>
            @else
            <a class="after_nice" href="like?snack_id={{$item->id}}" class="after_nice">★</a>
            @endif
        </th><td>
            count of likes {{$item->likes_cnt}}
        </td></tr>
-->
        <!--イイネ処理はjquery とajaxを使った非同期処理にする。Vue への代替も考えてみる-->
            <tr><th>
            @if(!$item->isLikedBy($member['id']))
            <span class="likes">
                <i class="fas snack-like like-toggle" data-snack_id="{{ $item->id }}">★</i>
                <span class="like-counter">{{$item->likes_cnt}}</span>
            </span><!-- /.likes -->
            @else
            <span class="likes">
                <i class="fas snack-like star like-toggle liked" data-snack_id="{{ $item->id }}">★</i>
                <span class="like-counter">{{$item->likes_cnt}}</span>
            </span><!-- /.likes -->
            @endif
            </th><td></td></tr>
            
            <tr><th>Name:</th><td><a href="{{$item->url}}" target="_blank" style="text-decoration:none;">{{$item->name}}</a>
            @if($item->member_id==$member['id'])
            <a href="snack_delete?snack_id={{$item->id}}" class='deletion'>delete</a>
            <a href="snack_edit?snack_id={{$item->id}}" class='edit'>edit</a>
            @endif
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