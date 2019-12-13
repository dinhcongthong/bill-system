<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spartie\Activitylog\Traits\LogsActivity;
use Illuminate\Auth\SessionGuard;
// use Spatie\Activitylog\Models\Activity;
use App\Http\Models\CustomActivityLog;
use App\Http\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Setting;

class ActivityController extends Controller
{
    public function index(){
		// $activities = auth()->user()->actions;
		$this->data['area_list'] = Setting::all();
    	// return $activities;
    	$logs = CustomActivityLog::all();
    	$title = "Daily activity log";
        // $jsonCauser = User::where('id', Auth::user()->id)->get('name');
        $this->data['title'] = $title;
    	$this->data['logs'] = $logs;
    	return view('log.log')->with($this->data);

        // var_dump( json_decode($logs, true) );
        $json = json_decode($logs, true);
        // return $json[4]['properties'][0];
    }

    public function loadDataTable(Request $request) {
        $columns = array(
            0 => 'id',
            1 => 'log_name',
            2 => 'description',
            3 => 'user',
        );

        $totalData = CustomActivityLog::count();

        $totalFiltered = $totalData;

        $limit = $request->length;
        $start = $request->start;

        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // $howToShowList = Config::get('ad.how_to_show');
        // $versionShowList = Config::get('ad.version_show');

        if(empty($request->input('search.value'))) {
            $positionList = CustomActivityLog::with(['getUser']);
        } else {
            $search = $request->input('search.value');

            $logList =  CustomActivityLog::with(['getUser'])
            ->where('id','LIKE',"%{$search}%")
            ->orWhere('name', 'LIKE',"%{$search}%")
            ->orWhereHas('getUser', function($query) use($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%");
            });

            $totalFiltered = AdPosition::with(['getUser'])
            ->where('id','LIKE',"%{$search}%")
            ->orWhere('name', 'LIKE',"%{$search}%")
            ->orWhereHas('getUser', function($query) use($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%");
            })->count();
        }
        $positionList = $positionList->orderBy($order, $dir)->offset($start)->limit($limit)->get();

        $data = array();


        if(!empty($positionList)) {
            foreach ($positionList as $position) {
                $nestedData['id']  = $position->id;

                $nestedData['name']  = $position->name;

                $html =         '<select class="form-control select2-no-search sl-how-to-show" data-id="'.$position->id.'">';
                foreach($howToShowList as $key => $item) {
                    $html .=    '   <option value="'. $key .'" '.($position->how_to_show == $key ? "selected" : "").'>'.$item.'</option>';
                }
                $html .=        '</select>';
                $nestedData['arrangement']  = $html;

                $html =     '<select class="form-control select2-no-search sl-version-show" data-id="'.$position->id.'">';
                foreach($versionShowList as $key => $item) {
                    $html .='   <option value="'.$key.'" '.($position->version_show == $key ? 'selected' : '').'>'.$item.'</option>';
                }
                $html .=    '</select>';
                $nestedData['version_show']  = $html;

                $nestedData['user']  = !is_null($position->getUser) ? getUserName($position->getUser) : '<span class="text-center">Pended User</span>';

                $nestedData['updated_at']  = date_format($position->updated_at, 'Y-m-d H:i:s');

                $html =     '<a class="mx-1" href="'.route('get_ads_position_edit_ad_route', ['id' => $position->id] ).'">';
                $html .=    '   <i class="fas fa-edit text-primary"></i>';
                $html .=    '</a>';
                $nestedData['action']  = $html;

                $data[] = $nestedData;
            }
        } //
        $json_data = array(
            'draw'              => intval($request->draw),
            'recordsTotal'      => intval($totalData),
            'recordsFiltered'   => intval($totalFiltered),
            'data'              => $data
        );

        return response()->json($json_data);
    }
}
