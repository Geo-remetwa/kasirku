# Kasiraku

Kasiraku adalah aplikasi kasir untuk toko yang menangani pencatatan transaksi penjualan, manajemen produk, dan pengaturan akses pengguna

### Tampilan

Login.png

## Installation

Requirements: PHP 8.2+, Composer, and MySQL/MariaDB

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create a new database, then fill in the credentials in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=
```

Continue with migration and seeding:

```bash
php artisan migrate --seed
php artisan storage:link
```

A default admin account is created automatically by the seeder:

- **Email:** `admintoko@gmail.com`
- **Password:** `Toko112233`

```bash
php artisan serve
```

**Lain-lain**

Projeck app kelompok 1 Akir semester
