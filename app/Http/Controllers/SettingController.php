<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Setting;

use File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\InfoSettingRequest;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Traits\WriteActivityLog;
use App\Http\Models\StoreType;

class SettingController extends Controller
{
    use writeActivityLog;
    
    public function index () {
        return view('sidebar.setting-menu')->with($this->data);
    }
    
    public function info_setting () {
        $setting = Setting::all();;
        $this->data['settings'] = $setting;
        
        return view('sidebar.info-setting')->with($this->data);
    }
    
    public function getNew ($id) {
        $parent = Setting::where('parent_id', 0)->get();
        if ($parent->count()) {
            $this->data['parent'] = $parent;
        }
        
        if ($id == 0) {
            $this->data['name_poste']       = old('name_poste', '');
            $this->data['address_poste']    = old('address_poste', '');
            $this->data['area_name']        = old('area_name', '');
            $this->data['vat_rate']         = old('vat_rate', '');
            $this->data['tax_code']         = old('tax_code', '');
            $this->data['phone_poste']      = old('phone_poste', '');
            $this->data['email_poste']      = old('email_poste', '');
            $this->data['exchange_rate']    = old('exchange_rate', '');
            $this->data['bank_name']        = old('bank_name', '');
            $this->data['acc_bank_name']    = old('acc_bank_name', '');
            $this->data['acc_bank_number']  = old('acc_bank_number', '');
            $this->data['parent_id']        = old('parent_id', '');
            return view('sidebar.add-new-setting')->with($this->data);
        }
        $settings = Setting::find($id);
        $this->data['id'] = $id;
        $this->data['name_poste']       = old('name_poste', $settings->name_poste);
        $this->data['address_poste']    = old('address_poste', $settings->address_poste);
        $this->data['path_logo_file']   = old('path_logo_file', $settings->path_logo_file);
        $this->data['area_name']        = old('area_name', $settings->area_name);
        $this->data['vat_rate']         = old('vat_rate', $settings->vat_rate);
        $this->data['tax_code']         = old('tax_code', $settings->tax_code);
        $this->data['phone_poste']      = old('phone_poste', $settings->phone_poste);
        $this->data['email_poste']      = old('email_poste', $settings->email_poste);
        $this->data['exchange_rate']    = old('exchange_rate', $settings->exchange_rate);
        $this->data['bank_name']        = old('bank_name', $settings->bank_name);
        $this->data['acc_bank_name']    = old('acc_bank_name', $settings->acc_bank_name);
        $this->data['acc_bank_number']  = old('acc_bank_number', $settings->acc_bank_number);
        $this->data['parent_id']        = old('parent_id', $settings->parent_id);
        
        return view('sidebar.add-new-setting')->with($this->data);
    }
    
    public function postCloneNew ($id) {
        $setting = Setting::find($id);

        $clone = $setting->replicate();
        $clone = $clone->save();
        if (!$clone) {
            return 'Have error duplicate data!';
        }

        $last_insert_id = Setting::max('id');
        // duplicate file logo
        copy('area/' . $setting->id . '.' . $setting->path_logo_file, 'area/' . $last_insert_id . '.' . $setting->path_logo_file);
        // update name
        $updateName = Setting::where('id', $last_insert_id)->update(['name_poste' => $setting->name_poste . '(copy)']);
        if (!$updateName) {
            return 'Have error duplicate data!';
        }

        return redirect('sidebar/info-setting')->with($this->data);
    }
    
    public function postNew (InfoSettingRequest $request) {
        $id = $request->setting_id;
        
        // format area name with no space
        $area_name = $request->area_name;
        $area_name = preg_replace("/\s+/", "", $area_name);
        $old_area_name = $request->old_area_name;
        $old_area_name = preg_replace("/\s+/", "", $old_area_name);
        
        // save file area_name with id setting
        if ($request->hasFile('path_logo_file')) {
            $file = $request->file('path_logo_file');
            $path_file = $file->getClientOriginalExtension($file);
            $file->move('area', $id . '.' . $path_file);
        }
        else {
            $setting_file = Setting::find($id);
            $path_file = $setting_file->path_logo_file;
            // rename('area/' . $setting_file->id . '.' . $setting_file->path_logo_file, 'area/' . $id . '.' . $path_file);
        }
        
        $data = [
            'name_poste' => $request->name_poste,
            'area_name' => $area_name,
            'address_poste' => $request->address_poste,
            'vat_rate' => $request->vat_rate,
            'tax_code' => $request->tax_code,
            'phone_poste' => $request->phone_poste,
            'email_poste' => $request->email_poste,
            'exchange_rate' => $request->exchange_rate,
            'bank_name' => $request->bank_name,
            'acc_bank_name' => $request->acc_bank_name,
            'acc_bank_number' => $request->acc_bank_number,
            'parent_id' => $request->parent_id,
            'path_logo_file' => $path_file
        ];
        
        $setting = Setting::find($id);
        if (!is_null($setting)) {
            // update data
            $setting = $setting->update($data);
            $last_insert_id = $id;
            $row = $setting;
            $row_name = $request->name_poste;
            $log_type = "was updated";
        }
        else {
            // create new data
            $setting = Setting::create($data);
            $last_insert_id = $setting->id;
            $row = $setting;
            $row_name = $row->name_poste;
            $log_type = "was created";
        }
        
        if ($setting) {
            $json_array = array(array(
                'name_poste' => $request->name_poste,           'old_name_poste' => $request->old_name_poste,
                'area_name' => $area_name,                      'old_area_name' => $old_area_name,
                'address_poste' => $request->address_poste,     'old_address_poste' => $request->old_address_poste,
                'vat_rate' => $request->vat_rate,               'old_vat_rate' => $request->old_vat_rate,
                'tax_code' => $request->tax_code,               'old_tax_code' => $request->old_tax_code,
                'phone_poste' => $request->phone_poste,         'old_phone_poste' => $request->old_phone_poste,
                'email_poste' => $request->email_poste,         'old_email_poste' => $request->old_email_poste,
                'exchange_rate' => $request->exchange_rate,     'old_exchange_rate' => $request->old_exchange_rate,
                'bank_name' => $request->bank_name,             'old_bank_name' => $request->old_bank_name,
                'acc_bank_name' => $request->acc_bank_name,     'old_acc_bank_name' => $request->old_acc_bank_name,
                'acc_bank_number' => $request->acc_bank_number, 'old_acc_bank_number' => $request->old_acc_bank_number,
                'path_logo_file' => $request->path_logo_file,    'old_path_logo_file' => $request->old_path_logo_file,
                'parent_id' => $request->parent_id,             'old_parent_id' => $request->old_parent_id
            ));
            $setting_json = json_encode($json_array);
            
            // call function from Trait
            return $this->writeActivityLog('settings',$last_insert_id,isset($row_name) ? $row_name : '',$log_type, $setting_json);
        }
        
        return 'error!';
    }
    
    public function deleteInfo ($id) {
        $setting = Setting::find($id);
        if (is_null($setting)) {
            return 'can not find any setting id!';
        }
        
        // find delete file
        $destinationPath = 'area' . '/' . $setting->area_name . '.' . $setting->path_logo_file;
        $file = File::delete($destinationPath);
        if (!$file) {
            return 'Error! <br> Can not delete file!';
        }
        
        $setting = $setting->delete();
        
        if ($setting) {
            return redirect('sidebar/info-setting');
        }
        return 'error!';
    }
    
    public function storeTypeIndex () {
        $this->data['store_type'] = StoreType::all();
        return view('sidebar.store-type-index')->with($this->data);
    }
    
    public function storeTypeGetSave ($id) {
        if ($id == 0) {
            return view('sidebar.store-type-save')->with($this->data);
        } else {
            $store_type = StoreType::find($id);
            $this->data['id'] = $store_type->id;
            $this->data['name'] = $store_type->name;
            $this->data['old_name'] = $store_type->name;
            return view('sidebar.store-type-save')->with($this->data);
        }
        
        return view('sidebar.store-type-save')->with($this->data);
    }
    
    public function storeTypePostSave (Request $request) {
        $id = $request->id;
        if ($id == 0) {
            $store_type = StoreType::create(['name' => $request->name]);
            $last_insert_id = $store_type->id;
            $row_name = $store_type->name;
            $log_type = 'was created';
        }
        else {
            $store_type = StoreType::where('id', $id)->update(['name' => $request->name]);
            $last_insert_id = $id;
            $row_name = $request->name;
            $log_type = 'was updated';
        }
        // if ($store_type) {
        //     return redirect('sidebar/store-type');
        // }

        if ($store_type) {
            $json_array = array(array(
                "name" => $request->name,
                "old_name" => $request->old_name,
            ));
            $store_type_json = json_encode($json_array);

            // call function from Trait
            return $this->writeActivityLog('store_types',$last_insert_id,isset($row_name) ? $row_name : '',$log_type, $store_type_json);
        }

        return 0;

        return back()->withInput()->withErrors(['errors' => 'Error save store type']);
    }
    
    public function storeTypeDelete ($id) {
        $store_type = StoreType::find($id);
        if (!is_null($store_type)) {
            $store_type = $store_type->update(['name' => 'NULL']);
            if ($store_type) {
                return redirect('sidebar/store-type');
            }
            return back()->withInput()->withErrors(['errors' => 'Error delete store type!']);
        }
        return back()->withInput()->withErrors(['errors' => 'Can not find any id to delete!']);
    }

}
