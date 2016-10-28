@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        {{ config('cas_server.site_name') }}
                        <span class="pull-right"><a href="{{ cas_route('login.get') }}">@lang('auth.login')</a></span>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-danger">@lang('auth.logged_out')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
