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
            'customer_id' => 'required|exists:customers,id',
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
            // Validate product quantities
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product->quantity < $item['quantity']) {
                    return response()->json([
                        'message' => 'Not enough stock for product: ' . $product->product_name,
                        'available_quantity' => $product->quantity,
                    ], 400);
                }
            }

            // Create the order
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
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

                // Deduct the ordered quantity from the product stock
                $product = Product::find($item['product_id']);
                $product->quantity -= $item['quantity'];
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
    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])->get();
        return response()->json($orders);
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
            // Restore product quantities
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity; // Add the returned quantity back to stock
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
}
