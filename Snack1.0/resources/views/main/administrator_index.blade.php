@extends('layouts.snackapp')

@section('title','Administrator')

@section('content')
    <ul><a href="../mypage/login">ログイン画面へ</a></ul>
    ここは管理者専用ページです。このページを御覧の方は、セキュリティ改善のために管理者までご連絡お願いします。m(__)m<br><!-- comment -->
       
    <form action="home1" method="post">
        {{csrf_field()}}
        &raquo;名前は？:<br>
        <input type="text" name="name" ><!-- comment -->
        <br><!-- comment -->
        &raquo;先輩から引き取った猫の名前は?:<br>
        <input type="text" name="neko"><!-- comment -->
        <br><!-- comment -->
        <input type="submit" value="access">
    </form>

    @if(isset($error))
    {{$error}}
    @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection