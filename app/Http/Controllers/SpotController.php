<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Trip;
use App\Models\SpotTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $trip->first_latitude = $request['first_latitude'];
        $trip->first_longitude = $request['first_longitude'];
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
        
        return redirect('create/users/' . Auth::id() . '/trip/list');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function show(Spot $spot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spot $spot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
