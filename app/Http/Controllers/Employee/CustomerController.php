<?php

namespace App\Http\Controllers\Employee;

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
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('employee.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('employee.customers.create');
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

        return redirect()->route('employee.customers.index')->with('swal_success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['documents', 'bookings' => function ($q) {
            $q->latest()->take(5);
        }]);

        return view('employee.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('employee.customers.edit', compact('customer'));
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

        return redirect()->route('employee.customers.show', $customer)->with('swal_success', 'Data pelanggan berhasil diperbarui.');
    }

    public function verify(Customer $customer)
    {
        $customer->update(['verification_status' => 'verified']);

        return back()->with('swal_success', 'Pelanggan berhasil diverifikasi.');
    }

    public function storeDocument(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'document_number' => 'nullable|string|max:50',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['file_path'] = $request->file('file')->store('customer-documents', 'public');
        $validated['status'] = 'pending';

        CustomerDocument::create($validated);

        return back()->with('swal_success', 'Dokumen berhasil diunggah.');
    }

    public function verifyDocument(Customer $customer, CustomerDocument $document)
    {
        $document->update(['status' => 'verified']);

        return back()->with('swal_success', 'Dokumen berhasil diverifikasi.');
    }
}
