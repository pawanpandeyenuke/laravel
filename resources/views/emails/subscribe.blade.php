@extends('layouts.app')

@include('panels.meta-data')
@section('title', 'Subscribe')
@section('content')
<div class="default-page">
    <div class="container" style="margin-top:5%">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Subscribe for social invitations</div>
                    <div class="panel-body">

                        @if (Session::has('success'))
                            <div class="alert alert-success">{!! Session::get('success') !!}</div>
                        @endif
                        <div>
                            <p>
                                If you want to unsubscribe again. Please <a href="{{ url('unsubscribe?email='.$email) }}">click here</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
