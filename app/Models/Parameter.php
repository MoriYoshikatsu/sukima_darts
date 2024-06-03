<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;
    
    public function trip() {
        return $this->belongsTo(Trip::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function spot_category() {
        return $this->belongsTo(SpotCategory::class);
    }
    
    protected $fillable = [
        'user_id',
        'spot_category_id',
        'departure_latitude',
        'departure_longitude',
        'trip_time',
        'transportation',
        'created_at',
        'updated_at',
    ];

}
