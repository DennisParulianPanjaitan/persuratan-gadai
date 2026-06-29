# Notepad Skripsi - Sistem Informasi Persuratan Gadai

## 1. Judul Skripsi
Sistem Informasi Persuratan Gadai Berbasis Web dengan Integrasi QR Code pada UD Gerlian Jaya.

## 2. Latar Belakang Singkat
Sebelum sistem ini dibuat, proses gadai dan pencatatan surat masih dilakukan secara manual. Hal ini membuat data mudah tercecer, proses pencarian lambat, dan pembuatan surat tidak efisien. Karena itu dibangun sistem berbasis web agar data lebih terpusat, lebih cepat diakses, dan lebih mudah diverifikasi.

## 3. Tujuan Sistem
- Mengubah pencatatan manual menjadi sistem digital.
- Menyimpan data pelanggan, barang, dan transaksi dalam database.
- Mempermudah admin dalam verifikasi pengajuan gadai.
- Mempermudah pelanggan melihat status transaksi dan surat gadai.
- Memakai QR code sebagai identitas cepat untuk membuka data dari database.

## 4. Aktor Sistem
### Admin
Admin adalah pengguna yang mengelola seluruh data inti sistem.
Tugas admin:
- mengelola jenis barang,
- mengelola pelanggan,
- mengelola barang,
- memverifikasi barang pengajuan gadai,
- mengelola transaksi gadai,
- mengelola perpanjangan,
- mengelola penebusan,
- mengelola penjualan barang,
- mencetak atau melihat surat,
- melihat laporan,
- mengatur profil.

### Pelanggan
Pelanggan adalah pihak yang mengajukan gadai dan melihat status transaksinya.
Tugas / akses pelanggan:
- registrasi dan login,
- melihat barang atau transaksi yang aktif,
- melihat surat gadai,
- mengajukan perpanjangan,
- melihat riwayat transaksi.

## 5. Stack Teknologi
- Laravel
- PHP
- Blade
- Eloquent ORM
- MySQL / database relasional
- Custom CSS dan JavaScript
- Simple QrCode package

## 6. Struktur Database Utama
Database inti yang digunakan terdiri dari 7 tabel utama.

### 6.1 users
Tabel untuk akun admin atau petugas.
Kolom penting:
- id_user
- nama
- email
- password
- role
- status_aktif
- created_at
- updated_at

### 6.2 m_pelanggan
Tabel untuk data pelanggan.
Kolom penting:
- id_pelanggan
- nama
- no_hp
- alamat
- email
- keterangan
- created_at
- updated_at

### 6.3 m_jenis_barang
Tabel untuk kategori atau jenis barang.
Kolom penting:
- id_jenis_barang
- nama_jenis
- deskripsi
- created_at
- updated_at

### 6.4 m_barang
Tabel utama untuk barang yang digadaikan atau dilelang.
Kolom penting:
- id_barang
- id_jenis_barang
- nama_barang
- harga_beli
- kondisi
- berat
- foto_barang
- status_verifikasi
- keterangan
- created_at
- updated_at

Status verifikasi barang:
- pending
- terverifikasi
- ditolak

### 6.5 t_transaksi_gadai
Tabel transaksi gadai utama.
Kolom penting:
- id_transaksi_gadai
- id_pelanggan
- id_barang
- id_user
- kode_transaksi
- uang_pinjaman
- tanggal_gadai
- tanggal_jatuh_tempo
- status
- tanggal_ditebus
- total_ditebus
- qr_code
- jumlah_perpanjangan
- created_at
- updated_at

Status transaksi gadai:
- aktif
- ditebus
- dijual

### 6.6 t_transaksi_perpanjangan
Tabel untuk histori perpanjangan gadai.
Kolom penting:
- id_perpanjangan
- id_transaksi_gadai
- id_user
- tanggal_perpanjangan
- perpanjangan_ke
- tambahan_bulan
- jatuh_tempo_sebelum
- jatuh_tempo_sesudah
- biaya_perpanjangan
- catatan
- created_at
- updated_at

### 6.7 t_transaksi_penjualan
Tabel untuk data penjualan barang.
Kolom penting:
- id_penjualan
- id_transaksi_gadai
- id_barang
- id_user
- tanggal_jual
- harga_jual
- biaya_lain
- laba_rugi
- catatan
- created_at
- updated_at

## 7. Relasi Antar Tabel
- m_barang berelasi ke m_jenis_barang.
- t_transaksi_gadai berelasi ke m_pelanggan, m_barang, dan users.
- t_transaksi_perpanjangan berelasi ke t_transaksi_gadai dan users.
- t_transaksi_penjualan berelasi ke t_transaksi_gadai, m_barang, dan users.

## 8. Alur Sistem Umum
1. Pelanggan registrasi atau login.
2. Pelanggan mengajukan barang atau transaksi gadai.
3. Admin menerima data dan memeriksa kelengkapan.
4. Barang diverifikasi menjadi pending, terverifikasi, atau ditolak.
5. Jika disetujui, transaksi gadai dibuat.
6. Sistem menyimpan data surat dan QR code.
7. Pelanggan dapat melihat status dan surat.
8. Jika diperlukan, pelanggan bisa mengajukan perpanjangan.
9. Jika dilunasi, transaksi berubah menjadi ditebus.
10. Jika tidak ditebus, barang dapat masuk penjualan.

## 9. Alur QR Code
QR code dipakai sebagai identitas cepat untuk membuka data dari database.
QR code bukan penentu asli atau palsu, tetapi hanya pointer untuk menampilkan data.

### Untuk Barang
Saat QR code barang dipindai oleh admin melalui HP, sistem menampilkan:
- nama barang,
- berat,
- foto barang,
- status verifikasi,
- jenis barang,
- harga beli,
- keterangan.

### Untuk Pengajuan / Gadai
Saat QR code pengajuan atau surat gadai dipindai, sistem menampilkan:
- data transaksi,
- data pelanggan,
- data barang,
- status transaksi,
- informasi jatuh tempo,
- data surat.

### Fungsi QR Code
- mempercepat pencarian data,
- mengurangi input manual,
- membantu admin melakukan verifikasi dari HP,
- memudahkan akses ke detail barang atau transaksi.

## 10. Halaman / Modul yang Sudah Disiapkan
- Landing page
- Dashboard admin
- Dashboard pelanggan
- Master jenis barang
- Master pelanggan
- Master barang
- Pengajuan gadai
- Penyerahan barang
- Transaksi gadai
- Transaksi perpanjangan
- Transaksi penjualan
- Laporan
- Profil admin

## 11. Komponen UI
Beberapa komponen Blade yang dibuat agar tampilan konsisten:
- card
- table
- page-header
- search
- button
- modal

## 12. Konfigurasi Admin Panel
- Route admin dibuat dalam grup prefix admin.
- Controller admin dipisah per modul.
- Tampilan memakai layout admin khusus.
- Styling utama memakai public/assets/css/layout.css.
- Interaksi seperti sidebar dan notifikasi memakai public/assets/js/layout.js.

## 13. QR Package
Package QR yang digunakan:
- simplesoftwareio/simple-qrcode

## 14. Catatan Penting Teknis
- Model tabel utama memakai akhiran Models.
- Barang memakai primary key id_barang.
- Status barang dipakai dalam huruf kecil agar konsisten dengan database.
- QR code untuk barang sebaiknya mengarah ke halaman detail yang membaca data dari database, bukan menyimpan semua data mentah di QR.
- Untuk kebutuhan HP, link QR harus bisa diakses dari jaringan yang sama atau dari hosting yang aktif.

## 15. Ringkasan Besar Alur Skripsi
Sistem ini dibangun untuk mengelola persuratan gadai secara digital. Admin mengolah master data, memverifikasi barang, membuat transaksi gadai, mengatur perpanjangan dan penebusan, lalu menghasilkan surat dan QR code. Pelanggan dapat melihat status transaksi dan riwayatnya. QR code berfungsi sebagai penghubung cepat untuk membuka data yang tersimpan di database.

## 16. Kalau Mau Lanjutkan Prompt dari Akun Lain
Mulai dari poin ini:
1. Database inti dan relasi tabel.
2. Alur QR code barang dan surat gadai.
3. Halaman admin yang sudah tersedia.
4. Penjelasan fitur pengajuan gadai, perpanjangan, penebusan, dan penjualan.
5. Detail implementasi Laravel, Blade, dan migration.
