@extends('templates.master')
@section('content')
<div id="page-main-container" class="col p-3">
    <div id="alphabet-nav" class="bg-grey d-flex flex-nowrap mb-3">
        
        
        @foreach ($alphabet as $key => $item)
        
        @if(count($clients[$item]) > 0)
        @if (is_numeric($item))
        <a href="#group-no" class="nav-link text-dark btn-circle">#</a>
        @else
        <a href="#group-{{$item}}" class="nav-link text-dark btn-circle">{{ strtoupper($item) }}</a>
        @endif
        @endif
        @endforeach
        
    </div>
    
    <div id="main-content-wrapper" data-spy="scroll" data-target="#alphabet-nav" data-offset="140">
        
        @if ($errors->any())
        <div class="alert alert-danger mb-1">
            <ul class="mb-0 w-100">
                @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="accordion clients-list">
            
            @foreach ($alphabet as $key => $item)
            @if(count($clients[$item]) > 0)
            
            
            <div id="group-{{ is_numeric($item) ? 'no' : $item }}">
                
                @foreach ($clients[$item]->sortByDesc('id') as $client)
                <div class="card client-item {{$client->status}}" id="client-id-{{$client->id}}">
                    <div class="card-header client-info" data-toggle="collapse" data-target="#collapse_{{$client->id}}" aria-expanded="true" aria-controls="collapse_{{$client->id}}">
                        <h6 class="border-right m-0 pr-3">
                            <strong class="text-truncate p-0">{{ $client->name }}</strong>
                        </h6>
                        <div class="position-relative mr-auto px-3">
                            <span class="client-numbers-of-contract" id="total-contract-{{$client->id}}">{{ count($client->getContract) }} contracts</span>
                            <div class="client-btn-group flex-nowrap">
                                @if ($client->status != 'saved')
                                <a id="client-options" href="#" class="btn btn-circle client-save tooltip-on" data-id="{{ $client->id }}" title="Save this Client">
                                    <i class="lnr lnr-folder-download h5 m-0"></i>
                                </a>
                                
                                <form action="{{ $client->status == 'banned' ? route('post_remove_the_ban_client_route', $client->id) : route('post_baner_client_route', $client->id) }}" method="post">
                                    @csrf
                                    @if ($client->status == 'banned')
                                    <button id="client-options" type="submit" class="btn btn-circle tooltip-on" title="Unban">
                                        <i class="lnr lnr-return h5 m-0 text-success"></i>
                                    </button>
                                    @else
                                    <button id="client-options" type="submit" class="btn btn-circle tooltip-on" title="Ban this Client">
                                        <i class="lnr lnr-prohibited h5 m-0 text-danger"></i>
                                    </button>
                                    @endif
                                </form>
                                @endif
                                
                                <a id="client-options" href="{{ route('get_edit_client_route', $client->id) }}" class="btn btn-circle tooltip-on client-edit" data-id="{{ $client->id }}" title="Edit this Client">
                                    <i class="lnr lnr-pencil3"></i>
                                </a>
                            </div>
                        </div>
                        <a id="client-options" href="{{ route('get_new_contract_route', $client->id) }}" class="btn bg-primary rounded-pill d-flex align-items-center text-white my-2 py-2">
                            <i class="lnr lnr-file-add h4 mb-0 mr-2"></i>Add Contract
                        </a>
                    </div>
                    
                    <div id="collapse_{{$client->id}}" class="collapse {{ count($client->getContract) > 0 ? 'show' : 'no-item'}}" data-parent=".clients-list">
                        <div class="card-body">
                            @foreach($client->getContract->sortByDesc('id') as $contract)
                            <div class="contract-item" id="contract-{{ $contract->id }}">
                                <div class="contract-item-header d-flex flex-wrap position-relative">
                                    <h5 class="contract-title text-truncate text-primary">Ads Contract<br>
                                        <small class="text-black-50">from {{ date("Y-m-d", strtotime($contract->start_date) ) }} to {{ date("Y-m-d", strtotime($contract->end_date) ) }}</small>
                                    </h5>
                                    <div class="contract-btn-group">
                                        <a href="{{ route('get_edit_contract_route', [$contract->id, $client->id]) }}" class="btn btn-outline-primary rounded-pill btn-sm mr-3"><i class="lnr-register h5 m-0"></i> Edit</a>
                                        <a href="#" class="contract-delete btn btn-outline-danger btn-sm rounded-pill mr-3" data-id="{{ $contract->id }}" data-client="{{ $client->id }}"><i class="lnr-shredder h5 m-0"></i> Remove</a>
                                    </div>
                                    <ul id="contract-info-list" class="list-group list-group-horizontal ml-auto">
                                        <li class="list-group-item">
                                            <strong>Type:</strong><br>
                                            {{ $contract->store_type }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Incharger:</strong><br>
                                            {{ $contract->poste_in_charge }} さん
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Contract code:</strong><br>
                                            <span class="text-uppercase">#{{ $contract->contract_code }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Client:</strong><br>
                                            {{ $contract->representative }} さん
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Client Phone:</strong><br>
                                            {{ $client->phone_client_in_charge }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>TAX (VAT):</strong><br>
                                            <span class="text-uppercase">{{ $contract->vat_status }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Payment type:</strong><br>
                                            <span class="text-uppercase">{{ $contract->payment_type }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Contract Total:</strong><br>
                                            <span class="text-warning h5 m-0">${{ $contract->payment_money }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="contract-invoices-list">
                                    
                                    @foreach($contract->getInvoice->sortBy('id') as $invoice)
                                    @php
                                    $data = [
                                    'client_id' => $client->id,
                                    'contract_id' => $contract->id,
                                    'invoice_id' => $invoice->id,
                                    ];
                                    
                                    $now = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now() );
                                    @endphp
                                    
                                    <div class="invoice-item d-flex flex-nowrap justify-content-between">
                                        <a href="{{ URL::to('/pdf/view/' . json_encode($data) ) }}" class="btn d-flex flex-nowrap align-items-center flex-grow-1">
                                            @php 
                                            $now = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now() );
                                            @endphp
                                            
                                            @if( $now->lt($invoice->start_date))
                                            <small class="invoice-status-{{ $invoice->id }} badge {{ $now->diffInDays($invoice->start_date) < 7 ? 'badge-warning' : 'badge-light'}}">
                                                {{ $invoice->start_date }}
                                            </small>
                                            @else
                                            <small class="invoice-status-{{ $invoice->id }} badge {{ $invoice->payment_status == '0' ? 'badge-danger' : 'badge-light'}} " >
                                                {{ $invoice->start_date }}
                                            </small>
                                            @endif
                                            <span class="order-3 ml-4">${{ $invoice->grand_total_money }}</span>
                                        </a>
                                        <button type="button" class="tick_exported_invoice tooltip-on btn {{ $invoice->exported == '1' ? 'active' : '' }}" id="tick-receipt" data-id="{{ $invoice->id }}" data-toggle="button" title="Red Invoice">
                                            <i class="lnr-receipt h4 mb-0 mr-2"></i>
                                        </button>
                                        
                                        <button type="button" class="tick_invoice tooltip-on btn {{ $invoice->payment_status == '1' ? 'active' : '' }}" id="tick-invoice" data-id="{{ $invoice->id }}" data-toggle="button" title="Payment">
                                            <i class="lnr-cash-dollar h4 mb-0 mr-2"></i>
                                        </button>
                                        
                                        
                                        {{-- log --}}
                                        <input type="hidden" name="invoice_id" value="{{ isset($invoice->id) ? $invoice->id : '' }}">
                                        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                                        <input type="hidden" name="payment_type" value="{{ $contract->payment_type }}">
                                        <input type="hidden" name="payment_status" value="{{ $invoice->payment_status }}">
                                        <input type="hidden" name="payment_note" value="{{ $contract->payment_note }}">
                                        <input type="hidden" name="time" value="{{ $invoice->time }}">
                                        <input type="hidden" name="exported" value="{{ $invoice->exported }}">
                                        
                                        <input type="hidden" name="old_payment_type" value="{{ isset($invoice->payment_type) ? $invoice->payment_type : '' }}">
                                        <input type="hidden" name="old_payment_status" value="{{ isset($invoice->payment_status) ? $invoice->payment_status : '' }}">
                                        <input type="hidden" name="old_payment_note" value="{{ isset($invoice->payment_note) ? $invoice->payment_note : '' }}">
                                        <input type="hidden" name="old_time" value="{{ isset($invoice->time) ? $invoice->time : '' }}">
                                        <input type="hidden" name="old_exported" value="{{ isset($invoice->exported) ? $invoice->exported : '' }}">
                                        
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                        
                                        {{-- /log --}}
                                        
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
