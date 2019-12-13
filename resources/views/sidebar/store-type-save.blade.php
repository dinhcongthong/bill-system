@extends('templates.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/home/setting.css') }}">
@endsection

@section('content')

<div class="container p-3">
    <h2 class="container setting-title">{{ isset($id) ? 'Edit Store Type' : 'Add New Store Type' }}</h2>
    <div class="row">
        <div class="col-12">
            @if ($errors->any())
            <div class="alert alert-danger mb-1">
                <ul class="mb-0 w-100">
                    @foreach ($errors->all() as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('post_save_store_type_setting_route') }}" method="POST" class="was-validated">
                @csrf
                <div class="py-3">
                    <input type="hidden" value="{{ isset($old_name) ? $old_name : '' }}" name="old_name">
                    <input type="hidden" value="{{ isset($id) ? $id : 0 }}" name="id">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label class="col-1" for=""> Name:</label> <input type="text" name="name" value="{{ isset($name) ? $name : '' }}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            </form>
        </div>
    </div>
</div>

@endsection

