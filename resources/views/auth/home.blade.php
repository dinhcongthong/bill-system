@extends('templates.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    <div class="pull-right">

        <form action="{{ route('post_logout_route') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Logout</button>
        </form>

    </div>
    <div style="padding-top: 30%">
        <a href="{{ route('get_client_index_route') }}" class="btn btn-primary btn-lg btn-block">Clients</a>
        <a href="{{ route('get_contract_index_route') }}" class="btn btn-secondary btn-lg btn-block">Contract</a>
        <a href="{{ route('get_service_index_route') }}" class="btn btn-info btn-lg btn-block">Service</a>
    </div>
</div>
@endsection
