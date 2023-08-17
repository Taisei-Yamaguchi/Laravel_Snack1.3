@extends('layouts.snackapp')

@section('title','Register')

@section('content')
    
<a href="home">to Mypage...</a>
    <form action="recomend_add" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            <tr><th></th><td>Please enter information of snack that you want to recommend.</td></tr>
            <tr><th>Name:</th><td><input type="text" name="name"></td></tr>
            <tr><th>Company:</th><td><input type="text" name="company"></td></tr>
            <tr><th>Type:</th><td><select name="type">
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
                <option Value="スナック菓子">スナック菓子 snack food</option><!-- comment -->
                <option value="せんべい">せんべい rice cookie</option>
                <option value="その他">その他 other</option>    
            </select>
            </td></tr>
            <tr><th>Country:</th><td><select name="country">
                <option value="" selected>国を選んでね♡</option>
                <option value="日本">日本</option><!-- comment -->
                <option value="Canada">Canada</option><!-- comment -->
                <option value="その他">その他</option>    
            </select>
            </td></tr>
            <tr><th>Coment:</th><td><textarea name="coment" rows="6" cols="20" onkeyup="ShowLength('inputlength',value);" placeholder="コメントを50字以内で入力して下さい"></textarea>
                <p id="inputlength1"></p></td></tr> <!-- ここjavascript -->
            <tr><th>key Word:</th><td><textarea name="keyword" rows="6" cols="20" placeholder="検索のためのキーワードを50字以内で入力してください"></textarea></td></tr>
            <tr><th>URL: </th><td><input type="text" name="url" placeholder="URL Only">please write links to homepage of maker or information of this snack</td></tr>
            <tr><th>Image:</th><td><input type="file" name="image"></td></tr>
            <input type="hidden" name="member_id" value="{{$member_id}}">
            <tr><th></th><td><input type="submit" value="RECOMMEND"></td></tr>
      
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