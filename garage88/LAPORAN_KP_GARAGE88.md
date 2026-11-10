# Laporan KP — Garage88

## 1. Latar Belakang
Garage88 adalah sistem informasi pelayanan repaint motor berbasis web yang dibangun untuk memudahkan proses pencatatan, pemantauan, dan laporan pekerjaan repaint. Sistem ini dirancang agar pelanggan dapat mengajukan pengerjaan secara online, admin dapat mengelola data pelanggan dan motor, serta teknisi dapat memantau dan memperbarui status pekerjaan.

## 2. Tujuan
- Memudahkan pelanggan dalam mengajukan layanan repaint.
- Mengelola data pelanggan, motor, dan layanan dengan lebih rapi.
- Menyediakan riwayat pengerjaan dan progress pekerjaan secara real-time.
- Mempermudah admin dalam membuat laporan dan melakukan monitoring.
- Meningkatkan transparansi antara pelanggan, teknisi, dan admin.

## 3. Fitur Utama
### A. Login dan Hak Akses
- Login untuk admin, teknisi, dan pelanggan.
- Akses halaman dibatasi berdasarkan role.

### B. Manajemen Pelanggan
- Tambah, lihat, ubah, dan hapus data pelanggan.
- Data meliputi nama, email, nomor HP, dan alamat.

### C. Manajemen Motor
- Data motor mencakup plat nomor, merk, tipe, tahun, dan warna.
- Satu pelanggan dapat memiliki banyak motor.

### D. Pencatatan Layanan Repaint
- Tanggal kedatangan.
- Estimasi selesai.
- Bagian yang direpaint.
- Warna dan jenis cat.
- Catatan pengerjaan.
- Status pekerjaan.

### E. Riwayat Pekerjaan
- Riwayat pesanan pelanggan.
- Detail per motor dan per pekerjaan.
- Timeline progress pengerjaan.

### F. Pencarian
- Pencarian pelanggan dan motor berdasarkan nama, plat, merk, tipe, atau status.
- Pencarian riwayat pesanan pelanggan.

### G. Laporan
- Laporan daftar pesanan dengan filter periode.
- Filter status dan pencarian nama/plat.
- Export ke CSV.
- Cetak ke PDF melalui fitur browser.

### H. Dashboard
- Ringkasan jumlah pelanggan, motor, pesanan aktif, dan total pesanan.
- Status pekerjaan terbaru untuk admin dan pelanggan.

## 4. Role Pengguna
### Admin
- Mengelola pelanggan, motor, layanan, dan pesanan.
- Mengakses laporan.

### Teknisi
- Melihat daftar pekerjaan.
- Mengubah status pengerjaan dan menambahkan keterangan.

### Pelanggan
- Mendaftar dan login.
- Mengajukan layanan repaint.
- Melihat status dan riwayat pengerjaan.

## 5. Teknologi yang Digunakan
- PHP 8.3
- Laravel 12
- MySQL
- Bootstrap 5
- Blade Template
- PHPUnit

## 6. Struktur Aplikasi
- app/Http/Controllers : controller logika aplikasi
- app/Models : model data utama
- database/migrations : struktur tabel
- resources/views : antarmuka pengguna
- routes/web.php : rute aplikasi
- tests/Feature : pengujian fungsional

## 7. Cara Menjalankan
1. Pastikan PHP, Composer, dan database MySQL tersedia.
2. Masuk ke folder project.
3. Jalankan:
   php artisan migrate --seed
   php artisan serve
4. Buka browser ke http://127.0.0.1:8000

## 8. Akun Demo
- Admin : admin@garage88.test / password
- Teknisi : teknisi@garage88.test / password
- Pelanggan : budi@garage88.test / password

## 9. Kesimpulan
Garage88 telah berhasil dibuat sebagai aplikasi pelayanan repaint yang dapat digunakan untuk proses pengajuan, pengerjaan, dan pelaporan pekerjaan. Aplikasi ini telah disesuaikan dengan kebutuhan penggunaan nyata di workshop motor serta siap dijadikan dasar untuk presentasi dan pengembangan lebih lanjut.
