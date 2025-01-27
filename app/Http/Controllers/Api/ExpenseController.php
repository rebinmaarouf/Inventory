<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    // Get all expenses
    public function index()
    {
        $expenses = Expense::all();
        return response()->json($expenses);
    }

    // Create an expense
    // app/Http/Controllers/Api/ExpenseController.php
    public function store(Request $request)
    {
        $request->validate([
            'details' => 'required|string',
            'amount' => 'required|numeric',
            'expenses_date' => 'required|date', // Add validation for expenses_date
        ]);

        $expense = Expense::create($request->all());

        return response()->json(['message' => 'Expense created successfully', 'expense' => $expense], 201);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $request->validate([
            'details' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'expenses_date' => 'sometimes|date', // Add validation for expenses_date
        ]);

        $expense->update($request->all());

        return response()->json(['message' => 'Expense updated successfully']);
    }

    // Get an expense by ID
    public function show($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
        return response()->json($expense);
    }



    // Delete an expense by ID
    public function destroy($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
