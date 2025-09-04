<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // -- Data Helper Function --
        $getStats = function ($query, $isSum = false, $sumColumn = null, $dateColumn = 'created_at') {
            $now = Carbon::now();
            
            $calculatePercentage = function ($new, $old) {
                if ($old == 0) return 0; // Or handle as infinite growth
                return round((($new - $old) / $old) * 100);
            };

            // Last 30 days
            $newData = $query->clone()->whereBetween($dateColumn, [$now->copy()->subDays(30), $now]);
            $newCount = $isSum ? $newData->sum($sumColumn) : $newData->count();

            // Previous 30 days
            $oldData = $query->clone()->whereBetween($dateColumn, [$now->copy()->subDays(60), $now->copy()->subDays(31)]);
            $oldCount = $isSum ? $oldData->sum($sumColumn) : $oldData->count();

            // Sparkline data (daily for last 30 days)
            $sparklineQuery = $query->clone()
                ->select(DB::raw("DATE($dateColumn) as date"));
            
            if ($isSum) {
                $sparklineQuery->addSelect(DB::raw("SUM($sumColumn) as value"));
            } else {
                $sparklineQuery->addSelect(DB::raw('count(*) as value'));
            }

            $sparklineData = $sparklineQuery->whereBetween($dateColumn, [$now->copy()->subDays(30), $now])
                ->groupBy('date')->orderBy('date', 'ASC')->pluck('value')->toArray();

            return [
                'count' => $newCount,
                'percentage' => $calculatePercentage($newCount, $oldCount),
                'sparkline' => $sparklineData,
            ];
        };

        // -- Stat Cards Data --
        $revenueStats = $getStats(Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered']), true, 'total_amount');
        $orderStats = $getStats(new Order);
        $customerStats = $getStats(new User);

        // Specific logic for Products Sold, as it requires a join
        $salesQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered']);
        $productSalesStats = $getStats($salesQuery, true, 'order_items.quantity', 'orders.created_at');

        // -- Main Chart: Monthly Orders --
        $monthlyOrders = Order::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')->orderBy('month', 'asc')->get()->keyBy('month');

        $chartLabels = [];
        $chartData = array_fill(0, 12, 0);
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = date('M', mktime(0, 0, 0, $i, 1));
            if (isset($monthlyOrders[$i])) {
                $chartData[$i - 1] = $monthlyOrders[$i]->count;
            }
        }

        // -- Lists --
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $topSellingProducts = Product::orderBy('total_sold', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'chartLabels',
            'chartData',
            'revenueStats',
            'orderStats',
            'customerStats',
            'productSalesStats',
            'recentOrders',
            'topSellingProducts'
        ));
    }
}
