<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Service;
use App\Http\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Config;
use App\Http\Controllers\Traits\WriteActivityLog;
use App\Http\Requests\ServiceRequest;
use App\Http\Models\Setting;

class ServiceController extends Controller
{
    use WriteActivityLog;

    public function index ()
    {
        $this->data['service'] = Service::all();
        return view('service.index')->with($this->data);
    }

    public function getNew ($service_id) {
        if( $service_id == 0 ) {
            // $this->data['service_id'] = '';
            return view('service.add-new')->with($this->data);
        }
        else{
            $service = Service::find($service_id);
            $this->data['id'] = $service->id;
            $this->data['name'] = $service->name;
            $this->data['price'] = $service->price;
            $this->data['old_name'] = $service->name;
            $this->data['old_price'] = $service->price;
            return view('service.add-new')->with($this->data);
        }
        
        return view('service.add-new')->with($this->data);
    }

    public function postNew (ServiceRequest $request, $id) {
        $name = $request->input('name');
        $price = $request->input('price');
        $old_name = $request->input('old_name');
        $old_price = $request->input('old_price');

        $data = [
            'name' => $name,
            'price' => $price
        ];

        if ($id != 0) {
            $service = Service::where('id', $id)->update($data);
            $last_insert_id = $id;
            $jsonRowName = Service::where('id', '=', $last_insert_id)->get('name');
            $jsonRowName = json_decode($jsonRowName, true);
            $row_name = $jsonRowName[0]['name'];
            $log_type = "was updated";
        }
        else {
            // create new data
            $service = Service::create($data);   
            $last_insert_id = $service->id;
            $jsonRowName = Service::where('id', '=', $last_insert_id)->get('name');
            $jsonRowName = json_decode($jsonRowName, true);
            $row_name = $jsonRowName[0]['name'];
            $log_type = "was created";
        }

        if ($service) {
            $json_array = array(array(
                "name" => $name,
                "price" => $price,
                "old_name" => $old_name,
                "old_price" => $old_price
            ));
            $service_json = json_encode($json_array);

            if ($request->has('ajaxInsert') == 1) {
                $log_type = "was created when create contract";
                return $this->writeActivityLog('services', $last_insert_id, isset($row_name) ? $row_name : '', $log_type, $service_json);
            }
            // call function from Trait
            return $this->writeActivityLog('services',$last_insert_id,isset($row_name) ? $row_name : '',$log_type, $service_json);
        }

        return 0;
    }

    public function delete ($id) {
        $service = Service::find($id);
        
        $last_insert_id = $id;
        $jsonRowName = Service::where('id', '=', $last_insert_id)->get();
        $jsonRowName = json_decode($jsonRowName, true);
        // return $jsonRowName;
        $row_name = $jsonRowName[0]['name'];
        // return $row_name;
        $log_type = "was deleted";
        if ($service) {

            $service->delete();
            $json_array = array(array(
                "name" => $row_name,
                "price" => $jsonRowName[0]['price']
            ));
            $service_json = json_encode($json_array);
            return $this->writeActivityLog('services',$last_insert_id,isset($row_name) ? $row_name : '',$log_type, $service_json);
        }

        return 0;
    }

    //     $lastActivity = Activity::all()->last(); //trả về hoạt động cuối cùng
   
    //     // $lastActivity->getExtraProperty('subject_id'); //trả về 'value' 
}

