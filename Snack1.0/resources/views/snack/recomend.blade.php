@extends('layouts.snackapp')

@section('title','Recomend')

@section('content')
    <ul><a href="home">to Mypage...</a></ul>
    @if (isset($mess))
    {{$mess}}
    @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection