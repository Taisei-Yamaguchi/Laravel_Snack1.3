@extends('layouts.snackapp')

@section('title','Administrator')

@section('content')
        <ul><a href="../mypage/login">ログイン画面へ</a></ul>
        @if($member_id==1)
        <ul><a href="../administrator/index">管理者ログイン画面</a></ul>
        @endif
        <ul><a href="home1">お菓子管理画面へ</a></ul>

    
        <p>登録されているメンバーの管理</p>
        <form action="member" method=post>
            {{csrf_field()}}
            <select name="order">
                <option value="" selected>順番指定してね♡</option>
                <option value="name,asc">名前順</option>
                <option value="created_at,desc">新しい順</option>
                <option value="created_at,asc">古い順</option>
            </select>
            <br>
            <input type="text" name="keyword" placeholder="検索">
            <input type="submit" value="search" class="search">
        </form>
        
        <hr>

        @if(isset($members))
            @foreach($members as $member)
            <table class="result-search">
            <tr><th>ID: </th><td>{{$member->id}}



            <!--制限ボタン　同期処理　使わない-->
            <!-- @if($member->deletion==0&&$member->id!=1)
            <form action="member_limit" method="post">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$member->id}}">
                <input class ="limit" type="submit" value="制限する">
            </form>
            @elseif($member->deletion==1&&$member->id!=1)
            <form action="member_unlimit" method="post">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$member->id}}">
                <input class="unlimit" type="submit" value="制限解除する">
            </form>
            @endif    -->
        
            <!-- 2023.6.11 メンバー制限を非同期処理 -->
            @if($member->deletion==0&&$member->id!=1)
            <span class="limits">
                <button class="limit member-limit-toggle" data-member_id="{{$member->id}}" type="button">制限する</button>
            </span>
            @elseif($member->deletion==1&&$member->id!=1)
            <span class="limits">
                <button class="limit member-limit-toggle limited" data-member_id="{{$member->id}}" type="button">制限解除する</button>
            </span>
            @endif  
        
                
            </td>
            <tr><th>Name:</th><td>{{$member->name}}</td></tr>
            <tr><th>Email:</th><td>{{$member->mail}}</td></tr>
            <tr><th>Image</th><td><img src="../storage/member_images/{{$member->image}}" width="70" height="85" alt=""></td></tr>
            </table>
            <br>
            @endforeach

            {{$members->links()}}
        @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection