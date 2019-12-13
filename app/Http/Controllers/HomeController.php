<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Client;

use App\Http\Models\Setting;
use App\Http\Models\StoreType;

class HomeController extends Controller {
    
    protected $setting;
    protected $setting_id_arr;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware(function ($request, $next) {
            if (empty(Request()->segment(1))) {
                
                // check have created setting yet ?
                $checkSetting = Setting::all();
                if ($checkSetting->isEmpty()) {
                    return redirect()->route('get_new_info_setting_route', 0);
                }
                return redirect()->route('home', ['index', 1]);
            }
            
            if(Request()->isMethod('GET') && Request()->segment(1) != 'login' && Request()->segment(1) != 'search') {
                $setting_id = Request()->segment(2);
                $this->setting = Setting::with(['getCities:id,parent_id'])->findOrFail($setting_id);
                $this->data['area_name'] = $this->setting->area_name . '.' . $this->setting->path_logo_file;
                
                // share area
                $this->data['parent_area'] = Setting::with(['getCities'])->where('parent_id', 0)->get();
                
                $this->data['alphabet'] = [
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
                ];
                
                if ($this->setting->getCities->isEmpty()) {
                    $this->setting_id_arr = [$this->setting->id];
                } else {
                    $this->setting_id_arr = $this->setting->getCities->pluck('id')->toArray();
                    
                    $this->setting_id_arr[] = $this->setting->id;
                }
            }
            
            return $next($request);
        });
    }
    
    public function index($type = '', $country = 0) {
        // set data session for client
        session(['content' => $type]);
        session(['country' => $country]);
        $this->data['id']                       = old('id', '');
        $this->data['name']                     = old('name', '');
        $this->data['client_type_id']           = old('client_type_id', '');
        $this->data['tax_code']                 = old('tax_code', '');
        $this->data['client_in_charge']         = old('client_in_charge', '');
        $this->data['email_client_in_charge']   = old('email_client_in_charge', '');
        $this->data['phone_client_in_charge']   = old('phone_client_in_charge', '');
        $this->data['address_client_in_charge'] = old('address_client_in_charge', '');
        
        // check url have belongs to us yet
        if(empty($type)) {
            return redirect()->route('home', ['index', $country]);
        }
        
        if($country == 0) {
            return redirect()->route('home', [$type, 1]);
        }
        
        $clients = array();
        foreach ($this->data['alphabet'] as $key => $item) {
            $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')->whereIn('setting_id', $this->setting_id_arr);
        }
        
        switch($type) {
            case 'index': {
                foreach ($this->data['alphabet'] as $key => $item) {
                    $clients[$item] = $clients[$item]->where('status', 'active')->get();
                }
                break;
            }
            case 'all': {
                foreach ($this->data['alphabet'] as $key => $item) {
                    $clients[$item] = $clients[$item]->get();
                }
                break;
            }
            case 'baner': {
                foreach ($this->data['alphabet'] as $key => $item) {
                    $clients[$item] = $clients[$item]->where('status', 'banned')->get();
                }
                break;
            }
            case 'ex-client': {
                foreach ($this->data['alphabet'] as $key => $item) {
                    $clients[$item] = $clients[$item]->where('status', 'saved')->get();
                }
                break;
            }
            case 'about-to-expire': {
                $dateAfter1Week = date('Y-m-d', strtotime('+7 days'));
                
                foreach ($this->data['alphabet'] as $key => $item) {
                    $clients[$item] = $clients[$item]->whereHas('getContract', function ($contractQuery) use ($dateAfter1Week) {
                        $contractQuery->whereHas('getInvoice', function ($invoiceQuery) use ($dateAfter1Week) {
                            $invoiceQuery->whereBetween('time', [now()->format('Y-m-d'), $dateAfter1Week]);
                        });
                    })->get();
                }
                break;
            }
            default: {
                abort(404);
            }
        }
        $this->data['clients'] = $clients;
        
        return view('home')->with($this->data);
    }
    
    public function get_login () {
        if (Auth::check()) {
            return redirect()->route('home', [session('content'), session('country')]);
        }
        return view('login')->with($this->data);
    }
    
    public function login (Request $request) {
        $email = $request->email;
        $password = $request->password;
        
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect()->route('home', [session('content'), session('country')]);
        }
        $this->data['login_error'] = 'The email or password login is not correct!'; 
        return view('login')->with($this->data);
    }
    
    public function logout () {
        Auth::logout();
        return redirect()->route('login');
    }
    
    public function getArea($segment, $country = '') {
        // check country
        $country == '' ? $setting = Setting::find(1) : $setting = Setting::find($country);
        session(['country' => $country]);
        session(['content' => $segment]);
        $this->data['area'] = $setting->area_name . '.' . $setting->path_logo_file;
        
        $clients = array();
        if ($segment == 'baner') {
            foreach ($this->data['alphabet'] as $item) {
                $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')->where('status', 'banned')->where('setting_id', $country)->get();
            }
        }
        else if ($segment == 'all') {
            foreach ($this->data['alphabet'] as $item) {
                $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')->where('setting_id', $country)->get();
            }
        }
        else if ($segment == 'index') {
            foreach ($this->data['alphabet'] as $item) {
                $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')->where('status', 'active')->where('setting_id', $country)->get();
            }
        }
        else if ($segment == 'about-to-expire') {
            $dateAfter1Week = date('Y-m-d', strtotime('+7 days'));
            foreach ($this->data['alphabet'] as $key => $item) {
                $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')
                ->whereHas('getContract', function ($contractQuery) use ($dateAfter1Week) {
                    $contractQuery->whereHas('getInvoice', function ($invoiceQuery) use ($dateAfter1Week) {
                        $invoiceQuery->whereBetween('time', [now()->format('Y-m-d'), $dateAfter1Week]);
                    });
                })
                ->where('setting_id', $country)
                ->get();
            }
        }
        else if ($segment == 'ex-client') {
            foreach ($this->data['alphabet'] as $item) {
                $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')->where('status', 'saved')->where('setting_id', $country)->get();
            }
        }
        $this->data['clients'] = $clients;
        return view('home')->with($this->data);
    }
    
    public function home_search(Request $request) {
        $key_search = $request->key;
        $clients = Client::where('status', '!=', 'banned')->where('name', 'LIKE', $key_search . '%')->limit(5)->get();
        $html = '<i class="fas fa-times float-right p-2" id="close-search" style="cursor: pointer"></i>';
        foreach ($clients as $item) {
            $html .= '<div>';
            $html .= '<a class="btn" href="' . route('get_client_search_route', $item->id) . '"  data-id="' . $item->id . '">'. $item->name .'</a>';
            $html .= '</div>';
        }
        return $html;
    }
    
    public function client_search ($id) {
        $alphabet = [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        ];
        
        $this->data['alphabet'] = $alphabet;
        
        $clients = array();
        foreach ($alphabet as $key => $item) {
            $clients[$item] = Client::where('name', 'LIKE', strtoupper($item) . '%')->where('id', $id)->get();
        }
        $this->data['clients'] = $clients;
        return view('home')->with($this->data);
    }
}
