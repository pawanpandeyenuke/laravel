@extends('layouts.app')
@section('title', 'Password Changed - ')
@section('content')

@include('panels.loginpopup')

<div class="container" style="margin-top:5%">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Password Reset Complete</div>
                <div class="panel-body">

                        <div class="alert alert-success" style="text-align: center;">
                            Congratulations your password has been changed succesfully.<br>
                            <div class="already-member">Please <a href="#" title="" data-toggle="modal" data-target="#LoginPop">Login</a> here to get started!</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
