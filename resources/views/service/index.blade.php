@extends('templates.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/home/setting.css') }}">
@endsection
@section('content')
<div class="container">
    <h2 class="setting-title">Service</h2>
    <div class="float-right">
        <a href="{{ route('get_new_service_route', 0) }}" class="btn btn-primary">+ Add new service</a>
    </div>
    <div id="table-view">
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price ($)</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($service as $item)
                <form action="{{ route('post_delete_service_route', $item->id) }}" method="post" id="frm-del-{{$item->id}}">
                    @csrf
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td style="letter-spacing: 5px;">
                            <a id="btn-done" href="{{ route('get_new_service_route', $item->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- <button type="button" id="btn-edit" ><i class="fas fa-edit"></i></button> -->
                            <a href="#" class="btn setting-delete" data-id="{{ $item->id }}"><i class="fas fa-trash-alt text-danger"></i></a>
                        </td>
                    </tr>
                </form>
                <input type="hidden" value="{{ $item->id }}" name="service_id" id="service_id">
                @endforeach
                
                <div class="edit_page"></div>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.setting-delete').on('click', function (e) {
            e.preventDefault();
            
            var id = $(this).data('id');
            BootstrapDialog.confirm({
                title: 'Delete Service Setting',
                message: 'Do you really want to delete selected item? <br/>You can not restore after confirm it...',
                type: BootstrapDialog.TYPE_DANGER,
                callback: function(result) {
                    if(result) {
                        $('#frm-del-' + id).trigger('submit')
                    }
                }
            });
        });
    });
</script>
@endsection