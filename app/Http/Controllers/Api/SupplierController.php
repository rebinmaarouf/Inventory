<?php

namespace App\Http\Controllers\Api;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json(Supplier::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'shop_name' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Supplier::create($data);

        return response()->json(['message' => 'Supplier added successfully']);
    }

    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'shop_name' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($supplier->photo) {
                Storage::disk('public')->delete($supplier->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $supplier->update($data);

        return response()->json(['message' => 'Supplier updated successfully']);
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->photo) {
            Storage::disk('public')->delete($supplier->photo);
        }
        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
