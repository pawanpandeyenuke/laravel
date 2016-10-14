@extends('layouts.app')

@include('panels.meta-data')

@section('title', 'Unsubscribe')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 mt-20">
            <div class="panel panel-default">
                <div class="panel-heading">Unsubscribe from Forum notifications</div>
                <div class="panel-body">
                    @if (Session::has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @else
                        <p class='text-center'>Are you sure want to unsuscribe?</p>
                        <div class='row'>
                            <div class='col-md-6 text-right'>
                                <a href="{{ url()->current().'?token='.$_GET['token'].'&action=yes' }}" class='btn btn-primary'><i class='fa fa-check'></i> Yes</a>
                            </div>
                            <div class='col-md-6'>
                                <a href="{{ url('/') }}" class='btn btn-primary'><i class='fa fa-close'></i> No</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection