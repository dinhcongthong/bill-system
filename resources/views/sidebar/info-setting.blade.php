@extends('templates.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/home/setting.css') }}">
@endsection

@section('content')
<div class="container-fluid" style="overflow:scroll;">
    <h2 class="text-center my-5">Poste Information Setting</h2>
    <div class="mb-3 pb-2 pt-4">
        <a href="{{ route('get_new_info_setting_route', 0 ) }}" class="btn btn-primary rounded-pill shadow-none hover hover-scale-sm">+ Add new Poste</a>
    </div>
    <table class="table table-striped custom-table bg-white rounded-xl shadow-lg overflow-hidden">
        <thead class="text-white">
            <tr>
                <th>ID</th>
                <th class="text-left">Area label</th>
                <th class="text-left">Name</th>
                <th class="text-left">Addres</th>
                <th class="text-left">Email</th>
                <th>Phone</th>
                <th>Logo</th>
                <th>VAT rate</th>
                <th>Exchange rate</th>
                <th>Bank name</th>
                <th>Account bank name</th>
                <th>Account bank number</th>
                <th>Tax code</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($settings as $item)
            <form action="{{ route('post_clone_new_setting_route', $item->id) }}" method="post" id="frm-clone-{{ $item->id }}">
                @csrf
                <tr>
                    <td>{{ $item->id }}</td>
                    <td class="text-left fw-500">{{ $item->area_name }}</td>
                    <td class="text-left">{{ $item->name_poste }}</td>
                    <td class="text-left">{{ $item->address_poste }}</td>
                    <td class="text-left">{{ $item->email_poste }}</td>
                    <td>{{ $item->phone_poste }}</td>
                    <td><img src="{{ asset('area/' . $item->id . '.' . $item->path_logo_file) }}" alt="" style="max-width: 5vw;"></td>
                    <td>{{ $item->vat_rate }}</td>
                    <td>{{ $item->exchange_rate }}</td>
                    <td>{{ $item->bank_name }}</td>
                    <td>{{ $item->acc_bank_name }}</td>
                    <td>{{ $item->acc_bank_number }}</td>
                    <td>{{ $item->tax_code }}</td>
                    <td>
                        <a href="{{ route('get_new_info_setting_route', $item->id) }}" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn setting-clone" data-id="{{ $item->id }}"><i class="fas fa-clone" title="Clone"></i></a>
                    </td>
                </tr>
            </form>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.setting-clone').on('click', function (e) {
            e.preventDefault();
            
            var id = $(this).data('id');
            BootstrapDialog.confirm({
                title: 'Duplicate Information Setting',
                message: 'Do you really want to duplicate selected item? <br/>You can not delete after confirm it...',
                type: BootstrapDialog.TYPE_WARNING,
                callback: function(result) {
                    if(result) {
                        $('#frm-clone-' + id).trigger('submit');
                    }
                }
            });
        });
    });
</script>
@endsection