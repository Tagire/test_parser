<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Episode;
use App\Models\Series;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
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
            $episodes = Episode::whereIn('id', $episodesIds)
                ->with('series')
                ->paginate($perPage);
        } else {
            $episodes = Episode::orderBy('release_date_ru', 'DESC')->paginate($perPage);
        }

        return view('series.index', compact('episodes', 'keyword'));
    }

}
