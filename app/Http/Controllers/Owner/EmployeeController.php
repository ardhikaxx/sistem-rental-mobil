<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'employee');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('username', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $employees = $query->latest()->paginate(15)->withQueryString();

        return view('owner.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('owner.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'pin' => 'required|string|size:4|digits:4',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['pin'] = Hash::make($validated['pin']);
        $validated['role'] = 'employee';
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('owner.employees.index')->with('swal_success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(User $employee)
    {
        $employee->load(['bookings' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('owner.employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        return view('owner.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,'.$employee->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update($validated);

        return redirect()->route('owner.employees.show', $employee)->with('swal_success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();

        return redirect()->route('owner.employees.index')->with('swal_success', 'Karyawan berhasil dihapus.');
    }

    public function toggleActive(User $employee)
    {
        $employee->update(['is_active' => ! $employee->is_active]);
        $status = $employee->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('swal_success', "Karyawan berhasil {$status}.");
    }

    public function resetPin(Request $request, User $employee)
    {
        $request->validate([
            'new_pin' => 'required|string|size:4|digits:4',
        ]);

        $employee->update(['pin' => Hash::make($request->new_pin)]);

        return back()->with('swal_success', 'PIN karyawan berhasil direset.');
    }
}
