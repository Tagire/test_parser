<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Episode extends Model
{
    protected $fillable = ['name_ru', 'name_en', 'release_date_ru', 'release_date_en', 'series_id', 'details_link'];
    
    public function series()
    {
        return $this->belongsTo('App\Models\Series');
    }

    /**
     * Scope a query that matches a full text search for episode name or series name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByEpisodeOrSeriesName(\Illuminate\Database\Eloquent\Builder $query, string $keyword)
    {
        $episodesIds = DB::select("
            SELECT episodes.id from episodes 
            INNER JOIN series ON episodes.series_id = series.id 
            WHERE 
                MATCH(episodes.name_ru, episodes.name_en) AGAINST (? IN BOOLEAN MODE) 
                OR MATCH(series.name) AGAINST (? IN BOOLEAN MODE)
            ORDER BY release_date_ru DESC",
            [$keyword, $keyword]);
        $episodesIds = array_map(function($episodesIdObject) {
            return $episodesIdObject->id;
        }, $episodesIds);

        $query->whereIn('id', $episodesIds);

        return $query;
    }
}
