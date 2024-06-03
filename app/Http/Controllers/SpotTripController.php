<?php

namespace App\Http\Controllers;

use App\Models\SpotTrip;
use App\Models\Trip;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotTripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    // 行く予定だったspotを行ったかどうかを更新するview
    public function create()
    {
        $parameter = Parameter::where('user_id', Auth::id())->latest("updated_at")->first();
        $trip = Trip::where('parameter_id', $parameter->id)->latest("updated_at")->first();
        $spotTrips = SpotTrip::where('trip_id', $trip->id)->get();
        
        return view("trip.list")->with(["spotTrips" => $spotTrips, "trip" => $trip]);
    }

    // spot_tripで行ったかどうかを更新
    public function store_status(Request $request)
    {
        $spotIds = $request['spots'];
        foreach($spotIds as $spotId) {
            $spotTrip = SpotTrip::where('spot_id', $spotId)->first();
            $spotTrip->status = 1;
            $spotTrip->save();
            
            $tripId = $spotTrip->trip_id;
        }

        return redirect('/users/' . Auth::id() . '/create/trip/' . $tripId);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spot_trip  $spot_trip
     * @return \Illuminate\Http\Response
     */
    public function show(Spot_trip $spot_trip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spot_trip  $spot_trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Spot_trip $spot_trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spot_trip  $spot_trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spot_trip $spot_trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spot_trip  $spot_trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spot_trip $spot_trip)
    {
        //
    }
}
