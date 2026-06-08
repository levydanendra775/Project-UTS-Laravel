# Laporan Project UTS - Praktikum Pemrograman Web Fullstack

## Identitas
* **Nama:** Levy Danendra Fahriza Zani
* **NIM:** 2305101032
* **Kelas:** 6B
* **Mata Kuliah:** Pemrograman Web
* **Repository URL:** [https://github.com/levydanendra775/Project-UTS-Laravel](https://github.com/levydanendra775/Project-UTS-Laravel)

---

## Persyaratan Sistem (System Requirements)
Sebelum menjalankan proyek ini, pastikan perangkat kamu sudah memenuhi spesifikasi minimum berikut:

* **PHP:** Version 8.1 atau lebih tinggi
* **Composer:** Version 2.0 atau lebih tinggi
* **Database:** MySQL / MariaDB (atau XAMPP/Laragon)
* **Web Server:** Apache / Nginx (Sudah include di XAMPP/Laragon)
* **Node.js & NPM:** (Opsional, jika proyek menggunakan Vite/Mix untuk frontend asset)

---

## Cara Menjalankan Project / Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di komputer lokal Anda:

### Langkah 1: Clone Repository
Buka terminal atau Git Bash, lalu jalankan perintah berikut untuk menyalin proyek:
```bash
git clone https://github.com/levydanendra775/Project-UTS-Laravel.git
cd Project-UTS-Laravel
```

### Langkah 2: Setup Environment Variables
Salin file `.env.example` dan ubah namanya menjadi `.env`. Setelah itu, generate application key dengan perintah:
```bash
cp .env.example .env
php artisan key:generate
```

### Langkah 3: Setup Database
Buka phpMyAdmin atau aplikasi manajemen database lainnya, lalu buat database baru (misalnya dengan nama `db_futsal_tournament`).

Buka file `.env` di text editor, lalu sesuaikan konfigurasi koneksi database berikut:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_futsal_tournament
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 4: Jalankan Migrasi & Seeder
Untuk membuat struktur tabel dan mengisi data awal (dummy data) secara otomatis, jalankan perintah ini di Terminal:
```bash
php artisan migrate --seed
```

### Langkah 5: Jalankan Local Server
Setelah semua setup selesai, jalankan server Laravel dengan perintah:
```bash
php artisan serve
```
Server akan berjalan dan dapat diakses melalui browser di: http://127.0.0.1:8000.

---

## Postman Collection

File Postman Collection tersedia di root project:

```text
Futsal_Tournament.postman_collection.json
```

Import file ini ke Postman untuk langsung menguji semua endpoint API yang tersedia.

---

## Tech Stack

| Teknologi | Versi |
| :--- | :--- |
| PHP | `^8.3` |
| Laravel | `^13.8` |
| barryvdh/laravel-dompdf | `^3.1` |
| MySQL | `8.0+` |

---


## Pengujian (Testing)
* **Akses Web:** Silakan buka [http://127.0.0.1:8000](http://127.0.0.1:8000) di browser untuk melihat dan berinteraksi dengan antarmuka aplikasi.
* **Pengujian API (Opsional):** Jika proyek ini mencakup pembuatan API, Anda dapat meng-import file Collection Postman (`Futsal_Tournament.postman_collection.json`) yang telah disertakan di dalam repository ini ke dalam aplikasi Postman Anda untuk menguji seluruh endpoint dan error handling yang sudah dikonfigurasi.

---

## Testing & Dokumentasi API (postman)
Berikut adalah beberapa tampilan antarmuka (interface) dari aplikasi Futsal Tournament:

1. **Landing Page**
   ![Landing Page](img/01.%20landing_page.png)

2. **Halaman Login**
   ![Login Page](img/02.%20show_login_page.png)

3. **Aksi Login Admin**
   ![Admin Login Action](img/03.%20admin_login_action.png)

4. **Dashboard Admin**
   ![Access Dashboard](img/04.%20access_dashboard.png)

5. **Form Pembuatan Tim**
   ![Team Creation Form](img/05.%20get_team_creation_from.png)

6. **Aksi Pembuatan Tim**
   ![Create Team Action](img/06.%20create%20_team_action.png)

7. **Daftar Tim & Validasi**
   ![List Team and Validate](img/07.%20list_team_and_validate.png)

8. **Detail Turnamen**
   ![Tournament Detail Page](img/08.%20tournament_detail_page.png)

9. **Halaman Bracket Turnamen (Knockout)**
   ![Tournament Knockout Bracket](img/09.%20tournament_knockout_bracket_page.png)

10. **Aksi Logout**
    ![Logout Action](img/10.%20logout_action.png)

---

## ERD Database & Relasi

Berikut adalah diagram ERD (Entity Relationship Diagram) dari database aplikasi Futsal Tournament:

![ERD Database](ERD/erd_database.png)

Berdasarkan *Foreign Key* (FK) yang dirancang pada database, berikut adalah relasi antar tabel:
* **Tournament ke Group (One-to-Many):** Satu turnamen dapat memiliki banyak grup penyisihan. *Foreign key* `tournament_id` berada di tabel `groups`.
* **Tournament ke Match (One-to-Many):** Satu turnamen dapat menyelenggarakan banyak pertandingan. *Foreign key* `tournament_id` berada di tabel `matches`.
* **Tournament ke Standing (One-to-Many):** Satu turnamen dapat memiliki banyak klasemen grup. *Foreign key* `tournament_id` berada di tabel `standings`.
* **Group ke Match (One-to-Many):** Satu grup dapat memiliki banyak pertandingan penyisihan. *Foreign key* `group_id` berada di tabel `matches`.
* **Group ke Standing (One-to-Many):** Satu grup memiliki banyak catatan klasemen tim. *Foreign key* `group_id` berada di tabel `standings`.
* **Team ke Player (One-to-Many):** Satu tim dapat memiliki banyak pemain yang terdaftar. *Foreign key* `team_id` berada di tabel `players`.
* **Team ke Group (Many-to-Many):** Hubungan tim dan grup dihubungkan melalui tabel pivot `group_team` dengan *foreign key* `group_id` dan `team_id`.
* **Team ke Match (One-to-Many, Ganda):** Satu tim dapat bertindak sebagai Tim Kesatu (`team1_id`), Tim Kedua (`team2_id`), atau Tim Pemenang (`winner_id`) di dalam pertandingan. *Foreign key* `team1_id`, `team2_id`, dan `winner_id` berada di tabel `matches`.
* **Team ke Standing (One-to-Many):** Satu tim memiliki catatan performa klasemen di dalam grup. *Foreign key* `team_id` berada di tabel `standings`.


---

## Struktur Tabel Database

| Tabel | Kolom |
| :--- | :--- |
| **`users`** | `id`, `name`, `email`, `password`, `role (enum: admin, panitia)`, `remember_token`, `timestamps` |
| **`teams`** | `id`, `name`, `logo (nullable)`, `coach_name`, `description (text, nullable)`, `timestamps` |
| **`players`** | `id`, `team_id (FK)`, `name`, `back_number`, `position`, `birth_date (date)`, `timestamps` |
| **`tournaments`** | `id`, `name`, `status (enum: draft, ongoing, completed)`, `start_date (date)`, `end_date (date)`, `timestamps` |
| **`groups`** | `id`, `tournament_id (FK)`, `name`, `timestamps` |
| **`group_team`** | `group_id (FK)`, `team_id (FK)` |
| **`matches`** | `id`, `tournament_id (FK)`, `group_id (FK, nullable)`, `round (enum: group, quarterfinal, semifinal, final)`, `team1_id (FK)`, `team2_id (FK)`, `team1_score (nullable)`, `team2_score (nullable)`, `winner_id (FK, nullable)`, `match_date (datetime)`, `status (enum: scheduled, played)`, `timestamps` |
| **`standings`** | `id`, `tournament_id (FK)`, `group_id (FK)`, `team_id (FK)`, `played (int)`, `won (int)`, `drawn (int)`, `lost (int)`, `goals_for (int)`, `goals_against (int)`, `goals_difference (int)`, `points (int)`, `timestamps` |

---

## Relasi Eloquent

```text
Tournament       -> hasMany        -> Group
Tournament       -> hasMany        -> TournamentMatch
Tournament       -> hasMany        -> Standing
Group            -> belongsTo      -> Tournament
Group            -> belongsToMany  -> Team
Group            -> hasMany        -> TournamentMatch
Group            -> hasMany        -> Standing
Team             -> hasMany        -> Player
Team             -> belongsToMany  -> Group
Team             -> hasMany        -> Standing
Team             -> hasMany        -> TournamentMatch (sebagai team1 / home)
Team             -> hasMany        -> TournamentMatch (sebagai team2 / away)
Player           -> belongsTo      -> Team
TournamentMatch  -> belongsTo      -> Tournament
TournamentMatch  -> belongsTo      -> Group
TournamentMatch  -> belongsTo      -> Team (sebagai team1)
TournamentMatch  -> belongsTo      -> Team (sebagai team2)
TournamentMatch  -> belongsTo      -> Team (sebagai winner)
Standing         -> belongsTo      -> Tournament
Standing         -> belongsTo      -> Group
Standing         -> belongsTo      -> Team
```

---

## Header Wajib

```text
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}   ← untuk route protected
```

---

## Middleware & Otorisasi

Sistem menggunakan satu middleware custom untuk mengontrol akses berdasarkan role:

| Middleware | Alias | Akses Diizinkan |
| :--- | :--- | :--- |
| `RoleMiddleware` | `role` | Membatasi akses berdasarkan parameter role (`admin`, `panitia`) |

Pengelompokan Route berdasarkan Role:

* **Public (Guest/Visitor):** `GET /` , `GET /public/tournaments/{tournament}` , `GET /public/tournaments/{tournament}/knockout`
* **Semua User Terautentikasi (Admin & Panitia):** `GET /dashboard` , `POST /logout`
* **Panitia & Admin:** `GET /tournaments/{tournament}` , `GET /tournaments/{tournament}/knockout` , `GET/POST/PUT/DELETE /matches` , `GET /tournaments/{tournament}/pdf`
* **Admin Only (Full Access):** CRUD `/teams` , CRUD `/players` , CRUD `/tournaments` , CRUD `/groups` , POST `/knockout/initialize`

---

## Daftar Endpoint API

| Modul | Method | Endpoint (Route) | Deskripsi & Akses |
| :--- | :--- | :--- | :--- |
| **Auth** | `GET` | `/login` | Tampilan halaman login (Guest) |
| | `POST` | `/login` | Proses autentikasi login (Guest) |
| | `GET` | `/register` | Tampilan halaman pendaftaran (Guest) |
| | `POST` | `/register` | Proses pendaftaran pengguna baru (Guest) |
| | `POST` | `/logout` | Logout dari sistem dan menghapus session (Semua User Terautentikasi) |
| **Dashboard**| `GET` | `/dashboard` | Menampilkan dashboard statistik utama (Admin & Panitia) |
| **Teams** | `GET` | `/teams` | Menampilkan daftar seluruh tim futsal (Admin Only) |
| | `POST` | `/teams` | Menyimpan data tim futsal baru (Admin Only) |
| | `GET` | `/teams/create` | Form untuk mendaftarkan tim futsal baru (Admin Only) |
| | `GET` | `/teams/{team}` | Menampilkan detail profil tim futsal tertentu (Admin Only) |
| | `GET` | `/teams/{team}/edit` | Form untuk mengubah profil tim futsal tertentu (Admin Only) |
| | `PUT`/`PATCH`| `/teams/{team}` | Memperbarui profil tim futsal tertentu (Admin Only) |
| | `DELETE` | `/teams/{team}` | Menghapus tim futsal dari sistem (Admin Only) |
| **Players** | `GET` | `/players` | Menampilkan daftar seluruh pemain (Admin Only) |
| | `POST` | `/players` | Menyimpan data pemain baru (Admin Only) |
| | `GET` | `/players/create` | Form untuk menambahkan pemain baru (Admin Only) |
| | `GET` | `/players/{player}` | Menampilkan detail profil pemain tertentu (Admin Only) |
| | `GET` | `/players/{player}/edit`| Form untuk mengubah profil pemain tertentu (Admin Only) |
| | `PUT`/`PATCH`| `/players/{player}` | Memperbarui profil pemain tertentu (Admin Only) |
| | `DELETE` | `/players/{player}` | Menghapus data pemain dari sistem (Admin Only) |
| **Tournaments**| `GET` | `/tournaments` | Menampilkan daftar turnamen kelolaan (Admin Only) |
| | `POST` | `/tournaments` | Membuat data turnamen futsal baru (Admin Only) |
| | `GET` | `/tournaments/create`| Form untuk membuat turnamen baru (Admin Only) |
| | `GET` | `/tournaments/{tournament}/edit`| Form untuk mengubah data turnamen (Admin Only) |
| | `PUT`/`PATCH`| `/tournaments/{tournament}`| Memperbarui data turnamen (Admin Only) |
| | `DELETE` | `/tournaments/{tournament}`| Menghapus turnamen dari database (Admin Only) |
| | `GET` | `/tournaments/{tournament}`| Detail turnamen & manajemen grup/fase (Admin & Panitia) |
| | `GET` | `/tournaments/{tournament}/knockout`| Panel bracket fase knockout admin (Admin & Panitia) |
| | `GET` | `/tournaments/{tournament}/pdf`| Generate dan unduh PDF laporan turnamen (Admin & Panitia) |
| **Groups** | `GET` | `/tournaments/{tournament}/groups/create`| Form untuk menambahkan grup di turnamen (Admin Only) |
| | `POST` | `/tournaments/{tournament}/groups`| Menyimpan grup baru di dalam turnamen (Admin Only) |
| | `DELETE` | `/groups/{group}` | Menghapus grup dari turnamen (Admin Only) |
| | `GET` | `/groups/{group}/teams`| Form untuk mengelola pembagian tim ke dalam grup (Admin Only) |
| | `POST` | `/groups/{group}/teams`| Memperbarui pembagian tim dalam grup (Admin Only) |
| **Knockout** | `POST` | `/tournaments/{tournament}/knockout/initialize-quarterfinals`| Menginisialisasi bracket perempat final (Admin Only) |
| | `POST` | `/tournaments/{tournament}/knockout/initialize-semifinals`| Menginisialisasi bracket semifinal (Admin Only) |
| **Matches** | `GET` | `/tournaments/{tournament}/matches/create`| Form pembuatan jadwal pertandingan manual (Admin & Panitia) |
| | `POST` | `/tournaments/{tournament}/matches`| Menyimpan jadwal pertandingan baru manual (Admin & Panitia) |
| | `POST` | `/tournaments/{tournament}/matches/generate`| Generate otomatis jadwal pertandingan penyisihan grup (Admin & Panitia) |
| | `GET` | `/matches/{match}/edit`| Form pengubahan jadwal pertandingan (Admin & Panitia) |
| | `PUT`/`PATCH`| `/matches/{match}` | Memperbarui jadwal pertandingan (Admin & Panitia) |
| | `DELETE` | `/matches/{match}` | Menghapus pertandingan dari jadwal (Admin & Panitia) |
| | `GET` | `/matches/{match}/score`| Form pengisian hasil skor pertandingan (Admin & Panitia) |
| | `POST` | `/matches/{match}/score`| Menyimpan skor gol dan pemenang pertandingan (Admin & Panitia) |
| **Public View**| `GET` | `/` | Tampilan Landing Page umum (Semua Pengguna / Guest) |
| | `GET` | `/public/tournaments/{tournament}`| Tampilan klasemen & jadwal publik turnamen tertentu (Semua Pengguna) |
| | `GET` | `/public/tournaments/{tournament}/knockout`| Tampilan bagan/bracket fase knockout publik (Semua Pengguna) |

---

## Akun Default (Seeder)

| Role | Nama | Email | Password |
| :--- | :--- | :--- | :--- |
| Admin | Admin Utama | admin@futsal.com | admin123 |
| Panitia | Panitia Lapangan | panitia@futsal.com | panitia123 |

---

## Kendala dan Solusi

* **Kendala:** Saat melakukan pengujian aksi `POST` (seperti Login, Pembuatan Tim, atau Input Skor) di luar browser atau di Postman, request ditolak/gagal karena masalah perlindungan CSRF (*CSRF protection*).
* **Solusi:** Di sisi web form, tag `@csrf` wajib ditambahkan ke dalam form HTML. Di sisi pengujian Postman, ditambahkan *scripts* Cheerio pada test-run untuk mengekstrak CSRF token dari halaman login/form secara otomatis dan menyimpannya sebagai *environment variable* untuk dikirimkan bersama request berikutnya.
* **Kendala:** Penghapusan data master seperti tim atau turnamen yang sudah memiliki data relasi (seperti pemain, pertandingan, atau grup) dapat menyebabkan kegagalan integritas data database (*foreign key constraint error*).
* **Solusi:** Seluruh *foreign key* didefinisikan menggunakan metode `.onDelete('cascade')` pada *file migrations* Laravel. Dengan demikian, jika data master dihapus, data detail yang terkait akan terhapus secara otomatis dan bersih oleh server database.




