<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ParseLostfilm;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ParseLostfilmTask extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-lostfilm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses lostfilm new page and saves data to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dispatch(new ParseLostfilm());
    }
}
