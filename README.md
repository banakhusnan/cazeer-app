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

### Jika migrasi gagal dengan error 1071 (max key length 1000 bytes)

Koneksi MySQL/MariaDB menggunakan InnoDB secara eksplisit agar tabel baru tidak mengikuti default server yang mungkin memakai MyISAM. Indeks `VARCHAR(255)` dengan `utf8mb4` dapat membutuhkan 1020 byte, melebihi batas 1000 byte MyISAM. Gunakan server dengan InnoDB yang mendukung indeks tersebut (misalnya MySQL 8 dengan row format DYNAMIC).

Setelah memperbarui kode, hapus cache konfigurasi:

```bash
php artisan config:clear
```

Migrasi yang gagal dapat meninggalkan tabel `users` walaupun migrasinya belum tercatat selesai. **Hanya untuk database instalasi baru yang seluruh isinya boleh dihapus**, buat ulang tabel:

```bash
php artisan migrate:fresh --seed
```

Perintah tersebut menghapus seluruh tabel pada database yang dipilih. Jika sudah ada data penting, jangan gunakan `migrate:fresh`: buat backup dan periksa engine, indeks, serta status migrasi sebelum memperbaiki tabel yang tertinggal. Perubahan konfigurasi engine hanya berlaku untuk tabel baru, bukan tabel yang sudah ada.

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
