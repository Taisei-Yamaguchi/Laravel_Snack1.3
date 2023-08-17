@extends('layouts.snackapp')

@section('title','Edit')

@section('content')
<a href="home" >Back</a><br>
<p>Do you want to delete your account?</p>
<p>*Your account and snacks that you recomended will be completely deleted.
    <form action="member_delete" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            
            <tr><th>ID:</th><td>{{$member['id']}}</td></tr>
            <tr><th>Name:</th><td>{{$member['name']}}</td></tr>
            <tr><th>Mail:</th><td>{{$member['mail']}}</td></tr>
            <tr><th>Image:</th><td><img src="../storage/member_images/{{$member['image']}}" width="70" height="85" alt=""></td></tr>
            <input type="hidden" name="member_id" value="{{$member['id']}}">
            <tr><th></th><td><input type="submit" value="DELETE"></td></tr>
      
        </table>
    </form>
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection