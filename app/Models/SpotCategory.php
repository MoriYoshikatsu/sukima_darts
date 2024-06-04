<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotCategory extends Model
{
    use HasFactory;

    public function spots() {
        return $this->hasmany(Spot::class);
    }
    
    public function parameter() {
        return $this->belongsTo(Parameter::class);
    }
}
