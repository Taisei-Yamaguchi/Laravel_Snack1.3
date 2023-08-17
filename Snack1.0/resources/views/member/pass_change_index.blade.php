@extends('layouts.snackapp')

@section('title','Edit')

@section('content')
<a href="home" >Back</a><br>
<p>You can change your password.</p>
    <form action="member_pass_change" method="post"> <!-- ここ「/」の有無が大事 !-->
        <table>
            {{csrf_field()}}
            <tr><th>Please enter the current password.</th><td><input type="password" name="current_pass" placeholder="現在のパスワード"></td></tr>
            <tr><th></th><td></td></tr>
            <tr><th>Please enter the new password.</th><td><input type="password" name="pass" placeholder="5~20字"></td></tr>
            <tr><th>Please enter the new password again. (for confirmation)</th><td><input type="password" name="pass2" placeholder="5~20字"></td></tr>
            
            <tr><th></th><td><input type="submit" value="Change"></td></tr>
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