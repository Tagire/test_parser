<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $search = $request->get('search');
        $perPage = 10;

        if (!empty($search)) {
            $episodes = Episode::searchByEpisodeOrSeriesName($search)
                ->with('series')
                ->paginate($perPage);
        } else {
            $episodes = Episode::orderBy('release_date_ru', 'DESC')->paginate($perPage);
        }

        return view('series.index', compact('episodes', 'search'));
    }

}
