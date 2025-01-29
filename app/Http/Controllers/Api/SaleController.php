<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    // Store a new sale
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

            // Create the sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'payment_method' => $validated['payment_method'],
                'sub_total' => $validated['sub_total'],
                'vat' => $validated['vat'],
                'total' => $validated['total'],
                'status' => 'completed', // Default status
            ]);

            // Add sale items and update product quantities
            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                // Deduct the sold quantity from the product stock
                $product = Product::find($item['product_id']);
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Sale created successfully.',
                'sale' => $sale,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create sale.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fetch all sales with optional category filter
    public function index(Request $request)
    {
        try {
            // Get the category_id from the request
            $categoryId = $request->input('category_id');

            // Start building the query
            $query = Sale::with(['customer', 'items.product']);

            // Apply the category filter if a category_id is provided
            if ($categoryId) {
                $query->whereHas('items.product', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }

            // Execute the query and get the results
            $sales = $query->get();

            return response()->json($sales);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch sales.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Return a sale
    public function returnSale($saleId)
    {
        $sale = Sale::findOrFail($saleId);

        // Check if the sale is already returned
        if ($sale->status === 'returned') {
            return response()->json([
                'message' => 'Sale is already returned.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Restore product quantities
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity; // Add the returned quantity back to stock
                    $product->save();
                }
            }

            // Mark the sale as returned
            $sale->status = 'returned';
            $sale->save();

            DB::commit();

            return response()->json([
                'message' => 'Sale returned successfully.',
                'sale' => $sale,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to return sale.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a sale
    public function destroy($saleId)
    {
        $sale = Sale::findOrFail($saleId);

        DB::beginTransaction();

        try {
            // Restore product quantities if needed
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }

            // Delete the sale
            $sale->delete();

            DB::commit();

            return response()->json([
                'message' => 'Sale deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete sale.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
