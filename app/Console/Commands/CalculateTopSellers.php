<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TopSellerService;

class CalculateTopSellers extends Command
{
    protected $signature   = 'top:sellers:calculate {--month=} {--year=} {--threshold=5}';
    protected $description = 'Calculate monthly top sellers (by quantity sold)';

    public function handle()
    {
        $month     = $this->option('month');
        $year      = $this->option('year');
        $threshold = (int) $this->option('threshold') ?: 5;

        $service = new TopSellerService();
        $saved   = $service->calculate($month, $year, $threshold);

        $this->info('Saved top sellers: ' . count($saved));
        return 0;
    }
}
