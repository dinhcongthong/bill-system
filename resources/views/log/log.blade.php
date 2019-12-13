@extends('templates.master')

@section('stylesheets')
<style>
.table {margin-bottom: 0;}
</style>
@endsection

@section('content')
<div class="container" style="overflow: scroll;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>TIME (YYYY-MM-DD)</th>
                <th>DESCRIPTION</th>
                <th>USER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs->sortByDesc('id') as $log )
                <form action="{{ route('post_delete_service_route', $log->id) }}" method="post">
                    @csrf
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>
                            {{ $log->description }} <br>
                            <!-- { $log->description }} -->
                        </td>
                        <td>{{ $log->getUser->full_name }}</td>
                    </tr>
                </form>
                <input type="hidden" value="{{ $log->id }}" name="service_id" id="log_id">
            @endforeach
        </tbody>
    </table>
    
    {{-- 
                @foreach ($log->properties as $key => $value )
                    @foreach ($value as $a => $b)
                        <b>{{ $b["name_poste"] }}: </b>
                        

                        @foreach ($b as $b1 => $b2)
                            <!-- <u>{ $b1["name_poste"] }}</u> -->
                            <i>{{ $b1 }} : {{ $b2 }}, </i>
                        @endforeach
                    @endforeach
                @endforeach
                --}}

    {{-- 
    <div id="accordion">
    @foreach ($logs->sortByDesc('id') as $log)
        <div class="card">
            <div class="card-header" id="heading{{ $log->id }}" style="cursor: pointer;">
                <!-- <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $log->id }}" aria-expanded="true" aria-controls="{{ $log->id }}">
                  Log Item #1
                </button>
                </h5> -->
                <table class="table table-striped" data-toggle="collapse" data-target="#collapse{{ $log->id }}" aria-expanded="false" aria-controls="{{ $log->id }}">
                    <thead>
                        <tr>
                            <th>TIME (YYYY-MM-DD)</th>
                            <th>DESCRIPTION</th>
                            <th>USER</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>
                                {{ $log->description }} <br>
                                {{ $log->description }}
                            </td>
                            <td>{{ $log->getUser->full_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="collapse{{ $log->id }}" class="collapse show" aria-labelledby="heading{{ $log->id }}" data-parent="#accordion">
                <div class="card-body">
                    <tr>
                        <td>Old </td>
                        <td>New </td>
                    </tr>
                </div>

            </div>
        </div>
      
    @endforeach
    </div>
    --}}

</div>
@endsection