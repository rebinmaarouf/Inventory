<?php

namespace App\Http\Controllers\Api;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Index endpoint hit', ['search' => $request->input('search')]);

        $query = Salary::query()->with('employee');

        if ($request->has('search')) {
            $search = $request->input('search');
            Log::info('Search query:', ['search' => $search]);

            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orWhere('amount', 'like', '%' . $search . '%')
                ->orWhere('salary_date', 'like', '%' . $search . '%')
                ->orWhere('salary_month', 'like', '%' . $search . '%')
                ->orWhere('salary_year', 'like', '%' . $search . '%');
        }

        $salaries = $query->get();
        Log::info('Salaries fetched:', ['count' => $salaries->count(), 'data' => $salaries]);

        return response()->json($salaries);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'amount' => 'required|integer',
    //         'salary_date' => 'required|date',
    //         'salary_month' => 'required|string',
    //         'salary_year' => 'required|string',
    //     ]);

    //     $salary = Salary::create($request->all());

    //     return response()->json(['message' => 'Salary created successfully', 'salary' => $salary], 201);
    // }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'amount' => 'required|numeric', // Use 'numeric' instead of 'integer' to allow decimals
                'salary_date' => 'required|date',
                'salary_month' => 'required|string',
                'salary_year' => 'required|string',
            ]);

            $salary = Salary::create($validatedData);

            return response()->json(['message' => 'Salary created successfully', 'salary' => $salary], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating salary:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create salary'], 500);
        }
    }

    public function show($id)
    {
        $salary = Salary::with('employee')->find($id);
        if (!$salary) {
            return response()->json(['message' => 'Salary not found'], 404);
        }
        return response()->json($salary);
    }

    public function update(Request $request, $id)
    {
        Log::info('Update Request Payload:', $request->all());

        $salary = Salary::find($id);
        if (!$salary) {
            return response()->json(['message' => 'Salary not found'], 404);
        }

        $request->validate([
            'employee_id' => 'sometimes|exists:employees,id',
            'amount' => 'sometimes|integer',
            'salary_date' => 'sometimes|date',
            'salary_month' => 'sometimes|string',
            'salary_year' => 'sometimes|string',
        ]);

        $salary->update($request->only([
            'employee_id',
            'amount',
            'salary_date',
            'salary_month',
            'salary_year',
        ]));

        return response()->json(['message' => 'Salary updated successfully']);
    }

    public function destroy($id)
    {
        $salary = Salary::find($id);
        if (!$salary) {
            return response()->json(['message' => 'Salary not found'], 404);
        }

        $salary->delete();

        return response()->json(['message' => 'Salary deleted successfully']);
    }

    public function search(Request $request)
    {
        Log::info('Search endpoint hit', [
            'search' => $request->input('search'),
            'amount' => $request->input('amount'),
            'date' => $request->input('date'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
        ]);

        $query = Salary::query()->with('employee');

        if ($request->has('search')) {
            $search = $request->input('search');
            Log::info('Search query:', ['search' => $search]);

            // Search by employee name
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('amount') && is_numeric($request->input('amount'))) {
            $query->orWhere('amount', $request->input('amount'));
        }

        if ($request->has('date') && strtotime($request->input('date'))) {
            $query->orWhere('salary_date', $request->input('date'));
        }

        if ($request->has('month')) {
            $query->orWhere('salary_month', 'like', '%' . $request->input('month') . '%');
        }

        if ($request->has('year')) {
            $query->orWhere('salary_year', 'like', '%' . $request->input('year') . '%');
        }

        $salaries = $query->get();
        Log::info('Salaries fetched:', ['count' => $salaries->count()]);

        return response()->json($salaries);
    }
}
