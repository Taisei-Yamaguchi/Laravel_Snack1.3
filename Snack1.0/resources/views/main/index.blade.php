@extends('layouts.snackapp')

@section('title','Login')

@section('content')
    <a href="../member/add">&raquo New Registration</a>
    @if (isset($mess))
    {{$mess}}<!--一致するメンバーがいないときと、ただ制限されているときでメッセージを変える。-->
    @endif
    <form action="../mypage/home" method=post> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            <tr><th></th><td>please enter your address and password.</td></tr>
            <tr><th>mail:</th><td><input type="text" name="mail"></td></tr>
            <tr><th>password:</th><td><input type="password" name="pass"></td></tr>
            <tr><th></th><td><input type="submit" value="LOGIN"></td></tr>
        </table>
    </form>
    <br>
    <a href="../guest/home">&raquo only view as a guest.</a>
    
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection