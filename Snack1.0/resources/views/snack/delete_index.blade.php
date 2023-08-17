@extends('layouts.snackapp')

@section('title','Edit')

@section('content')
    
<a href="home">to Mypage...</a>
<p>Do you want to delete this snacks?</p>
    <form action="snack_delete" method="post" enctype="multipart/form-data"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            
            <tr><th>Name:</th><td>{{$item->name}}</td></tr>
            <tr><th>Company:</th><td>{{$item->company}}</td></tr>
            <tr><th>Type:</th><td>{{$item->type}}</td></tr>
            <tr><th>Country:</th><td>{{$item->country}}</td></tr>
            <tr><th>Coment:</th><td>{{$item->coment}}</td></tr>
            <tr><th>Image:</th><td><img src="../storage/snack_images/{{$item->image}}" width="70" height="85" alt=""></td></tr>
            <input type="hidden" name="snack_id" value="{{$item->id}}">
            <input type="hidden" name="snack_image" value="{{$item->image}}">
            <tr><th></th><td><input type="submit" value="DELETE"></td></tr>
      
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