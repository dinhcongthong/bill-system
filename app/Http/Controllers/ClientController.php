<?php

namespace App\Http\Controllers;

use App\Http\Models\Client;
// use Storage;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\ClientRequest;
use App\Http\Controllers\Traits\WriteActivityLog;
use App\Http\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Models\ClientType;
use App\Http\Models\StoreBranch;
use App\Http\Models\Contract;
use App\Http\Models\StoreType;

class ClientController extends Controller
{
    use WriteActivityLog;
    
    public function postNew(ClientRequest $request) {
        // check validation store branch
        foreach ($request->store_name as $key => $item) {
            if(empty($item)) {
                if(!empty($request->get( 'store_type_id_'.$key))) {
                    return back()->withInput()->withErrors(['errors' => 'Store name must be required your branch!']);
                }
            }
            else {
                if (empty($request->get('store_type_id_' . $key))) {
                    return back()->withInput()->withErrors(['errors' => 'Store type and store name must be required your branch!']);
                }
            }
        }
        
        // return $request->all();
        $client_id                 = $request->client_id;
        $name                      = $request->name;
        $tax_code                  = $request->tax_code;
        $area                      = $request->area;
        $client_in_charge          = $request->client_in_charge;
        $email_client_in_charge    = $request->email_client_in_charge;
        $phone_client_in_charge    = $request->phone_client_in_charge;
        $address_client_in_charge  = $request->address_client_in_charge;
        $client_type_id            = implode(',', $request->client_type_id);
        // $old_client_type_id        = implode(',', );
        
        $data = [
            'name' => $name,
            'tax_code' => $tax_code,
            'setting_id' => $area,
            'client_in_charge' => $client_in_charge,
            'email_client_in_charge' => $email_client_in_charge,
            'phone_client_in_charge' => $phone_client_in_charge,
            'address_client_in_charge' => $address_client_in_charge,
            'client_type_id' => $client_type_id
        ];
        
        $client = Client::find($client_id);
        if (!empty($client)) {
            // $default_name = $client->name;
            $old_client_id = $client_id;
            $store_branch = StoreBranch::where('client_id', $client_id)->get();
            if ($store_branch->count()) {
                $store_branch = StoreBranch::where('client_id', $client_id)->delete();
            }
            $client = $client->forceDelete();
            if (!$client || !$store_branch) {
                return back()->withInput()->withErrors(['errors' => 'Have error update client!']);
            }
        }
        
        
        
        // create new data
        $client = Client::create($data);
        $last_insert_id = $client->id;
        $row_name = $client->name;
        
        $log_type = "was saved";
        
        if (!is_null($request->store_name[0])) {
            foreach ($request->store_name as $key => $item) {
                $store_type_id = 'store_type_id_'.$key;
                $dataStoreBranch = [
                    'name' => $item,
                    'address' => $request->store_addr[$key],
                    'client_id' => $last_insert_id,
                    'tax_code' => !is_null($request->store_tax_code[$key]) ? $request->store_tax_code[$key] : '',
                    'store_type_id' => implode(',', $request->get($store_type_id))
                ];
                $store_branch = StoreBranch::create($dataStoreBranch);
            }
        }
        // update client id for contract
        if (isset($old_client_id)) {
            $contract = Contract::where('client_id', $old_client_id)->get();
            if ($contract->count()) {
                $contract = Contract::where('client_id', $old_client_id)->update(['client_id' => $last_insert_id]);
            }
            if (!$contract) {
                return back()->withInput()->withErrors(['errors' => 'Have error update contract!']);
            }
        }
        
        if ($client) {
            $json_array = array(array(
                'name' => $request->name,
                'setting_id' => $request->area,
                'company_name' => $request->company_name,
                'status' => '',
                'client_in_charge' => $request->client_in_charge,
                'email_client_in_charge' => $request->email_client_in_charge,
                'phone_client_in_charge' => $request->phone_client_in_charge,
                'address_client_in_charge' => $request->address_client_in_charge,
                'tax_code' => $request->tax_code,
                'client_type_id' => $request->client_type_id,
                
                'old_name' => $request->old_name,
                'old_setting_id' => $request->old_area,
                'old_company_name' => $request->old_company_name,
                'old_status' => '',
                'old_client_in_charge' => $request->old_client_in_charge,
                'old_email_client_in_charge' => $request->old_email_client_in_charge,
                'old_phone_client_in_charge' => $request->old_phone_client_in_charge,
                'old_address_client_in_charge' => $request->old_address_client_in_charge,
                'old_tax_code' => $request->old_tax_code,
                'old_client_type_id' => $request->old_client_type_id,
            ));
            $client_json = json_encode($json_array);
            
            // // call function from Trait
            
            return $this->writeActivityLog('clients', $last_insert_id, $row_name, $log_type, $client_json);
            // return redirect()->route('home', [session('content'), session('country')]);
        }
        
        return 0;
    }
    
    public function getNew (Request $request, $id) {
        $client_id = $request->id;
        $this->data['client_id'] = $client_id;
        if ($id == 0) {
            return view('client-modal')->with($this->data);
        }
        else {
            $client = Client::find($client_id);
            $this->data['id'] = $client->id;
            $this->data['name'] = $client->name;
            $this->data['setting_id'] = $client->setting_id;
            $this->data['company_name'] = $client->company_name;
            $this->data['status'] = $client->status;
            $this->data['client_in_charge'] = $client->client_in_charge;
            $this->data['email_client_in_charge'] = $client->email_client_in_charge;
            $this->data['phone_client_in_charge'] = $client->phone_client_in_charge;
            $this->data['address_client_in_charge'] = $client->address_client_in_charge;
            $this->data['tax_code'] = $client->tax_code;
            $this->data['default_client_type_id'] = $client->client_type_id;
            $this->data['client_type_id'] = explode(',', $client->client_type_id);
            
            $this->data['old_name'] = $client->name;
            $this->data['old_setting_id'] = $client->setting_id;
            $this->data['old_company_name'] = $client->company_name;
            $this->data['old_status'] = $client->status;
            $this->data['old_client_in_charge'] = $client->client_in_charge;
            $this->data['old_email_client_in_charge'] = $client->email_client_in_charge;
            $this->data['old_phone_client_in_charge'] = $client->phone_client_in_charge;
            $this->data['old_address_client_in_charge'] = $client->address_client_in_charge;
            $this->data['old_tax_code'] = $client->tax_code;
            $this->data['old_client_type_id'] = $client->client_type_id;
            
            // data store branch
            $store_branch = StoreBranch::where('client_id', $client->id)->get();
            if ($store_branch->count()) {
                $this->data['store_branch'] = $store_branch;
            }
            return view('home-edit-client')->with($this->data)->render();
        }
        
        return 0;
    }
    
    public function postBaner ($id) {
        $client = Client::find($id);
        $client->update(['status' => 'banned']);
        if ($client) {
            return redirect()->route('home', [session('content'), session('country')]);
        }
        return 0;
    }
    
    public function postRemoveTheBan ($id) {
        $client = Client::find($id);
        $client->update(['status' => 'active']);
        if ($client) {
            return redirect()->route('home', [session('content'), session('country')]);
        }
        return 0;
    }
    
    public function postSaveClient (Request $request) {
        $id = $request->id;
        $client = Client::find($id);
        
        if (is_null($client)) {
            return response()->json(['errors' => 'Can not find this client!', 'result' => 0 ]);
        }
        
        // check this client has payment yet ?
        foreach ($client->getContract as $item) {
            if ($item->invoice == 0) {
                return response()->json(['errors' => 'This client has not payment yet !', 'result' => 0]);
            }
        }
        
        $client = $client->update(['status' => 'saved']);
        if ($client) {
            return response()->json(['result' => 1]);
        }
        
        return response()->json(['errors' => 'Save client was failed!', 'result' => 0]);
    }
}
