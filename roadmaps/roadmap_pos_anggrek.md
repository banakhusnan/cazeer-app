# Roadmap Aplikasi POS Penjualan Anggrek

## 1. Tujuan Aplikasi

Membangun aplikasi POS sederhana untuk penjualan anggrek yang:

- Dominan digunakan melalui Android dan iOS.
- Tetap nyaman digunakan melalui PC atau laptop.
- Memiliki dua peran pengguna: admin dan kasir.
- Menyimpan data produk, stok, penjualan, dan rincian barang yang dijual.
- Memungkinkan harga ditentukan ketika barang dimasukkan ke keranjang.
- Dapat mencetak nota menggunakan printer thermal BP ECO58D dengan lebar kertas 58 mm.
- Meminimalkan jumlah langkah dan input yang harus dilakukan pengguna.

## 2. Prinsip Desain Utama

### Mobile-first

Tampilan dirancang lebih dahulu untuk layar ponsel, kemudian diperluas untuk tablet dan desktop.

### Sedikit menu

Navigasi utama maksimal terdiri dari:

1. Beranda
2. Jual
3. Riwayat
4. Lainnya

Menu khusus admin seperti Produk, Stok, Pengguna, dan Laporan ditempatkan di halaman **Lainnya** agar navigasi utama tidak penuh.

### Tombol mudah ditekan

- Tinggi tombol utama minimal sekitar 44–48 piksel.
- Gunakan teks dan ikon secara bersamaan.
- Tombol utama seperti `Tambah ke Keranjang`, `Bayar`, dan `Cetak Nota` dibuat paling menonjol.
- Hindari tombol kecil yang saling berdekatan.

### Tipografi ringkas dan konsisten

- Ukuran font antarmuka utama menggunakan `0.9rem` (sekitar `14.4px` pada ukuran root bawaan browser), termasuk judul, isi, label, tombol, tabel, dan navigasi.
- Jika menggunakan satuan selain `rem`, ukuran visual teks biasa disetarakan dengan `0.9rem`; hierarki tetap mengutamakan ketebalan, warna, dan spasi.
- Perbedaan hierarki informasi dibuat melalui ketebalan font, warna, spasi, dan kapitalisasi, bukan dengan memperbesar font.
- Ketentuan ukuran font tidak mengurangi ukuran sentuh tombol: tinggi area tekan utama tetap minimal 44–48 piksel.

### Form sesingkat mungkin

- Hanya tampilkan kolom wajib.
- Gunakan keyboard numerik untuk jumlah, harga, dan pembayaran.
- Nilai default disediakan jika aman, misalnya jumlah barang otomatis `1`.
- Validasi ditampilkan langsung di dekat kolom yang salah.

### Tidak memaksa tabel lebar di ponsel

- Daftar transaksi dan produk ditampilkan sebagai kartu pada ponsel.
- Tabel dapat digunakan pada PC.
- Detail tambahan dibuka melalui halaman detail atau panel bawah.

## 3. Peran Pengguna

### Admin

Admin dapat:

- Mengelola akun admin dan kasir.
- Mengelola produk.
- Menambah dan menyesuaikan stok.
- Melakukan transaksi penjualan.
- Melihat seluruh riwayat penjualan.
- Melihat laporan penjualan sederhana.
- Mencetak nota dan mencetak ulang nota transaksi dari detail atau riwayat penjualan.

### Kasir

Kasir dapat:

- Melakukan transaksi penjualan.
- Mencetak ulang nota transaksi yang dibuatnya.
- Melihat riwayat transaksi sesuai batasan yang ditentukan admin.

## 4. Struktur Menu

### Navigasi utama

#### Beranda

Informasi ringkas:

- Total penjualan hari ini.
- Jumlah transaksi hari ini.
- Produk dengan stok menipis.
- Tombol besar `Mulai Penjualan`.

Kasir tidak perlu melihat grafik yang rumit. Informasi utama cukup ditampilkan dalam beberapa kartu ringkas.

#### Jual

Halaman utama transaksi:

- Pencarian produk.
- Daftar produk aktif.
- Keranjang.
- Total pembayaran.
- Tombol checkout.

#### Riwayat

- Daftar transaksi terbaru.
- Pencarian berdasarkan nomor nota.
- Filter tanggal sederhana.
- Detail transaksi.
- Cetak ulang nota.

#### Lainnya

Untuk admin:

- Produk
- Stok
- Pengguna
- Laporan
- Profil
- Keluar

Untuk kasir:

- Profil
- Keluar

## 5. Alur Transaksi yang Disarankan

### Langkah 1 — Memilih produk

Kasir membuka menu `Jual`, kemudian:

- Mencari berdasarkan kode atau nama produk.
- Menekan kartu produk.

### Langkah 2 — Memasukkan harga

Setelah produk dipilih, tampilkan panel sederhana berisi:

- Nama produk.
- Stok tersedia.
- Jumlah, default `1`.
- Harga jual, wajib diisi.
- Catatan, opsional.
- Tombol `Tambah ke Keranjang`.

Harga tidak diambil dari tabel produk karena harga ditentukan pada saat transaksi.

### Langkah 3 — Meninjau keranjang

Keranjang menampilkan:

- Nama produk.
- Jumlah.
- Harga per barang.
- Subtotal per baris.
- Tombol ubah.
- Tombol hapus.

Produk yang sama boleh memiliki harga berbeda. Sistem tidak harus menggabungkan kedua baris tersebut jika harganya berbeda.

### Langkah 4 — Checkout

Halaman checkout hanya memuat:

- Total transaksi.
- Metode pembayaran: tunai, transfer, atau QRIS.
- Uang diterima jika pembayaran tunai.
- Uang kembalian.
- Nama pelanggan, opsional.
- Catatan, opsional.
- Tombol `Selesaikan Transaksi`.

Saat tombol `Selesaikan Transaksi` ditekan dan data checkout telah valid, tampilkan Bootstrap Modal yang menanyakan **"Cetak nota transaksi setelah checkout?"** dengan pilihan:

- `Ya, Selesaikan & Cetak`
- `Tidak, Selesaikan Tanpa Cetak`
- `Batal` untuk kembali memeriksa transaksi tanpa menyimpan data.

Pilihan `Ya` atau `Tidak` tetap menyimpan transaksi satu kali melalui proses checkout yang sama. Perbedaannya hanya pada tindakan setelah transaksi berhasil: pilihan `Ya` membuka halaman/layout cetak nota, sedangkan pilihan `Tidak` menampilkan halaman transaksi selesai tanpa membuka dialog cetak.

### Langkah 5 — Selesai dan cetak

Setelah transaksi berhasil:

- Tampilkan nomor nota.
- Tampilkan total dan kembalian.
- Tampilkan tombol `Cetak Nota`.
- Tampilkan tombol `Transaksi Baru`.

Jangan langsung mengembalikan pengguna ke beranda agar kasir dapat memastikan transaksi dan nota telah berhasil.

Jika pencetakan gagal atau dibatalkan dari dialog printer, transaksi tetap berstatus berhasil dan dapat dicetak ulang dari halaman selesai atau riwayat. Jangan mengulangi request checkout hanya untuk mencoba mencetak kembali.

## 6. Rancangan Database Ringkas

### users

Menyimpan akun admin dan kasir.

Kolom penting:

- `name`
- `username`
- `email`
- `password`
- `role`
- `is_active`

### products

Menyimpan data master produk tanpa harga jual.

Kolom penting:

- `code`
- `name`
- `category`
- `unit`
- `stock`
- `description`
- `image_path`
- `is_active`

### sales

Menyimpan kepala transaksi.

Kolom penting:

- `invoice_number`
- `cashier_id`
- `transaction_at`
- `customer_name`
- `total_amount`
- `paid_amount`
- `change_amount`
- `payment_method`
- `notes`

### sale_items

Menyimpan rincian barang dalam transaksi.

Kolom penting:

- `sale_id`
- `product_id`
- `quantity`
- `price`
- `subtotal`
- `notes`

Rumus:

```text
subtotal = quantity × price
```

### stock_movements

Menyimpan riwayat perubahan stok.

Kolom penting:

- `product_id`
- `user_id`
- `sale_id`
- `type`
- `quantity`
- `stock_before`
- `stock_after`
- `notes`

## 7. Aturan Bisnis Penting

1. Harga wajib diisi saat barang ditambahkan ke keranjang.
2. Harga dan jumlah harus lebih besar dari nol.
3. `sale_items.subtotal` dihitung di server, bukan dipercaya dari nilai browser.
4. `sales.total_amount` dihitung dari jumlah seluruh subtotal rincian.
5. `change_amount` dihitung oleh server.
6. Untuk pembayaran tunai, `paid_amount` tidak boleh kurang dari `total_amount`.
7. Untuk transfer atau QRIS, `paid_amount` dapat otomatis disamakan dengan `total_amount`.
8. Stok tidak boleh menjadi negatif.
9. Pembuatan penjualan, rincian, pengurangan stok, dan pencatatan pergerakan stok harus berada dalam satu database transaction.
10. Nomor invoice dibuat oleh server dan harus unik.
11. Produk sebaiknya dinonaktifkan atau di-soft-delete, bukan dihapus permanen, jika sudah pernah digunakan dalam transaksi.
12. Penjualan yang telah selesai sebaiknya tidak dihapus langsung. Koreksi dapat dibuat melalui fitur pembatalan atau retur pada pengembangan berikutnya.

## 8. Rekomendasi Arsitektur Laravel

### Backend

- Laravel 13.
- MySQL atau PostgreSQL.
- Form Request untuk validasi.
- Service class untuk proses checkout dan pengelolaan stok.
- Policy atau middleware untuk pembatasan akses admin dan kasir.
- Database transaction pada proses checkout.

Contoh pembagian service:

```text
app/Services/SaleService.php
app/Services/StockService.php
app/Services/ReceiptService.php
```

### Frontend

Untuk menjaga implementasi tetap sederhana:

- Blade.
- Bootstrap 5 untuk layout responsif.
- Form tambah dan ubah data ditampilkan menggunakan Bootstrap Modal pada halaman daftar; jangan membuat view atau halaman khusus untuk form tambah/ubah.
- Pengiriman form, pengambilan data untuk modal ubah, hapus data, serta tambah dan hapus item keranjang menggunakan jQuery Ajax tanpa reload halaman penuh; jangan gunakan Fetch API, Axios, atau framework frontend lain untuk proses tersebut.
- Jika data ditampilkan dalam bentuk tabel, gunakan DataTables dengan konfigurasi responsif, pencarian, pengurutan, pagination, loading state, dan empty state yang konsisten.
- Endpoint keranjang tetap melakukan validasi di server dan mengembalikan JSON berisi status, isi/ringkasan keranjang, total item, total harga, serta pesan kesalahan bila ada.
- Ikon sederhana seperti Bootstrap Icons.
- Hindari komponen frontend yang terlalu kompleks pada versi awal.

### Standar implementasi dan penulisan kode

#### Struktur form dan interaksi

- Satu halaman daftar memiliki modal tambah dan modal ubah, atau satu modal yang dapat digunakan untuk kedua mode.
- Isi modal ubah diambil melalui jQuery Ajax berdasarkan ID data; form harus dikosongkan dan status validasinya direset setiap kali modal ditutup.
- Tombol simpan dan hapus memiliki loading state serta dinonaktifkan selama request berlangsung untuk mencegah request ganda.
- Setiap tindakan hapus wajib menampilkan Bootstrap Modal konfirmasi yang menyebutkan nama atau identitas data yang akan dihapus.
- Modal hapus menyediakan tombol `Batal` dan `Ya, Hapus`. Request hapus melalui jQuery Ajax hanya dikirim setelah pengguna memilih `Ya, Hapus`; menutup modal atau memilih `Batal` tidak mengubah data.
- Saat request hapus berlangsung, tombol konfirmasi dinonaktifkan dan menampilkan loading state. Modal ditutup serta DataTables diperbarui hanya setelah server menyatakan penghapusan berhasil.
- Kesalahan validasi HTTP `422` ditampilkan di dekat field terkait. Kesalahan lain ditampilkan menggunakan notifikasi yang konsisten.
- Setelah request berhasil, perbarui atau reload data DataTables melalui API DataTables tanpa reload halaman penuh.
- Sertakan CSRF token pada seluruh request yang mengubah data.

#### Validasi, controller, dan service layer

- Seluruh validasi input HTTP menggunakan class Form Request khusus di `app/Http/Requests`; jangan menulis validasi langsung di controller.
- Controller hanya menangani request, otorisasi, memanggil service, dan membentuk response. Controller tidak boleh berisi query atau logika bisnis.
- Logika bisnis, perhitungan, dan orkestrasi penyimpanan data ditempatkan di service pada `app/Services`.
- Operasi yang mengubah beberapa tabel harus menggunakan `DB::transaction()` di service agar seluruh perubahan berhasil atau seluruhnya dibatalkan.
- Method publik controller dan service yang berpotensi gagal harus memiliki penanganan `try-catch`. Tangkap `Throwable`, catat error dengan konteks yang aman, lalu kembalikan response gagal atau lemparkan kembali exception sesuai lapisannya; exception tidak boleh diabaikan.
- Hindari pencatatan exception yang sama berulang kali. Service mencatat error ketika memiliki konteks bisnis tambahan lalu melempar ulang, sedangkan controller membentuk response JSON seragam tanpa membuat log duplikat.
- Log error minimal memuat nama operasi, ID pengguna bila tersedia, ID data yang relevan, pesan exception, dan stack trace. Jangan memasukkan password, token, data pembayaran sensitif, atau isi request rahasia ke log.

#### Template response JSON

- Buat satu class khusus, misalnya `app/Support/ApiResponse.php`, sebagai pembentuk seluruh response JSON controller.
- Controller tidak boleh menyusun struktur JSON sendiri-sendiri dengan `response()->json()` kecuali di dalam class response tersebut.
- Gunakan struktur response yang konsisten:

```json
{
  "success": true,
  "message": "Data berhasil disimpan.",
  "data": {},
  "errors": null,
  "meta": null
}
```

- Sediakan method terpusat seperti `success()`, `created()`, `validationError()`, `notFound()`, dan `error()` dengan HTTP status code yang sesuai.
- Sesuaikan kegagalan Form Request agar response validasi AJAX juga melewati format `ApiResponse`, termasuk detail error per field.
- Response DataTables boleh memiliki adapter khusus agar memenuhi format yang dibutuhkan DataTables, tetapi error dan metadata aplikasi tetap mengikuti standar yang sama.

#### Query dan performa database

- Dilarang menjalankan query di dalam loop. Gunakan eager loading `with()`, aggregate eager loading, join, atau pengambilan data sekaligus sesuai kebutuhan.
- Cegah N+1 dengan memuat relasi yang benar-benar dipakai dan periksa jumlah query pada halaman daftar, detail, laporan, serta cetak nota.
- Setiap query pengambilan data menggunakan `select()` untuk mengambil kolom yang benar-benar diperlukan. Sertakan primary key dan foreign key yang dibutuhkan agar eager loading tetap bekerja.
- Gunakan `exists()`, `value()`, `pluck()`, `count()`, `sum()`, atau query agregat ketika tidak memerlukan model lengkap.
- Jangan mengambil data yang kemudian tidak digunakan. Daftar berukuran besar wajib menggunakan pagination atau server-side DataTables.
- Gunakan index database untuk kolom yang sering dicari, difilter, diurutkan, atau digunakan sebagai foreign key, lalu verifikasi query penting dengan query log atau `EXPLAIN` bila diperlukan.
- Jangan memanggil relasi Eloquent secara implisit dari Blade; seluruh data harus disiapkan oleh controller melalui service untuk mencegah query tersembunyi.

#### Kerapian dan maintainability

- Ikuti PSR-12 dan format proyek menggunakan Laravel Pint sebelum perubahan dianggap selesai.
- Gunakan penamaan class, method, variabel, route, dan file yang konsisten serta menjelaskan maksud bisnis.
- Satu method mengerjakan satu tanggung jawab. Pecah method yang panjang atau memiliki terlalu banyak percabangan menjadi method privat atau service yang jelas.
- Gunakan type declaration, return type, early return, dan dependency injection jika sesuai; hindari nested condition yang tidak perlu dan magic number/string.
- Hapus import, variabel, query, method, class, route, view, aset, dan kode komentar yang tidak digunakan. Jangan menyimpan fungsi untuk kemungkinan kebutuhan di masa depan.
- Hindari duplikasi kode; ekstrak hanya perilaku yang benar-benar digunakan bersama dan jangan membuat abstraksi yang tidak diperlukan.
- Komentar menjelaskan alasan atau aturan bisnis, bukan mengulang isi kode. Kode harus rapi, terformat, dan mudah ditinjau.
- Setiap fitur dianggap selesai setelah lolos test terkait, pemeriksaan format, dan pemeriksaan manual bahwa tidak ada error JavaScript maupun query berlebih.

### PWA

Setelah MVP stabil, tambahkan:

- Web app manifest.
- Ikon aplikasi.
- Mode standalone.
- Service worker untuk cache aset dasar.
- Halaman offline yang menjelaskan bahwa transaksi membutuhkan koneksi.

Pada fase awal, jangan langsung membuat transaksi offline karena sinkronisasi transaksi dan stok akan menambah kompleksitas serta risiko data ganda.

## 9. Struktur Halaman Minimal

### Login

- Username atau email.
- Password.
- Tombol masuk.

### Beranda

- Ringkasan hari ini.
- Stok menipis.
- Tombol mulai penjualan.

### Daftar produk

- Pencarian.
- Filter aktif/nonaktif untuk admin.
- Tombol tambah produk.
- Kartu produk pada ponsel.
- Tabel pada desktop.
- Gambar produk berbentuk persegi dengan fallback apabila gambar belum diunggah.

### Form produk (Bootstrap Modal)

Kolom:

- Kode.
- Nama.
- Kategori.
- Satuan.
- Stok awal hanya saat pembuatan produk.
- Deskripsi.
- Gambar produk JPG, PNG, atau WebP.
- Status aktif.

Perubahan stok setelah produk dibuat dilakukan melalui menu stok agar riwayatnya tercatat.

Form tambah dan ubah produk dibuka dari halaman daftar produk melalui modal dan dikirim menggunakan jQuery Ajax; tidak dibuat view tambah atau ubah yang terpisah.

### Penyesuaian stok

- Pilih produk.
- Pilih jenis: barang masuk atau penyesuaian.
- Masukkan jumlah.
- Masukkan catatan.
- Simpan.

Form penyesuaian stok juga menggunakan Bootstrap Modal dan jQuery Ajax dari halaman daftar/riwayat stok.

### Penjualan

- Pencarian produk di bagian atas.
- Daftar produk.
- Tombol keranjang yang selalu terlihat.
- Total keranjang ditampilkan pada tombol.

### Riwayat penjualan

- Nomor invoice.
- Waktu transaksi.
- Kasir.
- Total.
- Metode pembayaran.
- Tombol detail.

### Detail penjualan

- Informasi transaksi.
- Rincian barang.
- Total pembayaran.
- Tombol cetak ulang.

## 10. Roadmap Pengembangan

## Fase 0 — Persiapan dan keputusan teknis

Target:

- Menentukan database.
- Menentukan format nomor invoice.
- Menentukan printer dan cara koneksinya.
- Membuat wireframe sederhana untuk layar ponsel.
- Menyiapkan repository dan environment development.

Hasil:

- Struktur proyek Laravel.
- Migrasi database.
- Daftar aturan bisnis.
- Desain halaman inti.

## Fase 1 — Fondasi aplikasi

Fitur:

- Login dan logout.
- Role admin dan kasir.
- Pengelolaan pengguna oleh admin.
- Middleware role.
- Layout responsif.
- Navigasi bawah untuk ponsel.

Kriteria selesai:

- Admin dan kasir melihat menu yang sesuai.
- Tampilan dapat digunakan pada ponsel dan PC.

## Fase 2 — Produk dan stok

Fitur:

- Tambah, ubah, lihat, dan nonaktifkan produk.
- Pencarian produk.
- Stok awal.
- Barang masuk.
- Penyesuaian stok.
- Riwayat pergerakan stok.
- Peringatan stok menipis.

Kriteria selesai:

- Setiap perubahan stok tercatat.
- Stok produk sesuai dengan riwayat pergerakan.

## Fase 3 — POS dan checkout

Fitur:

- Memilih produk.
- Memasukkan jumlah dan harga.
- Keranjang.
- Tambah dan hapus item keranjang melalui jQuery Ajax tanpa reload halaman.
- Ubah item; jika perubahan diterapkan sebagai hapus lalu tambah, kedua proses tetap menggunakan jQuery Ajax.
- Pembayaran tunai, transfer, dan QRIS.
- Menghitung total dan kembalian.
- Menyimpan transaksi.
- Mengurangi stok.
- Membuat nomor invoice.

Kriteria selesai:

- Transaksi tersimpan secara atomik.
- Stok tidak dapat menjadi negatif.
- Rincian penjualan sesuai dengan keranjang.
- Penambahan dan penghapusan item memperbarui daftar keranjang, jumlah item, serta total secara langsung melalui respons JSON jQuery Ajax.
- Kegagalan request atau validasi ditampilkan kepada pengguna dan tidak meninggalkan tampilan keranjang yang berbeda dari data server.
- Tombol checkout menampilkan modal pilihan cetak dan transaksi tidak tersimpan sebelum pengguna memilih tindakan.
- Pilihan cetak maupun tanpa cetak hanya menghasilkan satu transaksi dan satu pengurangan stok.

## Fase 4 — Nota thermal BP ECO58D 58 mm

Fitur:

- Template nota khusus kertas 58 mm untuk printer BP ECO58D.
- Cetak dari halaman transaksi selesai.
- Cetak ulang dari riwayat.
- Cetak dan cetak ulang dapat dilakukan oleh admin; kasir mengikuti batasan transaksi yang boleh diaksesnya.
- Pengaturan margin dan ukuran teks.
- Pengujian Android, iOS, dan PC.

Urutan implementasi yang disarankan:

1. Cetak HTML melalui dialog browser untuk pengujian awal.
2. Uji dialog cetak dan driver BP ECO58D pada setiap perangkat target.
3. Integrasikan ESC/POS, SDK, atau aplikasi perantara yang kompatibel dengan varian koneksi printer jika cetak browser tidak stabil.
4. Tambahkan aplikasi native wrapper hanya jika browser/PWA tidak cukup stabil.

Kriteria selesai:

- Nama barang, jumlah, harga, subtotal, total, pembayaran, dan kembalian tercetak jelas.
- Nota dapat dicetak ulang tanpa membuat transaksi baru.
- Layout tidak terpotong pada BP ECO58D dan tidak memakai aturan lebar 80 mm.
- Pilihan `Ya, Selesaikan & Cetak` membuka nota transaksi yang baru berhasil disimpan, sedangkan pilihan tanpa cetak tidak memunculkan dialog printer.
- Kegagalan atau pembatalan pencetakan tidak mengirim ulang checkout dan tidak membuat transaksi ganda.

## Fase 5 — Riwayat dan laporan sederhana

Fitur:

- Riwayat transaksi.
- Filter tanggal.
- Detail transaksi.
- Rekap penjualan harian dan bulanan.
- Produk paling sering terjual.
- Ekspor CSV, opsional.

Kriteria selesai:

- Admin dapat memeriksa hasil penjualan tanpa laporan yang terlalu kompleks.

## Fase 6 — PWA dan penyempurnaan pengalaman pengguna

Fitur:

- Instal aplikasi ke home screen.
- Splash screen.
- Cache aset statis.
- Indikator koneksi.
- Loading state pada tombol.
- Pencegahan tombol checkout ditekan dua kali.
- Konfirmasi hanya untuk tindakan yang berisiko.

Kriteria selesai:

- Penggunaan dari ponsel terasa seperti aplikasi.
- Pengguna tidak mudah membuat transaksi ganda.

## Fase 7 — Keamanan, pengujian, dan operasional

Fitur:

- Feature test untuk checkout.
- Test perhitungan total dan kembalian.
- Test stok bersamaan.
- Backup database terjadwal.
- Audit log untuk tindakan penting, opsional.
- Monitoring error.
- Pemeriksaan N+1 dan batas jumlah query pada alur utama.
- Laravel Pint dan pemeriksaan kode yang tidak digunakan.

Kriteria selesai:

- Restore backup pernah diuji.
- Proses checkout memiliki pengujian otomatis.
- CRUD modal, response JSON, validasi Form Request, dan penanganan error memiliki feature test.
- Modal konfirmasi hapus dan kedua pilihan checkout (cetak/tanpa cetak) memiliki feature test atau browser test sesuai lapisannya.
- Tidak ditemukan N+1, query di dalam loop, atau kode yang tidak digunakan pada pemeriksaan akhir.

## 11. Prioritas MVP

Fitur yang wajib pada peluncuran pertama:

- Login.
- Admin dan kasir.
- Produk.
- Stok masuk dan penyesuaian.
- Keranjang.
- Harga saat transaksi.
- Checkout.
- Riwayat transaksi.
- Cetak dan cetak ulang nota.

Fitur yang dapat ditunda:

- Transaksi offline.
- Multi-cabang.
- Pelanggan lengkap.
- Supplier.
- Pembelian.
- Retur.
- Diskon.
- Harga default produk.
- Grafik kompleks.
- Integrasi marketplace.

## 12. Format Nota BP ECO58D 58 mm

Contoh:

```text
               [LOGO]
             TOKO ANGGREK
       Jl. Contoh No. 10, Kota
          HP: 0812-3456-7890
--------------------------------
No    : TRX-20260716-0001
Kasir : Bana
Waktu : 16/07/2026 14:30
--------------------------------
Anggrek Bulan
1 x 150.000              150.000

Dendrobium Ungu
2 x 85.000               170.000
--------------------------------
TOTAL                     320.000
TUNAI                     350.000
KEMBALI                    30.000
--------------------------------
          Terima kasih
```

Header nota menampilkan logo, nama toko, alamat, dan nomor HP secara rata tengah. Gunakan font monospace dan hindari logo besar agar pencetakan cepat serta tidak menghabiskan kertas.

Aturan layout cetak:

- Gunakan `@page { size: 58mm auto; margin: 0; }` pada stylesheet cetak.
- Lebar konten maksimal `58mm` dengan padding kecil; area cetak efektif harus dikalibrasi melalui hasil test print BP ECO58D agar teks tidak terpotong.
- Logo harus monokrom atau hitam-putih, memiliki kontras tinggi, dan dibatasi sekitar `24–30mm` agar tetap tajam serta tidak memperlambat pencetakan.
- Nama toko, alamat, dan nomor HP ditempatkan di bawah logo. Alamat panjang harus membungkus secara otomatis tanpa melewati lebar nota.
- Sediakan fallback berupa nama toko apabila gambar logo gagal dimuat atau tidak dapat dicetak.
- Sembunyikan navigasi, tombol, dan elemen admin lain menggunakan `@media print`.
- Gunakan font nota yang ringkas dan terbaca; ukuran font nota juga maksimal `0.8rem`.
- Nama barang yang panjang harus membungkus ke baris berikutnya, sedangkan nominal dibuat rata kanan.

## 13. Rekomendasi Strategi Printer

### Pilihan utama

Gunakan printer thermal **BP ECO58D** dengan kertas **58 mm**. Pastikan varian koneksi unit yang digunakan sesuai dengan perangkat operasional toko. Metode koneksi dan pencetakan ditetapkan setelah test print langsung pada unit tersebut.

Dialog cetak HTML browser menjadi tahap awal. Integrasi ESC/POS, SDK, atau aplikasi perantara hanya ditambahkan jika hasil cetak browser tidak stabil dan dukungannya telah dipastikan untuk varian BP ECO58D yang digunakan.

### Pengujian sebelum membeli banyak unit

1. Beli atau pinjam satu unit terlebih dahulu.
2. Uji dari Android, iPhone/iPad, dan Windows.
3. Uji teks panjang dan banyak rincian produk.
4. Uji cetak ulang.
5. Uji setelah jaringan atau printer sempat mati.
6. Pastikan varian koneksi pada produk benar-benar sesuai; satu model sering memiliki beberapa varian interface.

## 14. Definisi Selesai untuk MVP

MVP dianggap siap digunakan ketika:

- Kasir dapat menyelesaikan transaksi dalam beberapa langkah.
- Harga dapat berbeda pada setiap rincian produk.
- Total dihitung oleh server.
- Stok berkurang dengan benar.
- Transaksi tidak ganda saat tombol ditekan berulang.
- Nota dapat dicetak dan dicetak ulang.
- Nota 58 mm tidak terpotong saat diuji langsung pada BP ECO58D.
- Tampilan nyaman pada ponsel berukuran kecil.
- Font antarmuka utama menggunakan ukuran `0.9rem`, sedangkan nota tetap ringkas agar sesuai kertas 58 mm.
- Admin dapat mencari produk dan transaksi dengan cepat.
- Backup database telah tersedia.
- Seluruh CRUD menggunakan modal, jQuery Ajax, Form Request, service layer, dan response JSON terpusat sesuai standar roadmap.
- Kode lolos Laravel Pint, test otomatis, dan pemeriksaan query sebelum rilis.
