@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {!! csrf_field() !!}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}" style="display:none">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="hidden" class="form-control" name="email" value="{{ $email or old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" id="resetpassword1">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                    <div class="check-cont show-pw">
                                        <input type="checkbox"  name="checkboxG2" id="checkboxG21" class="css-checkbox password-eye" onchange="document.getElementById('resetpassword1').type = this.checked ? 'text' : 'password'"/>
                                        <label for="checkboxG21" class="css-label">show</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirmation" id="resetpassword2">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                    <div class="check-cont show-pw">
                                        <input type="checkbox"  name="checkboxG2" id="checkboxG22" class="css-checkbox password-eye" onchange="document.getElementById('resetpassword2').type = this.checked ? 'text' : 'password'"/>
                                        <label for="checkboxG22" class="css-label">show</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-refresh"></i>&nbsp;&nbsp;Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
