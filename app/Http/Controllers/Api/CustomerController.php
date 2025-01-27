<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Get all customers
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    // Create a customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('customers', 'public');
        }

        $customer = Customer::create($data);

        return response()->json(['message' => 'Customer created successfully', 'customer' => $customer], 201);
    }

    // Get a customer by ID
    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        return response()->json($customer);
    }

    // Update a customer by ID
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'nullable|email',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
            }
            $data['photo'] = $request->file('photo')->store('customers', 'public');
        }

        $customer->update($data);

        return response()->json(['message' => 'Customer updated successfully']);
    }

    // Delete a customer by ID
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        if ($customer->photo) {
            Storage::disk('public')->delete($customer->photo);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
