@extends('layouts.snackapp')

@section('title','Edit')

@section('content')
    <p>Update Success!</p>
    <ul><a href="../mypage/home">to Mypage...</a></ul>
    @if (isset($mess))
    {{$mess}}
    @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection