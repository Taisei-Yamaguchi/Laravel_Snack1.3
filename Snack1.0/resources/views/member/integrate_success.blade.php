@extends('layouts.snackapp')

@section('title','Register')

@section('content')
    <ul><a href="../mypage/login">to Login screen...</a></ul>
    <h2>Integrate Success</h2>
    <table>
        <tr><th>Name</th><td>{{$name}}</td></tr>
        <tr><th>Mail</th><td>{{$mail}}</td></tr>
        <tr><th>Image</th><td><img src="../storage/member_images/{{$image}}" width='48' height='48' align='left'></td></tr>
    </table>
    
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection