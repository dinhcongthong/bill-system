<?php 
namespace App\Http\Controllers\Traits;
use Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Service;
use App\Http\Models\Client;
use App\Http\Models\Invoice;
use App\Http\Models\Contract;
use App\Http\Models\Setting; 
use App\Http\Models\StoreBranch;
// use App\Http\Controllers\Traits\Activity;
use Spatie\Activitylog\Models\Activity;

trait WriteActivityLog {
	public function print(){
		// return "Hello";
        // return redirect('service');
	}

    // public function writeActivityLog($model_table,$last_insert_id,$row_name,$log_type, $Service_name,$Service_old_name,$Service_price,$Service_old_price, $Service_type, $service_Old_Type)  {
    public function writeActivityLog($model_table, $last_insert_id, $row_name, $log_type, $data_json)  {
        $data_array = json_decode($data_json, true);
    	/*******WRITE ACTIVITY LOG*******/
        switch ($model_table) {
            case 'services':
                $col1 =     $data_array[0]['name'];      $col2     = $data_array[0]['price'];
                $old_col1 = isset($data_array[0]['old_name']) ? $data_array[0]['old_name'] : '';  
                $old_col2 = isset($data_array[0]['old_price']) ? $data_array[0]['old_price'] : '';
                // $subject_id = $lastActivity;
                $subject_type = Config::get('table._service.log_data.subject_type');
                $model = Config::get('table._service.log_data.model'); //array

                $columnArray = Service::getTableColumns();

                // return $columnaArray;

                // activity()->withProperties(['service name' => $name, 'price' => $price, 'type' => $type]) // properties
                if($log_type == "was created" || $log_type == "was deleted" || $log_type == "was created when create contract"){
                    $properties = [
                                    'data' => [
                                        $columnArray[1] => isset($col1) ? $col1 : '', $columnArray[2] => isset($col2) ? $col2 : ''
                                    ], ];
                } else {
                    $properties = [
                                    'new_data' => [
                                        $columnArray[1] => isset($col1) ? $col1 : '', $columnArray[2] => isset($col2) ? $col2 : ''
                                    ],
                                    'old_data' => [
                                        $columnArray[1] => isset($old_col1) ? $old_col1 : '', $columnArray[2] => isset($old_col2) ? $old_col2 : ''
                                    ], ];   
                }
                
                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log($model.' "'.$row_name.'" '.$log_type); // description

                if ($log_type == "was created when create contract") {
                    return response()->json([
                        'result' => 1,
                        'id' => $last_insert_id,
                        'price' => $col2,
                        'new' => $col1
                    ]);
                }

                return redirect('service');
                break;

            case 'clients':
                $col1 =     $data_array[0]['name'];       $col2 = $data_array[0]['setting_id'];     
                $col3 = $data_array[0]['company_name'];      
                $col4 = $data_array[0]['status'];     
                $col4 =     $data_array[0]['client_in_charge'];   $col5 = $data_array[0]['phone_client_in_charge'];    
                $col6 = $data_array[0]['email_client_in_charge'];  $col7 = $data_array[0]['address_client_in_charge'];
                $col8 = $data_array[0]['tax_code'];   $col9 = $data_array[0]['client_type_id'];

                $old_col1 = isset($data_array[0]['old_name']) ? $data_array[0]['old_name'] : '';
                $old_col2 = isset($data_array[0]['old_setting_id']) ? $data_array[0]['old_setting_id'] : '';   
                $old_col3 = isset($data_array[0]['old_company_name']) ? $data_array[0]['old_company_name'] : '';         
                $old_col4 = isset($data_array[0]['old_status']) ? $data_array[0]['old_status'] : '';          
                $old_col4 = isset($data_array[0]['old_clent_in_charge']) ? $data_array[0]['old_clent_in_charge'] : '';  
                $old_col5 = isset($data_array[0]['old_phone_client_in_charge']) ? $data_array[0]['old_phone_client_in_charge'] : '';   
                $old_col6 = isset($data_array[0]['old_email_client_in_charge']) ? $data_array[0]['old_email_client_in_charge'] : '';
                $old_col7 = isset($data_array[0]['old_address_client_in_charge']) ? $data_array[0]['old_address_client_in_charge'] : '';
                $old_col8 = isset($data_array[0]['old_tax_code']) ? $data_array[0]['old_tax_code'] : '';
                $old_col9 = isset($data_array[0]['old_client_type_id']) ? $data_array[0]['old_client_type_id'] : '';


                $subject_type = Config::get('table._client.log_data.subject_type');
                $model = Config::get('table._client.log_data.model'); //array

                $columnArray = Client::getTableColumns();

                for($i = 1; $i <= 9; $i++) {
                    $data[] = [ $columnArray[$i] => isset(${"col$i"}) ? ${"col$i"} : '' ];
                    $data_old[] = [ $columnArray[$i] => isset(${"old_col$i"}) ? ${"old_col$i"} : '' ];
                }

                // activity()->withProperties(['service name' => $name, 'price' => $price, 'type' => $type]) // properties
                if($log_type == "was saved"){
                    $log_string = $model.' '.$log_type.' : '.'New data: '.json_encode($data).'. Old data: '.json_encode($data_old);
                    $properties = [
                        'new_data' => [
                            $data,
                        ],
                        'old_data' => [
                            $data_old,
                        ] ];
                } elseif ($log_type == "was created") {
                    $log_string = $model.' created : Data: '.json_encode($data);
                    $properties = [
                        'data' => [
                            $data,
                        ], ];
                }
                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log( $log_string ); // description
                        // ->log($model.' '.$log_type.' : '.'New data: '.json_encode($data) ); // description

                 return redirect()->route('home', [session('content'), session('country')]);
                break;
            case 'contracts':
                $col1 =     $data_array[0]['client_id'];     $col2 = $data_array[0]['contract_code'];   
                $col3 = $data_array[0]['company_phone'];    
                $col4 = $data_array[0]['poste_in_charge'];  $col5 = $data_array[0]['representative'];
                $col6 =     $data_array[0]['status'];     $col7 = $data_array[0]['start_date'];      
                $col8 = $data_array[0]['vat_status'];    $col9 = $data_array[0]['payment_times'];
                 $col10 = $data_array[0]['contract_note'];   $col11 = $data_array[0]['quantity_months'];
                $col12 = $data_array[0]['store_branch_id']; 
                $col13 = $data_array[0]['payment_type'];   

                $old_col1 = isset($data_array[0]['old_client_id']) ? $data_array[0]['old_client_id'] : '';
                $old_col2 = isset($data_array[0]['old_contract_code']) ? $data_array[0]['old_contract_code'] : '';               
                $old_col3 = isset($data_array[0]['old_company_phone']) ? $data_array[0]['old_company_phone'] : '';  
                $old_col4 = isset($data_array[0]['old_poste_in_charge']) ? $data_array[0]['old_poste_in_charge'] : '';
                $old_col5 = isset($data_array[0]['old_representative']) ? $data_array[0]['old_representative'] : '';
                $old_col6 = isset($data_array[0]['old_status']) ? $data_array[0]['old_status'] : '';
                $old_col7 = isset($data_array[0]['old_start_date']) ? $data_array[0]['old_start_date'] : '';                    
                $old_col8 = isset($data_array[0]['old_vat_status']) ? $data_array[0]['old_vat_status'] : '';
                $old_col9 = isset($data_array[0]['old_payment_times']) ? $data_array[0]['old_payment_times'] : '';
                $old_col10 = isset($data_array[0]['old_contract_note']) ? $data_array[0]['old_contract_note'] : ''; 
                $old_col11 = isset($data_array[0]['old_quantity_months']) ? $data_array[0]['old_quantity_months'] : ''; 
                $old_col12 = isset($data_array[0]['old_store_branch_id']) ? $data_array[0]['old_store_branch_id'] : ''; 
                $old_col13 = isset($data_array[0]['old_payment_type']) ? $data_array[0]['old_payment_type'] : ''; 

                $subject_type = Config::get('table._contract.log_data.subject_type');
                $model = Config::get('table._contract.log_data.model'); //array

                $columnArray = Contract::getTableColumns();



                for($i = 1; $i <= 13; ++$i) {
                    if( ${"col$i"} != ${"old_col$i"}) {
                        $data[] = [ $columnArray[$i] => isset(${"col$i"}) ? ${"col$i"} : '' ];
                        $data_old[] = [ $columnArray[$i] => isset(${"old_col$i"}) ? ${"old_col$i"} : '' ];
                    }
                }

                if($log_type == "was created") {
                    $properties = [
                                   'new_data' => [
                                        $data,
                                    ],
                                    'old_data' => [
                                        $data_old,
                                    ], 
                                ];
                } else {
                    $properties = [
                                    'data' => [
                                        $data,
                                    ], 
                                ];
                } 
                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log($model.' '.$log_type.' : '.'New data: '.json_encode($data).'. Old data: '.json_encode($data_old) ); // description

                return redirect()->route('home', [session('content'), session('country')]);
                break;
            // case 'contract_service':
            //     # code...
            //     break;
            case 'contract_delete':
                $col1 =     $data_array[0]['client_id'];     $col2 = $data_array[0]['contract_code'];  
                $col3 = $data_array[0]['company_phone'];   $col4 = $data_array[0]['tax_code']; 
                $col5 = $data_array[0]['poste_in_charge'];  $col6 = $data_array[0]['representative'];
                $col7 =     $data_array[0]['status'];     $col8 = $data_array[0]['start_date'];   $col9 = $data_array[0]['end_date'];   $col10 = $data_array[0]['vat'];
                $col11 = $data_array[0]['vat_status'];    $col12 = $data_array[0]['payment_times'];   $col13 = $data_array[0]['invoice'];
                 $col14 = $data_array[0]['contract_note'];   $col15 = $data_array[0]['quantity_months'];
                $col16 = $data_array[0]['store_branch_id'];     $col17 = $data_array[0]['payment_money'];    $col18 = $data_array[0]['exchange_rate'];
                $col19 = $data_array[0]['payment_type'];  

                $subject_type = Config::get('table._contract.log_data.subject_type');
                $model = Config::get('table._contract.log_data.model'); //array

                $columnArray = Contract::getTableColumns();

                for($i = 1; $i <= 19; ++$i) {
                    $data[] = [ $columnArray[$i] => isset(${"col$i"}) ? ${"col$i"} : '' ];
                }

                if ($log_type == "was deleted") {
                    $properties = [
                        'data' => [
                            $data,
                        ]
                    ];
                }
                activity()->withProperties([$properties]) // properties
                    ->tap(function (Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                        $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")) . ' - ' . Auth::user()->name . ' (' . Auth::user()->id . ') affect data';
                        $activity->subject_id = $last_insert_id;
                        $activity->subject_type = $subject_type;
                    }) //log_name
                    ->log($model.' '.$log_type.' : '.'Data: '.json_encode($data) ); // description

                return 1;
                break;

            case 'invoices_payment_status':
                $col1 = $data_array[0]['payment_status'];  $contract_code = $data_array[0]['contract_code'];

                $paid = isset( $data_array[0]['paid']) ? $data_array[0]['paid'] : '';

                $expo = isset( $data_array[0]['expo']) ? $data_array[0]['expo'] : '';

                $old_col1 = isset($data_array[0]['old_payment_status']) ? $data_array[0]['old_payment_status'] : '';

                $subject_type = Config::get('table._invoice.log_data.subject_type');
                $model = Config::get('table._invoice.log_data.model'); //array

                $data[] = [ 'payment_status' => isset($col1) ? $col1 : '' ];
                $data_old[] = [ 'payment_status' => isset($old_col1) ? $old_col1 : '' ];


                $properties = [
                    'new_data' => [
                        $data,
                    ],
                    'old_data' => [
                        $data_old,
                    ] ];

                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log($model.' of contract '.$contract_code.' '.$log_type.' : '.'New data: '.json_encode($data).'. Old data: '.json_encode($data_old) ); // description

                return response()->json([
                    'result' => 1,
                    'paid' => $paid,
                    'expo' => $expo,
                ]);

                break;

            case 'invoices_exported':
                $col1 = $data_array[0]['exported'];  $contract_code = $data_array[0]['contract_code'];

                $paid = isset( $data_array[0]['paid']) ? $data_array[0]['paid'] : '';

                $expo = isset( $data_array[0]['expo']) ? $data_array[0]['expo'] : '';

                $old_col1 = isset($data_array[0]['old_exported']) ? $data_array[0]['old_exported'] : '';
                // $subject_id = $lastActivity;
                $subject_type = Config::get('table._invoice.log_data.subject_type');
                $model = Config::get('table._invoice.log_data.model'); //array

                $data[] = [ 'exported' => isset($col1) ? $col1 : '' ];
                $data_old[] = [ 'exported' => isset($old_col1) ? $old_col1 : '' ];


                $properties = [
                    'new_data' => [
                        $data,
                    ],
                    'old_data' => [
                        $data_old,
                    ] ];

                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log($model.' of contract '.$contract_code.' '.$log_type.' : '.'New data: '.json_encode($data).'. Old data: '.json_encode($data_old) ); // description

                return response()->json([
                    'result' => 1,
                    'paid' => $paid,
                    'expo' => $expo,
                ]);

                break;

            case 'settings':
                $col1 =     $data_array[0]['name_poste'];     $col2 = $data_array[0]['address_poste'];      $col3 = $data_array[0]['area_name'];   
                $col4 = $data_array[0]['path_logo_file'];     
                $col5 =     $data_array[0]['tax_code'];   $col6 = $data_array[0]['phone_poste'];    $col7 = $data_array[0]['email_poste'];  
                $col8 = $data_array[0]['vat_rate'];     $col9 = $data_array[0]['exchange_rate'];    $col10 = $data_array[0]['bank_name']; 
                $col11 = $data_array[0]['acc_bank_name'];   $col12 = $data_array[0]['acc_bank_number'];  $col13 = $data_array[0]['parent_id']; 

                $old_col1 = isset($data_array[0]['old_name_poste']) ? $data_array[0]['old_name_poste'] : '';
                $old_col2 = isset($data_array[0]['old_address_poste']) ? $data_array[0]['old_address_poste'] : '';   
                $old_col3 = isset($data_array[0]['old_area_name']) ? $data_array[0]['old_area_name'] : '';         
                $old_col4 = isset($data_array[0]['old_path_logo_file']) ? $data_array[0]['old_path_logo_file'] : '';          
                $old_col5 = isset($data_array[0]['old_tax_code']) ? $data_array[0]['old_tax_code'] : '';     
                $old_col6 = isset($data_array[0]['old_phone_poste']) ? $data_array[0]['old_phone_poste'] : '';
                $old_col7 = isset($data_array[0]['old_email_poste']) ? $data_array[0]['old_email_poste'] : '';
                $old_col8 = isset($data_array[0]['old_vat_rate']) ? $data_array[0]['old_vat_rate'] : '';
                $old_col9 = isset($data_array[0]['old_exchange_rate']) ? $data_array[0]['old_exchange_rate'] : '';
                $old_col10 = isset($data_array[0]['old_bank_name']) ? $data_array[0]['old_bank_name'] : '';
                $old_col11 = isset($data_array[0]['old_acc_bank_name']) ? $data_array[0]['old_acc_bank_name'] : '';
                $old_col12 = isset($data_array[0]['old_acc_bank_number']) ? $data_array[0]['old_acc_bank_number'] : '';
                $old_col13 = isset($data_array[0]['old_parent_id']) ? $data_array[0]['old_parent_id'] : '';

                $subject_type = Config::get('table._setting.log_data.subject_type');
                $model = Config::get('table._setting.log_data.model'); //array

                $columnArray = Setting::getTableColumns();

                // return $columnaArray;

                for ($i = 1; $i <= 13; $i++) {
                    // $data[] = [ $columnArray[$i] => isset(${"col$i"}) ? ${"col$i"} : '' ];
                    // $data =  [ $columnArray[1] => isset($col1) ? $col1 : '', 
                    //     $columnArray[2] => isset($col2) ? $col2 : '' ];

                    // $data_old[] = [ $columnArray[$i] => isset(${"col$i"}) ? ${"old_col$i"} : '' ];

                    if( ${"old_col$i"} != ${"col$i"} ) {
                        $data[] = [ $columnArray[$i] => isset(${"col$i"}) ? ${"col$i"} : '' ];
                        $data_old[] = [ $columnArray[$i] => isset(${"old_col$i"}) ? ${"old_col$i"} : '', ];
                    }
                }

                // activity()->withProperties(['service name' => $name, 'price' => $price, 'type' => $type]) // properties
                if($log_type == "was updated" || $log_type == "was deleted" ){
                    $properties = [
                        'new_data' => [
                            $data
                        ],
                        'old_data' => [
                            $data_old
                        ] ];
                } else {
                    $properties = [
                        'data' => $data,
                    ];                 
                }

                $data_change_json = isset($data_change) ? $data_change : ['null' => 'null'];
                $data_change_json = json_encode($data_change_json);

                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        // ->log($model.' "'.$row_name.'" '.$log_type); // description
                        ->log($model.' '.$log_type.' : '.'New data: '.json_encode($data).'. Old data: '.json_encode($data_old) ); // description

                return redirect('/sidebar/info-setting');
                break;
        
            case 'store_branchs':
                $col1 =     $data_array[0]['name'];       $col2 = $data_array[0]['address'];     
                $col3 = $data_array[0]['store_type_id'];      $col4 = $data_array[0]['client_id'];  $col5 = $data_array[0]['tax_code'];

                $old_col1 = isset($data_array[0]['old_name']) ? $data_array[0]['old_name'] : '';
                $old_col2 = isset($data_array[0]['old_address']) ? $data_array[0]['old_address'] : '';
                $old_col3 = isset($data_array[0]['old_store_type_id']) ? $data_array[0]['old_store_type_id'] : '';
                $old_col4 = isset($data_array[0]['old_client_id']) ? $data_array[0]['old_client_id'] : '';
                $old_col5 = isset($data_array[0]['old_tax_code']) ? $data_array[0]['old_tax_code'] : '';

                $subject_type = Config::get('table._store_branch.log_data.subject_type');
                $model = Config::get('table._store_branch.log_data.model'); //array

                for($i = 1; $i <= 5; ++$i) {
                    $data[] = [ $columnArray[$i] => isset($col{$i}) ? $col{$i} : '' ];
                    $data_old = [ $columnArray[$i] => isset($old_col{$i}) ? $old_col{$i} : '' ];
                }

                $columnArray = StoreBranch::getTableColumns();

                if($log_type == "was updated"){
                    $properties = [
                        'new_data' => [
                            $data,
                        ],
                        'old_data' => [
                            $data_old,
                        ] ];
                } else {
                    $properties = [
                        'data' => [
                            $data,
                        ], ];
                }

                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log($model.' '.$log_type.' : '.$columnArray[1].': '.$row_name.' (old) -> '.$row_name.' (new)' ); // description

                break;

            case 'store_types':
                $col1 =     $data_array[0]['name'];

                $old_col1 = isset($data_array[0]['old_name']) ? $data_array[0]['old_name'] : '';

                $subject_type = Config::get('table._store_type.log_data.subject_type');
                $model = Config::get('table._store_type.log_data.model'); //array

                // $columnArray = StoreType::getTableColumns();

                $data = [ 'name' => isset($col1) ? $col1 : '' ];
                $data_old = [ 'name' => isset($old_col1) ? $old_col1 : '' ];
                    
                if($log_type == "was updated"){
                    $properties = [
                        'new_data' => [
                            $data,
                        ],
                        'old_data' => [
                            $data_old,
                        ] ];
                } else {
                    $properties = [
                        'data' => [
                            $data,
                        ], ];
                }

                $data_change_json = isset($data_old) ? $data_old : ['null' => 'null'];
                $data_change_json = json_encode($data_change_json);

                activity()->withProperties([$properties,]) // properties
                        ->tap(function(Activity $activity) use ($subject_type, $last_insert_id, $model, $log_type, $row_name) {
                                $activity->log_name = Carbon::createFromFormat("Y-m-d", date("Y-m-d")).' - '.Auth::user()->name.' ('.Auth::user()->id.') affect data';
                                $activity->subject_id = $last_insert_id;
                                $activity->subject_type = $subject_type;
                           }) //log_name
                        ->log($model.' '.$log_type.' : '.'New data: '.json_encode($data).'. Old data: '.$data_change_json ); // description

                return redirect('sidebar/store-type');

                break;

            case 'users':
                # code...
                break;
            
            default:
                # code...
                break;
        }

        
      
        /*******END WRITE ACTIVITY LOG*******/
    }
}