@extends('layouts.snackapp')

@section('title','Register')

Registration Success!
@section('content')
    <ul><a href="../mypage/login">to Login screen...</a></ul>
    @if (isset($mess))
    {{$mess}}
    @endif
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection