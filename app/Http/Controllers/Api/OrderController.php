<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // Store a new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id', // Changed from customer_id to supplier_id
            'payment_method' => 'required|in:cash,card,bank',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Create the order
            $order = Order::create([
                'supplier_id' => $validated['supplier_id'], // Changed from customer_id to supplier_id
                'payment_method' => $validated['payment_method'],
                'sub_total' => $validated['sub_total'],
                'vat' => $validated['vat'],
                'total' => $validated['total'],
                'status' => 'completed', // Default status
            ]);

            // Add order items and update product quantities
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                // Increase the ordered quantity to the product stock
                $product = Product::find($item['product_id']);
                $product->quantity += $item['quantity']; // Changed from -= to +=
                $product->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully.',
                'order' => $order,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fetch all orders
    public function index(Request $request)
    {
        try {
            // Get the category_id from the request
            $categoryId = $request->input('category_id');

            // Start building the query
            $query = Order::with(['supplier', 'items.product']); // Changed from customer to supplier

            // Apply the category filter if a category_id is provided
            if ($categoryId) {
                $query->whereHas('items.product', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }

            // Execute the query and get the results
            $orders = $query->get();

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch orders.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Return an order
    public function returnOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Check if the order is already returned
        if ($order->status === 'returned') {
            return response()->json([
                'message' => 'Order is already returned.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Decrease product quantities (since we are returning the order)
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity -= $item->quantity; // Changed from += to -=
                    $product->save();
                }
            }

            // Mark the order as returned
            $order->status = 'returned';
            $order->save();

            DB::commit();

            return response()->json([
                'message' => 'Order returned successfully.',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to return order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($orderId)
    {
        $order = Order::findOrFail($orderId);

        DB::beginTransaction();

        try {
            // Decrease product quantities if needed
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity -= $item->quantity; // Changed from += to -=
                    $product->save();
                }
            }

            // Delete the order
            $order->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
