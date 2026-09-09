<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['vehicle', 'user'])->where('user_id', auth()->id());

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('category', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $expenses = $query->latest()->paginate(15)->withQueryString();

        return view('employee.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('is_active', true)->orderBy('license_plate')->get();

        return view('employee.expenses.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Expense::create($validated);

        return redirect()->route('employee.expenses.index')->with('swal_success', 'Pengeluaran berhasil dicatat.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['vehicle', 'user']);

        return view('employee.expenses.show', compact('expense'));
    }
}
