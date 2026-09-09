# Sistem Manajemen Rental Mobil

Aplikasi website manajemen rental mobil berbasis Laravel 13 dengan MySQL.

## Requirement

- PHP 8.3+
- Composer
- MySQL 5.7+ / MariaDB 10.3+
- Node.js & NPM (optional, untuk asset build)

## Instalasi

```bash
# Clone repository
git clone https://github.com/ardhikaxx/sistem-rental-mobil.git
cd sistem-rental-mobil

# Install dependencies
composer install

# Copy .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_mobil
DB_USERNAME=root
DB_PASSWORD=

# Buat database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS rental_mobil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# Buat storage link
php artisan storage:link

# Jalankan aplikasi
php artisan serve
```

Aplikasi dapat diakses di http://localhost:8000

## Akun Demo

### Owner/Admin
- **Username:** admin
- **PIN:** 1234

### Karyawan
- **Username:** andi
- **PIN:** 1234

- **Username:** rina
- **PIN:** 1234

- **Username:** dedi
- **PIN:** 1234

## Fitur Utama

### Owner/Admin
- Dashboard eksekutif dengan KPI dan grafik
- Manajemen armada kendaraan
- Kalender armada
- Manajemen pelanggan dan verifikasi
- Monitoring booking
- Laporan keuangan
- Manajemen karyawan
- Approval sistem
- Audit log

### Karyawan
- Dashboard operasional harian
- Kalender armada
- Pembuatan booking
- Manajemen pelanggan
- Check-in/check-out kendaraan
- Surat jalan digital
- Kasir pembayaran
- Pencatatan pengeluaran

## Teknologi

- **Backend:** Laravel 13
- **Database:** MySQL
- **Frontend:** Bootstrap 5 (CDN)
- **Icon:** Font Awesome 6 (CDN)
- **Alert:** SweetAlert2 (CDN)
- **Chart:** Chart.js (CDN)
- **Calendar:** FullCalendar.js (CDN)

## Struktur Project

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Owner/
│   │   │   └── Employee/
│   │   └── Middleware/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── auth/
│       ├── layouts/
│       ├── owner/
│       └── employee/
├── routes/
│   └── web.php
└── public/
    └── css/
```

## License

MIT License
