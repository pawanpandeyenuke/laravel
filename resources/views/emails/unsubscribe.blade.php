@extends('layouts.app')

@include('panels.meta-data')
@section('title', 'Unsubscribe')
@section('content')
<div class="default-page">
    <div class="container" style="margin-top:5%">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Unsubscribe from social invitations</div>
                    <div class="panel-body">

                        @if (Session::has('success'))
                            <div class="alert alert-success">{!! Session::get('success') !!}</div>

                            <div>
                                <p>
                                    If you want to subscribe again. Please <a href="{{ url('subscribe?email='.$email) }}">click here</a>.
                                </p>
                            </div>

                        @else
                            <form class="form-horizontal" role="form" method="POST">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <p>
                                            We want to stay in touch, but only in ways that you find helpful.
                                            </br></br>
                                            The e-mail you received contained information for the social inviation. Click the "Unsubscribe" button to stop receiving e-mail notifications from social invitaion. If you unsubscribe, you will stop receiving messages to {{ $email }}.
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            Unsubscribe
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
