<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parameter;
use Illuminate\Support\Facades\Auth;


class ParameterController extends Controller
{
    public function show_darts()
    {
        $parameter = Parameter::where("user_id", Auth::id())->latest("updated_at")->first();
        return view("trip.darts")->with(["parameter" => $parameter]);
    }

    public function post_parameter(Request $request)
    {   
        $input = $request['parameter'];
        $parameter = new Parameter();
        $parameter->fill($input)->save();
        
        return redirect('users/' . Auth::id() . '/trip/darts');
    }
}
