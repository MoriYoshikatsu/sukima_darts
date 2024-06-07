<?php

namespace App\Http\Controllers;

use App\Models\Trip;
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
		$SpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 0)->get();
		$wentSpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 1)->get();
	
		return view('trip.user')->with(['trips' => $filteredTrips, "trip" => $trip, "wentSpotTrips" => $wentSpotTrips, "SpotTrips" => $SpotTrips]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$parameter = Parameter::where('user_id', Auth::id())->latest("updated_at")->first();
		$trip = Trip::where('parameter_id', $parameter->id)->latest("updated_at")->first();
		$SpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 0)->get();
		$wentSpotTrips = SpotTrip::where('trip_id', $trip->id)->where('status', 1)->get();
		
		return view("trip.create")->with(["trip" => $trip, "wentSpotTrips" => $wentSpotTrips, "SpotTrips" => $SpotTrips]);
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
		$trip->dart_latitude = $request->dart_latitude;
		$trip->dart_longitude = $request->dart_longitude;
		$trip->trip_date = $request->trip_date;
		$trip->status = $request->status;
	
		$trip->save();
	
		return redirect()->route('show_trip', ['user' => Auth::id(), 'trip' => $trip]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function show(Trip $trips)
	{
		$trips = Trip::all();
		return view("trip.user")->with(["trips" => $trips]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Trip $trip)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Trip  $trip
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Trip $trip)
	{
		//
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
