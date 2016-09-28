@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Central Authentication Service</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning">
                            @lang('message.cas_redirect_warn', ['url' => $service])
                        </div>
                        <div>
                            <a class="btn btn-danger pull-left" href="{{ route('home') }}">@lang('common.abort')</a>
                            <a class="btn btn-primary pull-right" href="{{ $url }}">@lang('common.ok')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
