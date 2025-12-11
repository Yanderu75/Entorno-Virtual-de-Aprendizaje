@extends('adminlte::master')

@section('adminlte_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @yield('css')
@stop

@section('classes_body', 'login-page')

@section('body')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>EVA</b> - Entorno Virtual</a>
    </div>

    <!-- The content yielded here should be the card itself or form inside card -->
    <!-- existing views define a .card. Let's just yield content. -->
    @yield('content')
</div>
@stop

@section('adminlte_js')
    @yield('js')
@stop
