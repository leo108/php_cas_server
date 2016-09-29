@extends('layouts.app')

@section('content')
<div id="message" class="">
    <div class="flex-center full-height">
        <div class="content">
            <div class="title m-b-md">
                {!! $message !!}
            </div>

            @if($subMsg)
                <div class="sub-title m-b-md">
                    {!! $subMsg !!}
                </div>
            @endif

            @if($btnName)
            <div class="links">
                <a href="{{ $btnLink }}" class="btn btn-primary">{{ $btnName }}</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection