@extends('layouts.snackapp')

@section('title','Register')

@section('content')
    <ul><a href="../mypage/login">to Login screen...</a></ul>
    <form action="integrate" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            <tr><th></th><td>Chatアプリにすでに登録しているアカウントがあれば、その情報をSnackアプリでも利用できるようになります。</td></tr>
            <tr><th>mail:</th><td><input type="text" name="mail" value="{{old('mail')}}"></td></tr>
            <tr><th>password:</th><td>
                <div id='password1'>
                <input type="password" name="pass" v-model="input" maxlength="20" placeholder="5~20文字のパスワードを指定してください。">
                </div>
                </td></tr>
            <tr><th></th><td><input type="submit" value="Get Your Account"></td></tr>
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
    @if (isset($errorAPI))
    <div><ul>
        <li>{{$errorAPI}}</li>
    </ul></div>
    @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection