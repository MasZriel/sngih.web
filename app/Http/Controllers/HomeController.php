<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Get best-selling products from completed orders
        $bestSellers = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
            ->groupBy('products.id')
            ->orderByDesc('total_quantity')
            ->take(8)
            ->get();

        // Get promotional products
        $promotionalProducts = Product::whereNotNull('harga_diskon')->where('harga_diskon', '>', 0)->take(8)->get();

        return view('home', compact('bestSellers', 'promotionalProducts'));
    }
}
