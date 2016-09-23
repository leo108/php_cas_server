@extends('layouts.admin')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">@lang('admin.menu.dashboard')</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $user['total'] }}</div>
                                <div>@lang('admin.dashboard.user_total')</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                {{ $user['active'] }} @lang('admin.dashboard.user_active')
                            </div>
                            <div class="col-xs-6">
                                {{ $user['admin'] }} @lang('admin.dashboard.user_admin')
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.user.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">@lang('admin.dashboard.view_details')</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- /.col-lg-6 -->
            <div class="col-lg-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $service['total'] }}</div>
                                <div>@lang('admin.dashboard.service_total')</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                {{ $service['enabled'] }} @lang('admin.dashboard.service_enabled')
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.service.index') }}">
                        <div class="panel-footer">
                            <span class="pull-left">@lang('admin.dashboard.view_details')</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
@endsection
