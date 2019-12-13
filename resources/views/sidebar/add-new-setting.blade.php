@extends('templates.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/home/setting.css') }}">
@endsection

@section('content')
<div class="flex-fill p-3">
    <h2 class="container setting-title">{{ isset($id) ? 'Edit Poste Information Setting' : 'Add New Poste Information Setting' }}</h2>
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
            <form action="{{ route('post_new_info_setting_route') }}" method="POST" class="was-validated" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="setting_id" value="{{ isset($id) ? $id : 0 }}">
                <div class="py-3">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_name_poste" value="{{ isset($name_poste) ? $name_poste : '' }}">
                            <label class="col-3" for=""> Name</label>
                            <input type="text" name="name_poste" value="{{ isset($name_poste) ? $name_poste : '' }}" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_area_name" value="{{ isset($area_name) ? $area_name : '' }}">
                            <label class="col-3" for="">Area</label>
                            <input type="text" name="area_name" value="{{ isset($area_name) ? $area_name : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="parent_id" value="{{ isset($parent_id) ? $parent_id : '' }}">
                            <label class="col-3" for="">Belong to</label>
                            <select name="parent_id" id="status" class="custom-select" required>
                                <option value="0">none</option>
                                @if (isset($parent))
                                
                                @foreach ($parent as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $parent_id ? 'selected' : ''  }}>{{ $item->area_name  }}</option>
                                @endforeach
                                
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_vat_rate" value="{{ isset($vat_rate) ? $vat_rate : '' }}">
                            <label class="col-3">VAT rate</label>
                            <input type="number" name="vat_rate" value="{{ isset($vat_rate) ? $vat_rate : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_tax_code" value="{{ isset($tax_code) ? $tax_code : '' }}">
                            <label class="col-3">Tax code</label>
                            <input type="text" name="tax_code" value="{{ isset($tax_code) ? $tax_code : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_address_poste" value="{{ isset($address_poste) ? $address_poste : '' }}">
                            <label class="col-3">Address</label>
                            <input type="text" name="address_poste" value="{{ isset($address_poste) ? $address_poste : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_phone_poste" value="{{ isset($phone_poste) ? $phone_poste : '' }}">
                            <label class="col-3">Phone</label>
                            <input type="number" name="phone_poste" value="{{ isset($phone_poste) ? $phone_poste : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_email_poste" value="{{ isset($email_poste) ? $email_poste : '' }}">
                            <label class="col-3">Email</label>
                            <input type="text" name="email_poste" value="{{ isset($email_poste) ? $email_poste : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <input type="hidden" name="old_exchange_rate" value="{{ isset($exchange_rate) ? $exchange_rate : '' }}">
                            <label class="col-3">Exchange rate: </label>
                            <input type="number" name="exchange_rate" value="{{ isset($exchange_rate) ? $exchange_rate : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="col-3">Bank name: </label>
                            <input type="text" name="bank_name" value="{{ isset($bank_name) ? $bank_name : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="col-5">Account bank name: </label>
                            <input type="text" name="acc_bank_name" value="{{ isset($acc_bank_name) ? $acc_bank_name : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="col-5">Account bank number: </label>
                            <input type="number" name="acc_bank_number" value="{{ isset($acc_bank_number) ? $acc_bank_number : '' }}" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="col-3">Logo</label><br>
                            @if (isset($path_logo_file) && isset($area_name))
                            <input type="hidden" name="old_path_logo_file" value="{{ $path_logo_file }}" class="form-control">
                            <input type="file" name="path_logo_file" class="form-control">
                            <img class="mh-100 m-auto img-setting-preview" src="{{ asset('area/' . $id . '.' . $path_logo_file) }}" alt="" style="width:25%">
                            @else
                            <input type="file" name="path_logo_file" class="form-control" required>
                            <img class="mh-100 m-auto img-setting-preview" src="{{ asset('images/no-image-6x4.png') }}" alt="" style="width:25%">
                            @endif
                            <input type="hidden" name="check_logo" id="check_logo">
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('input[name="path_logo_file"]').off('change').on('change', function (e) {
        img_route_preview(this)
    });
    
    function img_route_preview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('.img-setting-preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
            $('#check_logo').val(1);
        }
    }
</script>
@endsection

