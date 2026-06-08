# Project UTS - Laravel Web Application

## 1. Identitas
* **Nama:** Levy Danendra Fahriza Zani
* **NIM:** 2305101032
* **Kelas:** 6B
* **Mata Kuliah:** Pemrograman Web
* **Repository URL:** [https://github.com/levydanendra775/Project-UTS-Laravel](https://github.com/levydanendra775/Project-UTS-Laravel)

---

## 2. Persyaratan Sistem (System Requirements)
Sebelum menjalankan proyek ini, pastikan perangkat kamu sudah memenuhi spesifikasi minimum berikut:

* **PHP:** Version 8.1 atau lebih tinggi
* **Composer:** Version 2.0 atau lebih tinggi
* **Database:** MySQL / MariaDB (atau XAMPP/Laragon)
* **Web Server:** Apache / Nginx (Sudah include di XAMPP/Laragon)
* **Node.js & NPM:** (Opsional, jika proyek menggunakan Vite/Mix untuk frontend asset)

---

## 3. Cara Menjalankan Project / Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di komputer lokal kamu:

### Langkah 1: Clone Repository
Buka terminal atau Git Bash, lalu jalankan perintah berikut untuk menyalin proyek:
```bash
git clone [https://github.com/levydanendra775/Project-UTS-Laravel.git](https://github.com/levydanendra775/Project-UTS-Laravel.git)
cd Project-UTS-Laravel

2. Setup Environment Variables
Copy file .env.example dan ubah namanya menjadi .env. Setelah itu, generate application key dengan perintah:

php artisan key:generate

3. Setup Database
Buka phpMyAdmin atau aplikasi manajemen database lainnya, lalu buat database baru (misalnya dengan nama db_futsal_tournament).

Buka file .env di text editor, lalu sesuaikan konfigurasi koneksi database berikut:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_futsal_tournament
DB_USERNAME=root
DB_PASSWORD=

4. Jalankan Migrasi & Seeder
Untuk membuat struktur tabel dan mengisi data awal (dummy data) secara otomatis, jalankan perintah ini di Terminal:
php artisan migrate --seed
Server akan berjalan dan dapat diakses melalui browser di: http://127.0.0.1:8000.

5. Jalankan Local Server
Setelah semua setup selesai, jalankan server Laravel dengan perintah:

Pengujian (Testing)
Akses Web: Silakan buka http://127.0.0.1:8000 di browser untuk melihat dan berinteraksi dengan antarmuka aplikasi.

Pengujian API (Opsional): Jika proyek ini mencakup pembuatan API, Anda dapat meng-import file Collection Postman (.json) yang telah disertakan di dalam repository ini ke dalam aplikasi Postman Anda untuk menguji seluruh endpoint dan error handling yang sudah dikonfigurasi.