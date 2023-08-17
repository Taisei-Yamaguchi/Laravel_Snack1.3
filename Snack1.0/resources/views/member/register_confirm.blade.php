@extends('layouts.snackapp')

@section('title','Register')

@section('content')
    <form action="../member/add_get" enctype="multipart/form-data">
        @if(isset($form['image']))
        <input type="hidden" name="file" vlaue="{{$form['image']}}">
        @endif
        <input type="submit" value="入力画面に戻る">
    </form>
    
    
    <form action="add" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            <tr><th></th><td>この内容で登録しますか？
            </td></tr>
            <tr><th>Name:</th><td>{{$form['name']}}</td></tr>
            <tr><th>mail:</th><td>{{$form['mail']}}</td></tr>
            <tr><th>password:</th><td>非表示</td></tr>
            <tr><th>Image:</th><td>
                @if(isset($form['image']))
                    <img src="../../storage/app/public/member_images/{{$form['image']}}" width="70" height="85" alt="">
                @endif
            </td></tr>
            <tr><th></th><td><input type="submit" value="REGISTER"></td></tr>
        </table>
    </form>
    
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection