@extends('templates.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/home/setting.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="setting-title"> Menu Setting</h2>
    <div class="row text-center pt-5">
        <div class="col-3">
            <a href="{{ route('get_service_index_route') }}" class="btn btn-outline-primary btn-lg">Service setting</a>
        </div>
        <div class="col-3">
            <a href="{{ route('get_store_type_index_route') }}" class="btn btn-outline-success btn-lg">Store type setting</a>
        </div>
        <div class="col-3">
            <a href="{{ route('get_info_setting_route') }}" class="btn btn-outline-secondary btn-lg">Poste Information Setting</a>
        </div>
        <div class="col-3">
            <a href="{{ route('get_log_index_route') }}" class="btn btn-outline-warning btn-lg">View Activity Logs</a>
        </div>
    </div>
</div>
@endsection