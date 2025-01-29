<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\SaleItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get filters from the request
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);
        $week = $request->query('week', 1);

        // Total Sales (Total across all time, regardless of month)
        $totalSales = (float) Sale::sum('total') ?: 0;

        // Monthly Sales (Filtered by month and year)
        $monthlySales = (float) Sale::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total') ?: 0;

        // Total Orders (Filtered by month and year)
        $totalOrders = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() ?: 0;

        // Top Selling Item
        $topSellingItem = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->first();

        $topSellingItem = $topSellingItem ? [
            'product_name' => $topSellingItem->product->product_name ?? 'No product',
            'category_name' => $topSellingItem->product->category->category_name ?? 'N/A',
        ] : ['product_name' => 'No sales yet', 'category_name' => 'N/A'];

        // Top Selling Items
        $topItems = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as amount'))
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $product = $item->product;  // Eager-loaded product
                return [
                    'product_name' => $product->product_name,
                    'product_id' => $product->id,
                    'total_sales' => $item->total_quantity,
                    'amount' => $item->amount,
                ];
            });

        // Weekly Sales Data
        $weeklySales = Sale::selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(total) as total_sales')
            ->whereYear('created_at', $year)
            ->whereRaw('WEEK(created_at) = ?', [$week])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('WEEK(created_at)'))
            ->orderByDesc(DB::raw('WEEK(created_at)'))
            ->get();

        // Sales by Supplier (Salesman)
        $salesmanChart = Supplier::withCount('orders')
            ->orderByDesc('orders_count')
            ->get(['name', 'orders_count']);

        // Top Selling Categories
        $categoryChart = Product::selectRaw('categories.category_name, COUNT(sale_items.product_id) as count')
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.category_name')
            ->orderByDesc('count')
            ->get();

        // Monthly Sales Data
        $salesChart = Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total_sales')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'topSellingItem' => $topSellingItem,
            'topItems' => $topItems,
            'monthlySales' => $monthlySales,
            'weeklySales' => $weeklySales,
            'categoryChart' => $categoryChart,
            'salesChart' => $salesChart,
        ]);
    }


    public function getTopItems()
    {
        // Assuming you have a SaleItem model that relates to products
        return SaleItem::with('product')  // Eager load the product and category
            ->selectRaw('product_id, SUM(quantity) as total_sales, SUM(quantity * price) as amount')
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $product = $item->product;  // Already eager-loaded
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'total_sales' => $item->total_sales,
                    'amount' => $item->amount,
                ];
            });
    }
}
