<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDocument;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%")
                    ->orWhere('identity_number', 'like', "%{$request->search}%");
            });
        }

        if ($request->verification_status) {
            $query->where('verification_status', $request->verification_status);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('owner.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('owner.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|max:50',
            'identity_type' => 'nullable|string|max:50',
            'sim_number' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $validated['verification_status'] = 'unverified';
        Customer::create($validated);

        return redirect()->route('owner.customers.index')->with('swal_success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['documents', 'bookings' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('owner.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('owner.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|max:50',
            'identity_type' => 'nullable|string|max:50',
            'sim_number' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('owner.customers.show', $customer)->with('swal_success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('owner.customers.index')->with('swal_success', 'Pelanggan berhasil dihapus.');
    }

    public function verify(Customer $customer)
    {
        $customer->update(['verification_status' => 'verified']);

        return back()->with('swal_success', 'Pelanggan berhasil diverifikasi.');
    }

    public function verifyDocument(Customer $customer, CustomerDocument $document)
    {
        $document->update(['status' => 'verified']);

        return back()->with('swal_success', 'Dokumen berhasil diverifikasi.');
    }
}
