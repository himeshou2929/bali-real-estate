<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    protected $fillable = ['property_id','url','caption','sort_order','is_cover'];

    public function property() {
        return $this->belongsTo(\App\Models\Property::class);
    }
}