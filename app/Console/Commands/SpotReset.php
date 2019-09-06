<?php

namespace App\Console\Commands;

use App\Spot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SpotReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spot:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all spots not occupied by default';

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
        $spots = Spot::where('isoccupiedbydefault', '=', 0)
            ->whereNull('occupiedbydefaultby')
            ->whereNotNull('occupiedby')
            ->get();
        foreach ($spots as $spot) {
            $spot->occupiedby = null;
            $spot->releasedat = Carbon::now();
            $spot->save();
        }
        $this->info(count($spots).' spot(s) successfully cleared !');
    }
}
