# Posyandu Remaja

## Deskripsi
Posyandu Remaja adalah sebuah sistem manajemen berbasis web yang dirancang untuk membantu pengelolaan data dan pemantauan kesehatan remaja di posyandu. Aplikasi ini bertujuan untuk mempermudah kader dan petugas kesehatan dalam mencatat, melaporkan, dan menganalisis perkembangan kesehatan remaja secara digital.

Sistem ini menggantikan pencatatan manual yang rentan hilang atau rusak, serta menyediakan fitur analisis data untuk mendeteksi risiko kesehatan lebih dini menggunakan teknologi pembelajaran mesin.

## Fitur Utama

- **Manajemen Data Remaja**: Pencatatan biodata lengkap remaja yang terdaftar di posyandu.
- **Pencatatan Pemeriksaan**: Merekam hasil pemeriksaan rutin seperti berat badan, tinggi badan, tekanan darah, dan lingkar lengan.
- **Analisis Kesehatan**: Menggunakan algoritma pembelajaran mesin untuk membantu memberikan indikasi awal mengenai status kesehatan remaja berdasarkan data pemeriksaan.
- **Laporan dan Ekspor**: Fitur untuk mencetak laporan pemeriksaan dalam format PDF dan mengekspor data rekapitulasi ke dalam format Excel.
- **Dashboard Admin**: Halaman khusus untuk pengelola sistem guna memantau statistik dan mengelola seluruh data.

## Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan teknologi web modern untuk memastikan performa yang cepat dan tampilan yang responsif.

- **Framework Backend**: Laravel 12 (PHP)
- **Frontend**: Blade Templating Engine & Livewire
- **Styling**: Tailwind CSS 4
- **Database**: MySQL
- **Machine Learning**: Rubix ML / PHP-ML
- **Laporan**: DomPDF (PDF) & Maatwebsite Excel (Excel)

## Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda.

### Prasyarat
Pastikan Anda telah menginstal perangkat lunak berikut:
- PHP (versi 8.2 atau lebih baru)
- Composer
- Node.js dan NPM
- MySQL

### Langkah-langkah

1. **Clone Repositori**
   Unduh kode sumber proyek ini ke komputer Anda.

2. **Instal Dependensi PHP**
   Jalankan perintah berikut di terminal untuk menginstal pustaka yang dibutuhkan oleh Laravel:
   ```bash
   composer install
   ```

3. **Instal Dependensi Frontend**
   Jalankan perintah berikut untuk menginstal dan membangun aset tampilan:
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Lingkungan**
   Salin file konfigurasi contoh dan sesuaikan dengan pengaturan database Anda:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan atur koneksi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5. **Generate Kunci Aplikasi**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database**
   Buat tabel-tabel yang diperlukan di dalam database:
   ```bash
   php artisan migrate
   ```

7. **Jalankan Aplikasi**
   Mulai server lokal untuk mengakses aplikasi:
   ```bash
   php artisan serve
   ```

Aplikasi sekarang dapat diakses melalui browser di alamat yang muncul di terminal (biasanya http://127.0.0.1:8000).
