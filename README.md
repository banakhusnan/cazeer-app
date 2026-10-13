# Cazeer

Aplikasi POS mobile-first untuk mengelola produk, stok, penjualan, dan laporan berbagai jenis bisnis. Dibangun dengan Laravel 12, Blade, Bootstrap 5, jQuery Ajax, dan DataTables.

## Fitur

- Login admin/kasir dan pembatasan menu berdasarkan role.
- CRUD produk dan pengguna melalui modal Ajax.
- Barang masuk, penyesuaian, dan riwayat stok.
- Keranjang jQuery Ajax dengan harga per transaksi.
- Checkout atomik dengan penguncian stok dan perlindungan transaksi ganda.
- Pembayaran tunai, transfer, dan QRIS.
- Nota thermal BP ECO58D ukuran 58 mm dengan logo, alamat, dan nomor HP.
- Riwayat, cetak ulang, laporan periode, produk terlaris, dan ekspor CSV.
- PWA dasar dengan halaman offline informatif.
- Backup harian dan perintah restore dengan konfirmasi eksplisit.

## Menjalankan aplikasi

Pastikan konfigurasi database di `.env` benar, lalu jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Akun awal hasil seeder:

- Admin: `admin` / `password`
- Kasir: `kasir` / `password`

Ganti kata sandi akun awal sebelum penggunaan produksi.

## Pengujian dan format

```bash
php artisan test
vendor/bin/pint --test
```

## Backup

Scheduler membuat backup setiap hari pukul 01:00. Jalankan scheduler melalui cron atau Task Scheduler:

```bash
php artisan schedule:run
```

Backup manual:

```bash
php artisan app:backup-database
```

Restore harus menggunakan path relatif pada disk local dan flag konfirmasi:

```bash
php artisan app:restore-database backups/nama-file.json.gz --force
```

Selalu uji restore pada database terpisah sebelum melakukan restore produksi.
