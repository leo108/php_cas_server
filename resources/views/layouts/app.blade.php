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
    @yield('stylesheet')
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};</script>
</head>
<body v-cloak>
    @yield('content')
    <script src="{{ elixir('js/common.js') }}"></script>
    @yield('javascript')
    <script>
        window.app = new Vue({
            el: 'body'
        });
    </script>
</body>
</html>
