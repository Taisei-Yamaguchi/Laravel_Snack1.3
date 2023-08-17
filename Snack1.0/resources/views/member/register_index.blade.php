@extends('layouts.snackapp')

@section('title','Register')

@section('content')
    <ul><a href="../mypage/login">to Login screen...</a></ul>
    <ul><a href="integrate">Chatアプリのメンバー情報を統合する。</a></ul>
    <form action="add" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            <tr><th></th><td>Please enter your registration information.</td></tr>
            <tr><th>Name:</th><td><input type="text" name="name" value="{{old('name')}}"></td></tr>
            <tr><th>mail:</th><td><input type="text" name="mail" value="{{old('mail')}}"></td></tr>
            <tr><th>password:</th><td>

                <div id='password1'>
                <input type="password" name="pass" v-model="input" maxlength="20" placeholder="5~20文字のパスワードを指定してください。">
                </div>
                <div id='password2'>
                <input type="password" name="pass2" v-model="input" maxlength="20" placeholder="再度入力してください。">
                </div>
                </td></tr>

            <tr><th>Image:</th><td><input type="file" name="image"></td></tr>
            <tr><th></th><td><input type="submit" value="REGISTER"></td></tr>
        </table>
    </form>
    @if(count($errors)>0)
    <div>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        <ul>
    </div>
    @endif
    @if (isset($error_others))
    <div><ul>
        @foreach($error_others as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul></div>
    @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection