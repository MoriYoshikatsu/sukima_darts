<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\SpotCategory;
use App\Models\Spot;
use App\Models\Trip;
use App\Models\SpotTrip;
use Illuminate\Http\Request;
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
	
	public function create_spots(Request $request)
	{
		// spotをcreate
		$spots = $request->input('spot');
		$spotIds = [];      // spot_idの配列
		
		foreach ($spots as $spot) {
			if (isset($spot['selected'])) {
				$newSpot = new Spot();
				$newSpot->spot_category_id = $spot['spot_category_id'];
				$newSpot->name = $spot['name'];
				$newSpot->latitude = $spot['latitude'];
				$newSpot->longitude = $spot['longitude'];
				$newSpot->save();
				
				// 今作ったsputのidを持っておく
				array_push($spotIds, $newSpot->id);
			}
		}
		
		// Tripリストをcreate
		$trip = new Trip();
		$trip->parameter_id = $request['parameter_id'];
		$trip->title = "タイトル";
		$trip->description = "詳細";
		$trip->dart_latitude = $request['dart_latitude'];
		$trip->dart_longitude = $request['dart_longitude'];
		$trip->trip_date = now();
		$trip->status = 0;
		$trip->save();
		
		foreach($spotIds as $spotId) {
			$spotTrip = new SpotTrip();
			$spotTrip->spot_id = $spotId;
			$spotTrip->trip_id = $trip->id;
			$spotTrip->status = 0;
			$spotTrip->save();
		}
		
		return redirect('/users/' . Auth::id() . '/create/trip/' . $trip->id);
	}
}