<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CAS Server</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    <link rel="stylesheet" href="{{ elixir('css/sb-admin-2.css') }}">
    <link rel="stylesheet" href="{{ elixir('css/metisMenu.css') }}">
    @yield('stylesheet')
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};</script>
</head>
<body v-cloak>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('admin_home', [], false) }}">PHP CAS</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{ route('home') }}"><i class="fa fa-user fa-fw"></i> @lang('admin.back_to_front')</a>
                </li>
                <li class="divider"></li>
                <li><a href="{{ cas_route('logout') }}"><i class="fa fa-sign-out fa-fw"></i> @lang('auth.logout')</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{ route('admin_home') }}"><i class="fa fa-dashboard fa-fw"></i> @lang('admin.menu.dashboard')</a>
                </li>
                <li>
                    <a href="{{ route('admin.user.index') }}"><i class="fa fa-user fa-fw"></i> @lang('admin.menu.users')</a>
                </li>
                <li>
                    <a href="{{ route('admin.service.index') }}"><i class="fa fa-list fa-fw"></i> @lang('admin.menu.services')</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>

@yield('content')

<script src="{{ elixir('js/common.js') }}"></script>
<script src="{{ elixir('js/metisMenu.js') }}"></script>
<script src="{{ elixir('js/admin/admin.js') }}"></script>
@yield('javascript')
<script>
    window.app = new Vue({
        el: 'body'
    });
</script>
</body>
</html>
