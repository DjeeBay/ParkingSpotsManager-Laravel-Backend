<?php

namespace App\Console\Commands;

use App\Spot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SpotRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spot:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default occupier on spots';

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
        $spots = Spot::where('isoccupiedbydefault', '=', 1)
            ->whereNotNull('occupiedbydefaultby')
            ->get();
        foreach ($spots as $spot) {
            $spot->occupiedby = $spot->occupiedbydefaultby;
            $spot->occupiedat = Carbon::now();
            $spot->save();
        }
        $this->info(count($spots).' spot(s) now occupied with the default occupier !');
    }
}
