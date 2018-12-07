<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['name_ru', 'name_en', 'release_date_ru', 'release_date_en', 'series_id', 'details_link'];
    
    public function series()
    {
        return $this->belongsTo('App\Models\Series');
    }
}
