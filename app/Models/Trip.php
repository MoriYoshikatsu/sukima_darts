<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
	use HasFactory;
	
	public function parameter() {
		return $this->belongsTo(Parameter::class);
	}

	public function spot_trips() {
		return $this->hasmany(SpotTrip::class);
	}
	
	public function images() {
		return $this->hasmany(Image::class);
	}

	public function likes() {
		return $this->hasmany(Like::class);
	}
	
	protected $fillable = [
		'title',
		'description',
		'dart_latitude',
		'dart_longitude',
		'trip_date',
		'status',
		'created_at',
		'updated_at',
	];
	
	public function getByLimit(int $limit_count = 10)
	{
		// updated_atで降順に並べたあと、limitで件数制限をかける
		return $this->orderBy('updated_at', 'DESC')->limit($limit_count)->get();
	}

}
