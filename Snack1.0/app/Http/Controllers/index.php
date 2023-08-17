@extends('layouts.snackapp')

@section('title','Login')
@section('content')
    <form action="/snack/login" method=post>
        <table>
            {{csrf_field()}}
            <tr><th>mail:</th><td><input type="text" name="mail"></td></tr>
            <tr><th>password:</th><input type="password" name="pass"></td></tr>
            <tr><th></th><td><input type="submit" value="send"></td></tr>
        </table>
    </form>
@endsection

@section('footer')
copyright 2023 yamaguchi.
@endsection