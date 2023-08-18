@extends('layouts.snackapp')

@section('title','Login')

@section('css')
<link rel="stylesheet" href="{{asset('/css/snack-login.css')}}">
@endsection

@section('content')
    <div class='login-container'>
        <a href="../member/add">&raquo New Registration</a>
        @if (isset($mess))
        <br>
        {{$mess}}<!--一致するメンバーがいないときと、ただ制限されているときでメッセージを変える。-->
        @endif
        <form action="../mypage/home" method=post> <!-- ここ「/」の有無が大事 !-->
            {{csrf_field()}}
            <label>Mail:</label><input type='text' name='mail'>
            <label>Password:</label><input type='password' name='pass'>
            <input type='submit' value='LOGIN'>
        </form>
        <a href="../guest/home">&raquo only view as a guest.</a>
    </div>
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection