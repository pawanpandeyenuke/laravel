@extends('layouts.app')


@section('content')

<div class="container" style="margin-top:5%">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Account Verification</b></div>
                <div class="panel-body">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="form-group">
                            <div class="col-md-12" style="text-align:center; padding: 5%; font-size: 13pt;"> 
                                Your account has been successfully verified! Please click on below button to login.
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12"  style="text-align:center; ">
                                <button data-toggle="modal" data-target="#LoginPop" class="btn btn-primary">
                                   Login
                                </button>
                            </div>
                        </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

@endsection