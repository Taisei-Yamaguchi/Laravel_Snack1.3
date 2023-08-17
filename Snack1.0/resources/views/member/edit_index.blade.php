@extends('layouts.snackapp')

@section('title','Edit')

@section('content')
<a href="home" >Back</a><br>
<p>You can change your name and icon image.</p>
    <form action="member_edit" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            
            <tr><th>ID:</th><td>{{$member['id']}}</td></tr>
            <tr><th>Name:</th><td><input type="text" name="name" value="{{$member['name']}}"></td></tr>
            <tr><th>Mail:</th><td>{{$member['mail']}}</td></tr>
            <tr><th>Image:</th><td>Current image<img src="../storage/member_images/{{$member['image']}}" width="70" height="85" alt="">
                <br><br><input type="file" name="image"></td></tr>
            <input type="hidden" name="id" value="{{$member['id']}}">
            <tr><th></th><td><input type="submit" value="EDIT"></td></tr>
      
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