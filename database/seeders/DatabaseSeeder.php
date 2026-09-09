<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Inspection;
use App\Models\MaintenanceRecord;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Owner
        $owner = User::create([
            'name' => 'Budi Santoso',
            'username' => 'admin',
            'pin' => Hash::make('1234'),
            'phone' => '081234567890',
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Create Employees
        $employees = [
            ['name' => 'Andi Prasetyo', 'username' => 'andi', 'phone' => '081234567891'],
            ['name' => 'Rina Wulandari', 'username' => 'rina', 'phone' => '081234567892'],
            ['name' => 'Dedi Kurniawan', 'username' => 'dedi', 'phone' => '081234567893'],
        ];

        $employeeModels = [];
        foreach ($employees as $emp) {
            $employeeModels[] = User::create([
                'name' => $emp['name'],
                'username' => $emp['username'],
                'pin' => Hash::make('1234'),
                'phone' => $emp['phone'],
                'role' => 'employee',
                'is_active' => true,
            ]);
        }

        // Create Vehicles
        $vehiclesData = [
            ['license_plate' => 'B 1234 ABC', 'brand' => 'Toyota', 'model' => 'Avanza', 'year' => 2023, 'color' => 'Putih', 'transmission' => 'automatic', 'fuel_type' => 'gasoline', 'passenger_count' => 7, 'daily_rate' => 350000, 'weekly_rate' => 2200000, 'monthly_rate' => 8500000, 'status' => 'available', 'current_odometer' => 15000],
            ['license_plate' => 'B 5678 DEF', 'brand' => 'Honda', 'model' => 'Brio', 'year' => 2023, 'color' => 'Merah', 'transmission' => 'automatic', 'fuel_type' => 'gasoline', 'passenger_count' => 5, 'daily_rate' => 300000, 'weekly_rate' => 1900000, 'monthly_rate' => 7500000, 'status' => 'available', 'current_odometer' => 12000],
            ['license_plate' => 'B 9012 GHI', 'brand' => 'Toyota', 'model' => 'Innova', 'year' => 2022, 'color' => 'Hitam', 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'passenger_count' => 7, 'daily_rate' => 500000, 'weekly_rate' => 3200000, 'monthly_rate' => 12000000, 'status' => 'rented', 'current_odometer' => 35000],
            ['license_plate' => 'B 3456 JKL', 'brand' => 'Daihatsu', 'model' => 'Xenia', 'year' => 2023, 'color' => 'Silver', 'transmission' => 'manual', 'fuel_type' => 'gasoline', 'passenger_count' => 7, 'daily_rate' => 300000, 'weekly_rate' => 1900000, 'monthly_rate' => 7000000, 'status' => 'available', 'current_odometer' => 8000],
            ['license_plate' => 'B 7890 MNO', 'brand' => 'Suzuki', 'model' => 'Ertiga', 'year' => 2022, 'color' => 'Abu-abu', 'transmission' => 'manual', 'fuel_type' => 'gasoline', 'passenger_count' => 7, 'daily_rate' => 320000, 'weekly_rate' => 2000000, 'monthly_rate' => 7800000, 'status' => 'maintenance', 'current_odometer' => 28000],
            ['license_plate' => 'B 2345 PQR', 'brand' => 'Toyota', 'model' => 'Fortuner', 'year' => 2023, 'color' => 'Putih', 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'passenger_count' => 7, 'daily_rate' => 700000, 'weekly_rate' => 4500000, 'monthly_rate' => 17000000, 'status' => 'available', 'current_odometer' => 20000],
            ['license_plate' => 'B 6789 STU', 'brand' => 'Mitsubishi', 'model' => 'Xpander', 'year' => 2023, 'color' => 'Biru', 'transmission' => 'automatic', 'fuel_type' => 'gasoline', 'passenger_count' => 7, 'daily_rate' => 380000, 'weekly_rate' => 2400000, 'monthly_rate' => 9200000, 'status' => 'booked', 'current_odometer' => 10000],
            ['license_plate' => 'B 0123 VWX', 'brand' => 'Honda', 'model' => 'Civic', 'year' => 2023, 'color' => 'Hitam', 'transmission' => 'automatic', 'fuel_type' => 'gasoline', 'passenger_count' => 5, 'daily_rate' => 450000, 'weekly_rate' => 2900000, 'monthly_rate' => 11000000, 'status' => 'available', 'current_odometer' => 5000],
        ];

        $vehicleModels = [];
        foreach ($vehiclesData as $v) {
            $v['tax_expiry_date'] = now()->addMonths(rand(1, 12));
            $v['insurance_expiry_date'] = now()->addMonths(rand(1, 12));
            $vehicleModels[] = Vehicle::create($v);
        }

        // Create Customers
        $customersData = [
            ['name' => 'Ahmad Rizky', 'phone' => '085612345678', 'address' => 'Jl. Sudirman No. 10, Jakarta Selatan', 'identity_number' => '3175012345678901', 'identity_type' => 'KTP', 'sim_number' => '1234567890123', 'verification_status' => 'verified'],
            ['name' => 'Siti Nurhaliza', 'phone' => '085723456789', 'address' => 'Jl. Thamrin No. 25, Jakarta Pusat', 'identity_number' => '3175023456789012', 'identity_type' => 'KTP', 'sim_number' => '2345678901234', 'verification_status' => 'verified'],
            ['name' => 'Rudi Hartono', 'phone' => '085834567890', 'address' => 'Jl. Gatot Subroto No. 5, Bandung', 'identity_number' => '3273013456789023', 'identity_type' => 'KTP', 'sim_number' => '3456789012345', 'verification_status' => 'verified'],
            ['name' => 'Dewi Sartika', 'phone' => '081945678901', 'address' => 'Jl. Asia Afrika No. 15, Bandung', 'identity_number' => '3273024567890134', 'identity_type' => 'KTP', 'verification_status' => 'unverified'],
            ['name' => 'Hendra Wijaya', 'phone' => '082156789012', 'address' => 'Jl. Diponegoro No. 30, Semarang', 'identity_number' => '3374015678901245', 'identity_type' => 'KTP', 'sim_number' => '5678901234567', 'verification_status' => 'verified'],
            ['name' => 'Maya Anggraeni', 'phone' => '083267890123', 'address' => 'Jl. Malioboro No. 20, Yogyakarta', 'identity_number' => '3471016789012356', 'identity_type' => 'KTP', 'verification_status' => 'problem'],
        ];

        $customerModels = [];
        foreach ($customersData as $c) {
            $customerModels[] = Customer::create($c);
        }

        // Create Bookings
        $bookingsData = [
            [
                'customer_id' => $customerModels[0]->id,
                'vehicle_id' => $vehicleModels[0]->id,
                'user_id' => $employeeModels[0]->id,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(2),
                'rental_days' => 7,
                'daily_rate_snapshot' => 350000,
                'rental_subtotal' => 2450000,
                'total_amount' => 2450000,
                'dp_amount' => 500000,
                'paid_amount' => 1000000,
                'remaining_amount' => 950000,
                'status' => 'rented',
            ],
            [
                'customer_id' => $customerModels[1]->id,
                'vehicle_id' => $vehicleModels[6]->id,
                'user_id' => $employeeModels[1]->id,
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(4),
                'rental_days' => 3,
                'daily_rate_snapshot' => 380000,
                'rental_subtotal' => 1140000,
                'total_amount' => 1140000,
                'dp_amount' => 300000,
                'paid_amount' => 300000,
                'remaining_amount' => 840000,
                'status' => 'booked',
            ],
            [
                'customer_id' => $customerModels[2]->id,
                'vehicle_id' => $vehicleModels[2]->id,
                'user_id' => $employeeModels[0]->id,
                'start_date' => now()->subDays(10),
                'end_date' => now()->subDays(3),
                'rental_days' => 7,
                'daily_rate_snapshot' => 500000,
                'rental_subtotal' => 3500000,
                'total_amount' => 3500000,
                'dp_amount' => 1000000,
                'paid_amount' => 3500000,
                'remaining_amount' => 0,
                'status' => 'completed',
            ],
            [
                'customer_id' => $customerModels[4]->id,
                'vehicle_id' => $vehicleModels[7]->id,
                'user_id' => $employeeModels[2]->id,
                'start_date' => now(),
                'end_date' => now()->addDays(2),
                'rental_days' => 2,
                'daily_rate_snapshot' => 450000,
                'rental_subtotal' => 900000,
                'total_amount' => 900000,
                'dp_amount' => 0,
                'paid_amount' => 0,
                'remaining_amount' => 900000,
                'status' => 'pending_payment',
            ],
        ];

        $bookingModels = [];
        foreach ($bookingsData as $b) {
            $b['booking_code'] = 'BK'.now()->subDays(rand(0, 10))->format('Ymd').strtoupper(substr(uniqid(), -6));
            $bookingModels[] = Booking::create($b);
        }

        // Create Payments
        $paymentsData = [
            ['booking_id' => $bookingModels[0]->id, 'user_id' => $employeeModels[0]->id, 'amount' => 500000, 'type' => 'dp', 'method' => 'cash', 'status' => 'confirmed'],
            ['booking_id' => $bookingModels[0]->id, 'user_id' => $employeeModels[0]->id, 'amount' => 500000, 'type' => 'installment', 'method' => 'transfer', 'bank_name' => 'BCA', 'status' => 'confirmed'],
            ['booking_id' => $bookingModels[1]->id, 'user_id' => $employeeModels[1]->id, 'amount' => 300000, 'type' => 'dp', 'method' => 'cash', 'status' => 'confirmed'],
            ['booking_id' => $bookingModels[2]->id, 'user_id' => $employeeModels[0]->id, 'amount' => 1000000, 'type' => 'dp', 'method' => 'cash', 'status' => 'confirmed'],
            ['booking_id' => $bookingModels[2]->id, 'user_id' => $employeeModels[0]->id, 'amount' => 2500000, 'type' => 'final_payment', 'method' => 'transfer', 'bank_name' => 'Mandiri', 'status' => 'confirmed'],
        ];

        foreach ($paymentsData as $p) {
            $p['transaction_code'] = 'TRX'.now()->format('Ymd').strtoupper(substr(uniqid(), -6));
            Payment::create($p);
        }

        // Create Inspections
        Inspection::create([
            'booking_id' => $bookingModels[0]->id,
            'vehicle_id' => $vehicleModels[0]->id,
            'user_id' => $employeeModels[0]->id,
            'type' => 'check_in',
            'inspection_date' => now()->subDays(5),
            'odometer' => 14500,
            'fuel_level' => 'full',
            'exterior_condition' => 'Kondisi prima, tidak ada lecet',
            'interior_condition' => 'Bersih dan rapi',
            'equipment_condition' => 'Kelengkapan lengkap',
        ]);

        // Create Expenses
        $expensesData = [
            ['expense_date' => now()->subDays(3), 'category' => 'Cuci Mobil', 'amount' => 50000, 'vehicle_id' => $vehicleModels[0]->id, 'user_id' => $employeeModels[0]->id, 'description' => 'Cuci mobil Avanza B 1234 ABC', 'status' => 'verified'],
            ['expense_date' => now()->subDays(2), 'category' => 'BBM Darurat', 'amount' => 150000, 'vehicle_id' => $vehicleModels[2]->id, 'user_id' => $employeeModels[0]->id, 'description' => 'Isi BBM Innova saat rental', 'status' => 'verified'],
            ['expense_date' => now()->subDays(1), 'category' => 'Parkir', 'amount' => 25000, 'user_id' => $employeeModels[1]->id, 'description' => 'Parkir kantor', 'status' => 'pending'],
            ['expense_date' => now(), 'category' => 'Tambal Ban', 'amount' => 75000, 'vehicle_id' => $vehicleModels[4]->id, 'user_id' => $employeeModels[2]->id, 'description' => 'Tambal ban Ertiga', 'status' => 'pending'],
        ];

        foreach ($expensesData as $e) {
            Expense::create($e);
        }

        // Create Maintenance
        MaintenanceRecord::create([
            'vehicle_id' => $vehicleModels[4]->id,
            'service_type' => 'Servis Berkala',
            'service_date' => now(),
            'odometer_at_service' => 28000,
            'workshop' => 'Bengkel Resmi Suzuki',
            'cost' => 1500000,
            'description' => 'Ganti oli, filter, dan servis rutin',
            'status' => 'in_progress',
            'next_service_date' => now()->addMonths(3),
            'next_service_odometer' => 33000,
        ]);

        // Create Approval
        Approval::create([
            'user_id' => $employeeModels[1]->id,
            'type' => 'discount',
            'reference_type' => Booking::class,
            'reference_id' => $bookingModels[1]->id,
            'amount' => 100000,
            'reason' => 'Pelanggan member lama, minta diskon',
            'status' => 'pending',
        ]);

        // Create Audit Logs
        $auditData = [
            ['user_id' => $owner->id, 'role' => 'owner', 'action' => 'login', 'module' => 'auth', 'description' => 'Owner login'],
            ['user_id' => $employeeModels[0]->id, 'role' => 'employee', 'action' => 'login', 'module' => 'auth', 'description' => 'Employee login'],
            ['user_id' => $employeeModels[0]->id, 'role' => 'employee', 'action' => 'create', 'module' => 'booking', 'reference_type' => Booking::class, 'reference_id' => $bookingModels[0]->id, 'description' => 'Booking baru dibuat'],
            ['user_id' => $employeeModels[0]->id, 'role' => 'employee', 'action' => 'check_in', 'module' => 'inspection', 'description' => 'Check-in kendaraan Avanza'],
            ['user_id' => $owner->id, 'role' => 'owner', 'action' => 'create', 'module' => 'vehicle', 'description' => 'Kendaraan baru ditambahkan'],
        ];

        foreach ($auditData as $a) {
            $a['ip_address'] = '127.0.0.1';
            AuditLog::create($a);
        }
    }
}
