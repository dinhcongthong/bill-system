
<form id="client-steps" class="swiper-container text-center was-validated" action="{{ route('post_new_client_route') }}" method="POST">
    @csrf
    <input type="hidden" id="client_id_home" name="client_id" value="{{ isset($client_id) ? $client_id : 0 }}">
    <div class="progress flex-shrink-1 text-center mb-4"></div>
    <div class="swiper-wrapper flex-grow-1 mb-4">
        <div class="swiper-slide bg-white rounded p-5">
            <h4 class="mb-5">Where is this Client?</h4>
            @foreach ($area_list as $key => $area_item)
            <div class="custom-control custom-radio mb-4">
                <input type="radio" id="area{{$area_item->area_name}}" name="area" class="custom-control-input" value="{{ $area_item->id }}" {{ isset($area) && $area == $area_item->id ? 'checked' : '' }} required>
                <label class="custom-control-label" for="area{{$area_item->area_name}}">{{ $area_item->area_name }}</label>
            </div>
            @endforeach
        </div>
        <div class="swiper-slide bg-white rounded p-5">
            <h4 class="mb-5">Client's information</h4>
            <div class="client-info-container d-grid g-4 x1 x2-sm text-left" id="add-client-menu">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-name">Client name</span>
                    </div>
                    <input type="hidden" name="old_name" value="{{ isset($old_name) ? $old_name : '' }}">
                    <input type="text" name="name" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-name" value="{{ isset($name) ? $name : '' }}" required>
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-contact-person">Contact person</span>
                    </div>
                    <input type="hidden" name="client_in_charge" value="{{ isset($old_client_in_charge) ? $old_client_in_charge : '' }}">
                    <input type="text" name="client_in_charge" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-contact-person" value="{{ isset($client_in_charge) ? $client_in_charge : '' }}" required>
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-phone">Phone</span>
                    </div>
                    <input type="hidden" name="phone_client_in_charge" value="{{ isset($old_phone_client_in_charge) ? $old_phone_client_in_charge : '' }}">
                    <input type="number" name="phone_client_in_charge" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-phone" value="{{ isset($phone_client_in_charge) ? $phone_client_in_charge : '' }}" required>
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-email">Email</span>
                    </div>
                    <input type="hidden" name="old_email_client_in_charge" value="{{ isset($old_email_client_in_charge) ? $old_email_client_in_charge : '' }}">
                    <input type="email" name="email_client_in_charge" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-email" value="{{ isset($email_client_in_charge) ? $email_client_in_charge : '' }}">
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-address">Address</span>
                    </div>
                    <input type="hidden" name="old_address_client_in_charge" value="{{ isset($old_address_client_in_charge) ? $old_address_client_in_charge : '' }}">
                    <input type="text" name="address_client_in_charge" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-address" value="{{ isset($address_client_in_charge) ? $address_client_in_charge : '' }}" required>
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-tax-code">Tax code</span>
                    </div>
                    <input type="hidden" name="tax_code" class="form-control" value="{{ isset($old_tax_code) ? $old_tax_code : '' }}">
                    <input type="text" name="tax_code" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code" value="{{ isset($tax_code) ? $tax_code : '' }}">
                </div>
                
                <div class="input-group mb-3 g-col-1 g-col-sm-2" id="client_type_id">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-client-type">Client type</span>
                    </div>
                    
                    
                    <input type="hidden" value="{{ isset($old_client_type_id) ? $old_client_type_id : '' }}" name="old_client_type_id">
                    <input type="hidden" value="{{ isset($old_setting_id) ? $old_setting_id : '' }}" name="setting_id">
                    <input type="hidden" value="{{ isset($old_company_name) ? $old_company_name : '' }}" name="company_name">
                    <input type="hidden" value="{{ isset($old_status) ? $old_status : '' }}" name="status">
                    
                    
                    <select class="selectpicker" multiple data-live-search="true" name="client_type_id[]" required>
                        @if(isset($store_type_list) > 0)
                        
                        @foreach ($store_type_list as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        
                        @endif
                    </select>
                </div>
                
                <div class="input-group mb-3" id="store_type">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-store-type">Store type</span>
                    </div>
                    
                    <select class="selectpicker" multiple data-live-search="true" name="store_type_id_0[]" id="store_type_id">
                        @if(isset($store_type_list) > 0)
                        
                        @foreach ($store_type_list as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        
                        @endif
                    </select>
                    
                    
                    {{-- get content select option to handle in client.js --}}
                    @if(isset($store_type_list) > 0)
                    
                    @foreach ($store_type_list as $key => $item)
                    <input type="hidden" id="store-type-id-{{ $item->id }}" value="{{ $item->name }}">
                    @endforeach
                    <input type="hidden" id="count-store-type" value="{{ $store_type_list->count() }}">
                    
                    @endif
                    {{-- end get content select option store type --}}
                    
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-store-name">Store name</span>
                    </div>
                    <input type="text" name="store_name[]" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code" value="{{ isset($tax_code) ? $tax_code : '' }}">
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-store-address">Store address</span>
                    </div>
                    <input type="text" name="store_addr[]" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code" value="{{ isset($tax_code) ? $tax_code : '' }}">
                </div>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="input-client-store-tax-code">Store tax code</span>
                    </div>
                    <input type="text" name="store_tax_code[]" class="form-control" aria-label="Sizing example input" aria-describedby="input-client-tax-code" value="{{ isset($store_tax_code) ? $store_tax_code : '' }}">
                </div>
            </div>
            
            <button type="button" class="btn-add-ads font-weight-bold" id="branch-new"><i class="lnr lnr-file-add mr-3"></i>Add More Store Branch</button>
        </div>
    </div>
    <div class="flex-shrink-1">
        <div class="prev-btn-client mb-3 mr-3"></div>
        <div class="next-btn-client mb-3"></div>
    </div>
</form>