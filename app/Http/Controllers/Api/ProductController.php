<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Get all products with category and supplier relationships
    public function index()
    {
        // Load products with their related category and supplier
        $products = Product::with(['category', 'supplier'])->get();
        return response()->json($products);
    }

    // Create a product
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'product_code' => 'required|string|unique:products',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'root' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    // Get a product by ID
    public function show($id)
    {
        $product = Product::with(['category', 'supplier'])->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Get a product by barcode
    public function showByBarcode($barcode)
    {
        $product = Product::with(['category', 'supplier'])->where('product_code', $barcode)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Update a product by barcode
    public function updateByBarcode(Request $request, $barcode)
    {
        $product = Product::where('product_code', $barcode)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'product_name' => 'sometimes|string',
            'product_code' => 'sometimes|string|unique:products,product_code,' . $product->id,
            'buying_price' => 'sometimes|numeric',
            'selling_price' => 'sometimes|numeric',
            'quantity' => 'sometimes|numeric',
            'root' => 'sometimes|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
            'supplier_id' => 'sometimes|exists:suppliers,id',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json(['message' => 'Product updated successfully']);
    }

    // Delete a product by barcode
    public function destroyByBarcode($barcode)
    {
        $product = Product::where('product_code', $barcode)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    // Search products by name
    public function searchByName(Request $request)
    {
        $name = $request->query('name');
        $products = Product::with(['category', 'supplier'])
            ->where('product_name', 'like', "%$name%")
            ->get();
        return response()->json($products);
    }
}
