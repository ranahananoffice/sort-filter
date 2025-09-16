<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\TopSeller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TopSellerService
{
    /**
     * Calculate & save top sellers.
     * Returns array/list of created/updated TopSeller models.
     */
    public function calculate(?int $month = null, ?int $year = null, int $threshold = 5)
    {
        $month = $month ?: Carbon::now()->month;
        $year  = $year ?: Carbon::now()->year;

        $sellers = Sale::select('userId', DB::raw('SUM(quantity) as total'))
            ->whereRaw('YEAR(FROM_UNIXTIME(createdAt)) = ?', [$year])
            ->whereRaw('MONTH(FROM_UNIXTIME(createdAt)) = ?', [$month])
            ->groupBy('userId')
            ->havingRaw('SUM(quantity) > ?', [$threshold])
            ->get();

        $saved = [];

        Product::query()->update(['isTopSeller' => false]);

        foreach ($sellers as $s) {
            // Save in topSellers table
            $saved[] = TopSeller::updateOrCreate(
                ['userId' => $s->userId, 'year' => $year, 'month' => $month],
                ['totalSales' => $s->total]
            );

            // Mark all this user's products as top seller
            Product::where('userId', $s->userId)->update(['isTopSeller' => true]);
        }

        return $saved;
    }
}
