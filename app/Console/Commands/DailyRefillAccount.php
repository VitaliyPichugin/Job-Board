<?php

namespace App\Console\Commands;

use App\Services\CoinService;
use Illuminate\Console\Command;

class DailyRefillAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refill:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily refill of the account';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CoinService::addCoins();
        $this->info('Successfully refill daily of the account.');
    }
}
