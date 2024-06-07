<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parameter;
use App\Models\SpotCategory;
use Illuminate\Support\Facades\Auth;


class ParameterController extends Controller
{
    public function index()
	{
		$spot_categories = SpotCategory::all();
		return view("dart.index")->with(["spot_categories" => $spot_categories]);
	}
	
    public function post_parameter(Request $request)
    {   
        $input = $request['parameter'];
        $parameter = new Parameter();
        $parameter->fill($input)->save();
        
        return redirect('users/' . Auth::id() . '/trip/darts');
    }
    
    public function show_darts()
    {
        $parameter = Parameter::where("user_id", Auth::id())->latest("updated_at")->first();
        return view("dart.darts")->with(["parameter" => $parameter]);
    }
}
