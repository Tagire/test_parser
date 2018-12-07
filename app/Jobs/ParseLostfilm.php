<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Library\LostfilmParser;
use App\Models\Episode;
use App\Models\Series;

class ParseLostfilm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $page;

    public function __construct($page = 1)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parser = new LostfilmParser();
        try {
            $episodesData = $parser->parse($this->page);
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            throw $e;
        }

        foreach ($episodesData as $episodeData) {
            $series = Series::firstOrCreate([
                'name' => $episodeData['series_name'],
            ]);
            $episode = Episode::firstOrNew([
                'name_ru' => $episodeData['episode_name_ru'],
                'name_en' => $episodeData['episode_name_en'],
                'release_date_ru' => date('Y-m-d', strtotime($episodeData['release_date_ru'])),
                'release_date_en' => date('Y-m-d', strtotime($episodeData['release_date_en'])),
                'details_link' => $episodeData['details_link'],
                'series_id' => $series->id,
            ]);
            if (!empty($episode->id)) {
                return; //Already parsed page
            }
            $episode->save();
        }
    }
}
