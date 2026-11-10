# Garage88 — Sistem Informasi Pelayanan Repaint Berbasis Web

Kode aplikasi untuk laporan KP "Perancangan Sistem Informasi Pelayanan Repaint Berbasis Web pada Garage88".
Stack sesuai Bab IV: **Laravel 12, PHP 8.3, MySQL, Bootstrap 5, XAMPP**.

Folder ini berisi **lapisan aplikasi** (migrasi, model, controller, route, view, seeder, test).
Tinggal ditimpa ke proyek Laravel 12 yang baru.

## Cara Pasang (XAMPP)

1. Nyalakan **Apache** dan **MySQL** di XAMPP. Buat database kosong bernama `garage88` lewat phpMyAdmin.
2. Buat proyek Laravel 12 baru:
   ```bash
   composer create-project laravel/laravel:^12.0 garage88
   ```
3. Salin isi folder ini ke dalam proyek `garage88` tersebut, **timpa file yang sama**
   (`bootstrap/app.php`, `routes/web.php`, `database/`, `app/`, `resources/views/`, `tests/`).
4. Edit `.env`:
   ```env
   APP_NAME=Garage88
   APP_LOCALE=id
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=garage88
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Jalankan:
   ```bash
   php artisan migrate --seed
   php artisan serve
   ```
   Buka http://127.0.0.1:8000

## Akun Demo (password: `password`)

| Role      | Email                  |
|-----------|------------------------|
| Admin     | admin@garage88.test    |
| Teknisi   | teknisi@garage88.test  |
| Pelanggan | budi@garage88.test     |

Pelanggan baru bisa mendaftar sendiri lewat menu **Daftar**.

## Fitur per Aktor (Use Case Bab III)

- **Pelanggan**: registrasi, login, ajukan layanan (pilih layanan → data motor → simpan → *Menunggu Konfirmasi*), lihat status (timeline progres), riwayat.
- **Admin**: login, tambah layanan, verifikasi DP/pelunasan, konfirmasi pesanan setelah pembayaran terverifikasi, selesaikan pesanan, balas chat, dan cetak laporan.
- **Teknisi**: login, lihat daftar pekerjaan, dan memperbarui tahapan pengerjaan selain konfirmasi dan penyelesaian.

Akses dibatasi per role lewat middleware `role` (`app/Http/Middleware/RoleMiddleware.php`).

## Pembayaran dan Chat

Pelanggan dapat mengirim DP atau pelunasan melalui transfer (dengan bukti) atau tunai. Admin memverifikasi setiap transaksi dan dapat mengonfirmasi pesanan setelah ada pembayaran terverifikasi. Chat pelanggan dan admin tersimpan di aplikasi.

## Struktur Database

Sesuai ERD laporan: `users`, `pelanggan`, `motor`, `layanan`, `pesanan`, `detail_pesanan`, `progress`, `payments`, `chat_messages`.
Harga layanan disalin ke `detail_pesanan` saat pesanan dibuat, jadi perubahan harga tidak mengubah pesanan lama.

Tahapan status: Menunggu Konfirmasi → Dikonfirmasi → Pembongkaran → Pengampelasan → Pengecatan → Finishing → Selesai (atau Dibatalkan).

## Pengujian Black Box (Bab IV)

```bash
php artisan test
```

`tests/Feature/Garage88Test.php` mencakup: login data valid/salah, registrasi dan pengajuan layanan pelanggan, pembayaran,
chat, update status (valid/tidak valid), pembatasan akses per role, kepemilikan pesanan, perhitungan laporan, dan proteksi hapus layanan.
Test memakai database SQLite in-memory bawaan `phpunit.xml`, jadi tidak menyentuh data MySQL Anda.

## Catatan

- Bootstrap dan ikon dimuat dari CDN, jadi perlu koneksi internet saat membuka halaman.
- Kode belum dijalankan di lingkungan pembuatnya (tidak ada akses ke Packagist), jadi jalankan `php artisan test` lebih dulu setelah pemasangan.
