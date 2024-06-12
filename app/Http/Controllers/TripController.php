<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\SpotTrip;
use App\Models\Parameter;
use App\Models\SpotCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TripController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$trips = Trip::all();
		$userParameters = Parameter::where('user_id', Auth::id())->get();
		$filteredTrips = collect();
		$tripSpotTrips = [];
		$tripWentSpotTrips = [];
		
		foreach ($trips as $trip) {
			foreach ($userParameters as $parameter) {
				if ($trip->parameter_id === $parameter->id) {
					$filteredTrips->push($trip);
					
					$tripSpotTrips[$trip->id] = SpotTrip::where('trip_id', $trip->id)->where('status', 0)->get();
					$tripWentSpotTrips[$trip->id] = SpotTrip::where('trip_id', $trip->id)->where('status', 1)->get();
				}
			}
		}
		return view('trip.index')->with(['trips' => $filteredTrips, "tripWentSpotTrips" => $tripWentSpotTrips, "tripSpotTrips" => $tripSpotTrips]);
	}
	
	public function others_index(Request $request)
	{
		$trips = Trip::all();
		$user_id = $request->route('user');
		$user = User::where('id', $user_id)->first();
		$userParameters = Parameter::where('user_id', $user_id)->get();
		$filteredTrips = collect();
		$tripSpotTrips = [];
		$tripWentSpotTrips = [];
		
		foreach ($trips as $trip) {
			foreach ($userParameters as $parameter) {
				if ($trip->parameter_id === $parameter->id) {
					$filteredTrips->push($trip);
					
					$tripSpotTrips[$trip->id] = SpotTrip::where('trip_id', $trip->id)->where('status', 0)->get();
					$tripWentSpotTrips[$trip->id] = SpotTrip::where('trip_id', $trip->id)->where('status', 1)->get();
				}
			}
		}
		return view('trip.othersindex')->with(['trips' => $filteredTrips, "tripWentSpotTrips" => $tripWentSpotTrips, "tripSpotTrips" => $tripSpotTrips, "user" => $user]);
	}
	
	public function create(Request $request)
	{
		$trips = Trip::all();
		$userParameters = Parameter::where('user_id', Auth::id())->get();
		$filteredTrips = collect();
		foreach ($trips as $trip) {
			foreach ($userParameters as $parameter) {
				if ($trip->parameter_id === $parameter->id) {
					$filteredTrips->push($trip);
				}
			}
		}

		$tripId = $request->route('trip');
		$trip = $filteredTrips->where('id', $tripId)->sortByDesc("updated_at")->first();
		$SpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 0)->get();
		$wentSpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 1)->get();
	
		return view('trip.create')->with(['trips' => $filteredTrips, "trip" => $trip, "wentSpotTrips" => $wentSpotTrips, "SpotTrips" => $SpotTrips]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$parameter = Parameter::where('user_id', Auth::id())->latest("updated_at")->first();
		$trip = Trip::where('parameter_id', $parameter->id)->latest("updated_at")->first();
		$trip->parameter_id = $parameter->id;
		$trip->title = $request->title;
		$trip->description = $request->description;
		$trip->trip_date = $request->trip_date;
		$trip->status = $request->status;
		$trip->save();
		
		$spotIds = $request['spots'];
		if ($spotIds !== null) {
			foreach($spotIds as $spotId) {
				$spotTrip = SpotTrip::where('spot_id', $spotId)->first();
				if ($spotTrip->status === 0) {
					$spotTrip->status = 1;
				} else {
					$spotTrip->status = 0;
				}
				$spotTrip->save();
				
				$tripId = $spotTrip->trip_id;
			}
		}
		
		return redirect()->route('index_trip', ['user' => Auth::id()]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function show(Trip $trips)
	{
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request)
	{
		$trips = Trip::all();
		$userParameters = Parameter::where('user_id', Auth::id())->get();
		$filteredTrips = collect();
		foreach ($trips as $trip) {
			foreach ($userParameters as $parameter) {
				if ($trip->parameter_id === $parameter->id) {
					$filteredTrips->push($trip);
				}
			}
		}

		$tripId = $request->route('trip');
		// $trip = Trip::where('parameter_id', $userParameters->id)->where('id', $tripId)->first();
		$trip = $filteredTrips->where('id', $tripId)->sortByDesc("updated_at")->first();
		// dd($trip);
		$SpotTrips = SpotTrip::where('trip_id', $trip->id)->get();
		// $wentSpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 1)->get();
		
		return view('trip.edit')->with(['trips' => $filteredTrips, "trip" => $trip, "SpotTrips" => $SpotTrips]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$tripId = $request->route('trip');
		
		$trip = Trip::where('id', $tripId)->latest("updated_at")->first();
		$trip->title = $request->title;
		$trip->description = $request->description;
		$trip->trip_date = $request->trip_date;
		$trip->status = $request->status;
		$trip->save();
		
		
		$spotIds = $request['spots'];
		
		if ($spotIds !== null) {
			foreach($spotIds as $spotId) {
				$spotTrip = SpotTrip::where('spot_id', $spotId)->first();
				if ($spotTrip->status === 0) {
					$spotTrip->status = 1;
				} else {
					$spotTrip->status = 0;
				}
				$spotTrip->save();
				
				$tripId = $spotTrip->trip_id;
			}
		}
		
		return redirect('/users/' . Auth::id());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Trip $trip)
	{
		//
	}
}
