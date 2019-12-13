@extends('templates.master')
@section('stylesheets')
<style>
    #ads-new:focus {
        outline: none;
    }
    .disabled-div {
        pointer-events: none;
        opacity: 0.4;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/home/setting.css') }}">
@endsection
@section('content')

<div class="container" style="overflow:scroll;">
    <h2 class="text-center setting-title">{{ (isset($contract_id) ? 'Edit Contract(' : 'Add New Contract (') . $client_name . ')'  }}</h2>
    <hr>
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
    </div>
    <form id="contract-steps" action="{{ route('post_new_contract_route', 0) }}" method="POST" class="text-center was-validated" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="contract_id" id="contract_id" value="{{ isset($contract_id) ? $contract_id : '' }}">
        <input type="hidden" name="client_id" value="{{ isset($client_id) ? $client_id : '' }}">
        
        <div class="col-12" id="area_vat">
            <div class="form-row">
                <div class="form-group col-4 text-left">
                    <label for="vat_status">VAT status</label>
                    <input type="hidden" name="old_vat_status" value="{{ isset($vat_status) ? $vat_status : ''}}">
                    <div class="input-group flex-grow-1">
                        <select name="vat_status" class="custom-select">
                            <option value="no" {{ $vat_status == 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ $vat_status == 'yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-4 text-left">
                    <label for="contract_start_date">Contract start date</label>
                    <div class="input-group flex-grow-1">
                        <input type="hidden" name="old_contract_start_date" value="{{ isset($contract_start_date) ? $contract_start_date : ''}}">
                        <input type="text" class="form-control datepicker" name="contract_start_date" value="{{$contract_start_date}}" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-row text-left">
                <div class="form-group col-12 col-md-6 col-lg" id="contract_code">
                    <label for="contract_code">Contract code</label>
                    <input type="hidden" name="old_contract_code" value="{{ isset($contract_code) ? $contract_code : ''}}">
                    <input type="text" class="form-control" name="contract_code" value="{{$contract_code}}" placeholder="Contract code">
                </div>
                @if (isset($status))
                <div class="form-group col-12 col-md-6 col-lg">
                    <label for="status">Status</label>
                    <input type="hidden" name="old_status" value="{{ $status }}">
                    <select name="status" id="status" class="custom-select" required>
                        <option value="doing" {{ $status == 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="sent" {{ $status == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="saved" {{ $status == 'saved' ? 'selected' : '' }}>Saved</option>
                        <option value="destroyed" {{ $status == 'destroyed' ? 'selected' : '' }}>Destroyed</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                @endif
            </div>
        </div>
        <div class="col-12" id="phone_name">
            <div class="form-row text-left">
                <div class="form-group col-12 col-md-6 col-lg mb-4">
                    <label for="representative">Representative</label>
                    <input type="hidden" name="old_representative" value="{{ isset($representative) ? $representative : ''}}">
                    <input type="text" id="representative" name="representative" value="{{ isset($representative) ? $representative : '' }}" class="form-control" required>
                </div>
                <div class="form-group col-12 col-md-6 col-lg mb-4">
                    <label for="company_phone">Company's phone</label>
                    <input type="hidden" name="old_company_phone" value="{{ isset($company_phone) ? $company_phone : ''}}">
                    <input type="text" name="company_phone" value="{{$company_phone}}" class="form-control" required>
                </div>
                <div class="form-group col-12 col-md-6 col-lg mb-4">
                    <label for="poste_in_charge">Poste in charge</label>
                    <input type="hidden" name="old_poste_in_charge" value="{{ isset($poste_in_charge) ? $poste_in_charge : ''}}">
                    <input type="text" id="poste_in_charge" name="poste_in_charge" value="{{$poste_in_charge}}" class="form-control" required>
                </div>
            </div>
        </div>
        
        @if(in_array(Auth::user()->id, $permission))
        <hr>
        <div class="col-12 text-left">
            <a href="#" id="add-new-service" class="btn btn-light text-left">+ Add new service</a>
        </div>
        <div class="row p-3" id="add-service-menu">
            <div class="col-4">
                <label for="service_name">Service name</label>
                <input type="text" class="form-control" name="service_name" id="service_name">
            </div>
            <div class="col-4">
                <label for="service_price">Service price ($)</label>
                <input type="number" class="form-control" name="service_price" id="service_price">
            </div>
            <div class="col-2 pt-4">
                <a href="#" class="btn btn-success" id="save-service"><i class="fas fa-save mr-1"></i> Save service</a>
            </div>
        </div>
        @endif
        
        <hr>
        <div class="col-12" id="service_menu">
            {{-- check service menu --}}
            @if (isset($contract_service))
            @foreach ($contract_service as $key => $cs_item)
            <div id="service-menu" class="text-left">
                <div class="ads-item">
                    <div class="form-row" id="form-row-service-item-{{$key}}">
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="service_id">Service name</label>
                            <select id="service_id" name="service_id[]" class="custom-select" required>
                                @if (isset($service_list))
                                @foreach ($service_list as $item)
                                <option value="{{ $item->id }}" {{ $cs_item->service_id == $item->id ? 'selected' : '' }}>{{ $item->name }} (${{ $item->price }})</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="service_start_date">Service start date</label>
                            <input type="text" name="service_start_date[]" class="form-control datepicker" value="{{ $cs_item->service_start_date }}" placeholder="YYYY-MM-DD" required>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="time_service">QTY</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">months</span>
                                </div>
                                <input type="number" min="1" class="form-control" id="time_service" name="time_service[]" value="{{$cs_item->quantity_months}}" placeholder="Duration" required>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="ads-discount">Discount rate</label>
                            <input type="number" name="discount_rate[]" value="{{$cs_item->discount_rate}}" class="form-control" id="ads-discount" placeholder="%">
                        </div>
                        @if ($key > 0)
                        <a href="#" class="item-delete" title ="Delete this item!" data-count={{ $key }}>
                            <i class="fas fa-times-circle text-danger" style="font-size: 18px;"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div id="service-menu" class="text-left">
                <div class="ads-item">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="service_id">Service name</label>
                            <select id="service_id" name="service_id[]" class="custom-select" required>
                                @if (isset($service_list))
                                @foreach ($service_list as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} (${{ $item->price }})</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="service_start_date">Service start date</label>
                            <input type="text" name="service_start_date[]" class="form-control datepicker" placeholder="YYYY-MM-DD" required>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="time_service">QTY</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">months</span>
                                </div>
                                <input type="number" min="1" class="form-control" id="time_service" name="time_service[]" placeholder="Duration" required>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg">
                            <label for="ads-discount">Discount rate</label>
                            <input type="text" name="discount_rate[]" class="form-control" id="ads-discount" placeholder="%">
                        </div>
                    </div>
                </div>
            </div>
            @endif
            {{-- endif check service menu --}}
            <button type="button" class="btn-add-ads font-weight-bold" id="ads-new"><i class="lnr lnr-file-add mr-3"></i>Add Ads item</button>
        </div>
        <div class="col-12" id="payment">
            <div class="form-row text-left">
                <div class="form-group col-12 col-lg">
                    <label for="payment_times">Payment Schedule</label>
                    <div class="d-flex flex-wrap flex-lg-nowrap">
                        <div class="input-group flex-grow-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Pay in</span>
                            </div>
                            <input type="hidden" name="old_end_date" value="{{ isset($end_date) ? $end_date : '' }}">
                            <input type="number" min="1" class="form-control" id="end_date" value="{{$end_date}}" name="end_date" placeholder="Payment duration" required>
                            <div class="input-group-append">
                                <span class="input-group-text rounded-right-lg-0">months</span>
                            </div>
                        </div>
                        <div class="input-group flex-shrink-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text rounded-left-lg-0">divided into</span>
                            </div>
                            <input type="hidden" name="old_payment_times" value="{{ isset($payment_times) ? $payment_times : '' }}">
                            <input type="number" min="1" class="form-control" id="payment_times" name="payment_times" value="{{$payment_times}}" placeholder="Payment times" required>
                            <div class="input-group-append">
                                <span class="input-group-text">times</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <div class="ads-item">
                <div class="form-row">
                    <div class="form-group col-12 col-md-6 col-lg">
                        <input type="hidden" value="{{ isset($default_store_branch_id) ? $default_store_branch_id : '' }}" name="old_store_branch_id">
                        <label for="store_branch_id">Store branch</label>
                        <select class="selectpicker" multiple data-live-search="true" name="store_branch_id[]">
                            @if(isset($store_branch_id) && isset($store_branch_list))
                            @foreach ($store_branch_list as $item)
                            @if( in_array($item->id, $store_branch_id) )
                            <option value="{{ $item->id }}" selected>{{ $item->name }} ( {{ $item->address }} )</option>
                            @else
                            <option value="{{ $item->id }}">{{ $item->name }} ( {{ $item->address }} )</option>
                            @endif
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg">
                        <input type="hidden" name="old_payment_type" value="{{ isset($payment_type) ? $payment_type : '' }}">
                        <label for="payment_type">Payment types</label>
                        <select id="service_id" class="custom-select" name="payment_type">
                            <option value="transfer">Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- upload file --}}
        <h4 class="my-4 text-left">Upload scan file</h4>
        <div id="menu-file">
            @if (isset($scan_file))
            @foreach ($scan_file as $item)
            <div class="form-row my-2">
                <a href="{{ asset('scan_files/' . $item->name . '.' . $item->path) }}" id="scanfile-{{$item->id}}" target="_blank">{{ $item->name . '.' . $item->path }}</a>
                <a href="#" class="item-file ml-2" id="file-delete-{{ $item->id }}" data-id="{{ $item->id }}"><i class="fas fa-minus-circle text-danger"></i></a>
            </div>
            @endforeach
            @endif
            <div class="form-row">
                <div class="col-6">
                    <input type="file" class="form-control" name="scan_file[]">
                </div>
            </div>
        </div>
        <div class="col-6 pl-0 text-left py-3">
            <a href="#" class="btn btn-info" id="add-more-file">Add more</a>
        </div>
        
        <div class="dropdown-divider mx-n5"></div>
        
        <h4 class="my-5">Contract note</h4>
        <div class="form-group">
            <textarea class="form-control" name="contract_note" rows="5">{{ $contract_note  }}</textarea>
        </div>
        <div class="text-center py-4">
            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Save</button>
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/contract.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.datepicker').datetimepicker({
            format:'YYYY-MM-DD'
        });
    });
</script>
@endsection
