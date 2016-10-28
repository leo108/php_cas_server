@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            {{ config('cas_server.site_name') }}
                        </h3>
                    </div>
                    <div class="panel-body">
                        @if ($errors->has('global'))
                            <div class="alert alert-danger">{{ $errors->first('global') }}</div>
                        @endif
                        <div class="alert alert-success">
                            @lang('auth.logged_in_as', ['name' => Auth::user()->name])
                        </div>
                        <button class="btn btn-primary pull-left" id="btn_change_pwd">@lang('auth.change_pwd')</button>
                        @if(Auth::user()->admin)
                            <a href="{{ route('admin_home') }}" class="btn-success btn col-md-offset-1">@lang('admin.system_manage')</a>
                        @endif
                        <button class="btn btn-danger pull-right" id="btn_logout">@lang('auth.logout')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="change-pwd-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('auth.change_pwd')</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="old-pwd" class="col-sm-4 control-label">@lang('auth.old_pwd')</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="old-pwd"
                                       placeholder="@lang('auth.old_pwd')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-pwd" class="col-sm-4 control-label">@lang('auth.new_pwd')</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="new-pwd"
                                       placeholder="@lang('auth.new_pwd')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-pwd2" class="col-sm-4 control-label">@lang('auth.new_pwd2')</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="new-pwd2"
                                       placeholder="@lang('auth.new_pwd2')">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.close')</button>
                    <button type="button" class="btn btn-primary" id="btn-save-pwd">@lang('common.ok')</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('javascript')
    @include('vendor.bootbox')
    <script>
        Laravel.routes = {
            'logout': '{{ cas_route_uri('logout') }}',
            'change_pwd': '{{ route_uri('password.change.post') }}'
        };
        Laravel.lang = {
            'message.confirm_logout': '@lang('message.confirm_logout')'
        };
    </script>
    <script src="{{ elixir('js/front/home.js') }}"></script>
@endsection

