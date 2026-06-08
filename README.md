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

## 4. Pengujian (Testing)
* **Akses Web:** Silakan buka [http://127.0.0.1:8000](http://127.0.0.1:8000) di browser untuk melihat dan berinteraksi dengan antarmuka aplikasi.
* **Pengujian API (Opsional):** Jika proyek ini mencakup pembuatan API, Anda dapat meng-import file Collection Postman (`Futsal_Tournament.postman_collection.json`) yang telah disertakan di dalam repository ini ke dalam aplikasi Postman Anda untuk menguji seluruh endpoint dan error handling yang sudah dikonfigurasi.

---

## 5. Testing & Dokumentasi API (postman)
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

### Penjelasan Relasi Antar Tabel

1. **`users`**
   - Tabel ini bersifat mandiri (*standalone*) dan digunakan untuk mengelola data pengguna (nama, email, password, role) dalam sistem, seperti autentikasi admin atau panitia turnamen.

2. **`tournaments`**
   - **Hubungan dengan `groups` (One-to-Many):** Satu turnamen (`tournaments`) dapat memiliki banyak grup (`groups`). Relasi ini dihubungkan melalui foreign key `tournament_id` pada tabel `groups`.
   - **Hubungan dengan `matches` (One-to-Many):** Satu turnamen dapat mengadakan banyak pertandingan (`matches`). Relasi ini dihubungkan melalui foreign key `tournament_id` pada tabel `matches`.

3. **`groups`**
   - **Hubungan dengan `group_team` (One-to-Many):** Menghubungkan grup dengan tim yang berpartisipasi di dalamnya. Dihubungkan melalui foreign key `group_id` pada tabel `group_team`.
   - **Hubungan dengan `matches` (One-to-Many):** Satu grup memiliki banyak jadwal pertandingan. Dihubungkan melalui foreign key `group_id` pada tabel `matches`.
   - **Hubungan dengan `standings` (One-to-Many):** Klasemen grup dihubungkan melalui foreign key `group_id` pada tabel `standings`.

4. **`teams`**
   - **Hubungan dengan `players` (One-to-Many):** Satu tim (`teams`) dapat memiliki banyak pemain (`players`). Dihubungkan melalui foreign key `team_id` pada tabel `players`.
   - **Hubungan dengan `group_team` (One-to-Many):** Menghubungkan tim ke dalam grup tertentu. Dihubungkan melalui foreign key `team_id` pada tabel `group_team`.
   - **Hubungan dengan `matches` (One-to-Many, ganda):** Satu tim dapat bertindak sebagai Tim Kesatu (`team1_id`) atau Tim Kedua (`team2_id`) dalam suatu pertandingan. Dihubungkan melalui foreign key `team1_id` dan `team2_id` pada tabel `matches`.
   - **Hubungan dengan `standings` (One-to-Many):** Menghubungkan performa tim di klasemen grup melalui foreign key `team_id` pada tabel `standings`.

5. **`group_team`**
   - Merupakan tabel pivot (*pivot table*) yang merepresentasikan hubungan Many-to-Many antara tabel `groups` dan `teams`. Setiap baris mencatat tim mana saja yang masuk ke dalam grup mana.

6. **`players`**
   - Setiap pemain terikat pada satu tim tertentu melalui foreign key `team_id`. Tabel ini menyimpan detail informasi pemain seperti nama, posisi, nomor punggung, dan tanggal lahir.

7. **`matches`**
   - Menyimpan informasi detail pertandingan futsal. Tabel ini mereferensikan:
     - `tournament_id` ke tabel `tournaments` (menunjukkan pertandingan ini bagian dari turnamen apa).
     - `group_id` ke tabel `groups` (menunjukkan pertandingan di grup mana, bernilai *nullable* untuk fase knockout).
     - `team1_id` dan `team2_id` ke tabel `teams` (menunjukkan kedua tim yang bertanding).
     - `winner_id` ke tabel `teams` (menunjukkan tim pemenang).

8. **`standings`**
   - Menyimpan data klasemen sementara untuk setiap grup. Tabel ini mereferensikan `tournament_id` (tabel `tournaments`), `group_id` (tabel `groups`), dan `team_id` (tabel `teams`) untuk menghitung performa tim (jumlah main, menang, seri, kalah, gol memasukkan, gol kemasukan, selisih gol, dan poin total).

---

## Struktur Tabel Database

Berikut adalah penjelasan struktur kolom untuk masing-masing tabel database hasil migrasi Laravel:

### 1. Tabel `users`
Digunakan untuk menyimpan informasi kredensial pengguna (Admin / Panitia).
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik user |
| `name` | Varchar(255) | Not Null | Nama lengkap user |
| `email` | Varchar(255) | Unique, Not Null | Alamat email (untuk login) |
| `email_verified_at` | Timestamp | Nullable | Waktu email diverifikasi |
| `password` | Varchar(255) | Not Null | Password terenkripsi |
| `role` | Enum('admin', 'panitia') | Default 'panitia' | Peran/hak akses user |
| `remember_token` | Varchar(100) | Nullable | Token untuk fitur "remember me" |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |

### 2. Tabel `teams`
Menyimpan data tim futsal yang mendaftar.
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik tim |
| `name` | Varchar(255) | Not Null | Nama tim futsal |
| `logo` | Varchar(255) | Nullable | Nama file logo tim |
| `coach_name` | Varchar(255) | Not Null | Nama pelatih tim |
| `description` | Text | Nullable | Deskripsi singkat mengenai tim |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |

### 3. Tabel `players`
Menyimpan data pemain yang terdaftar dalam suatu tim.
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik pemain |
| `team_id` | Bigint Unsigned | FK, Constrained, Cascade | ID tim asal pemain |
| `name` | Varchar(255) | Not Null | Nama lengkap pemain |
| `back_number` | Integer | Not Null | Nomor punggung pemain |
| `position` | Varchar(255) | Not Null | Posisi pemain (misal: GK, Pivot, Anchor, Flank) |
| `birth_date` | Date | Not Null | Tanggal lahir pemain |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |

### 4. Tabel `tournaments`
Menyimpan data turnamen futsal yang diadakan.
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik turnamen |
| `name` | Varchar(255) | Not Null | Nama turnamen |
| `status` | Enum('draft', 'ongoing', 'completed') | Default 'draft' | Status pelaksanaan turnamen |
| `start_date` | Date | Not Null | Tanggal mulai turnamen |
| `end_date` | Date | Not Null | Tanggal berakhir turnamen |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |

### 5. Tabel `groups`
Menyimpan data pembagian grup dalam turnamen (misal: Grup A, Grup B).
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik grup |
| `tournament_id` | Bigint Unsigned | FK, Constrained, Cascade | ID turnamen terkait |
| `name` | Varchar(255) | Not Null | Nama grup (misal: Group A) |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |

### 6. Tabel `group_team` (Pivot Table)
Tabel relasi Many-to-Many untuk menghubungkan tim ke dalam grup.
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `group_id` | Bigint Unsigned | Composite PK, FK, Cascade | ID grup |
| `team_id` | Bigint Unsigned | Composite PK, FK, Cascade | ID tim |

### 7. Tabel `matches`
Menyimpan jadwal dan hasil skor pertandingan.
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik pertandingan |
| `tournament_id` | Bigint Unsigned | FK, Constrained, Cascade | ID turnamen terkait |
| `group_id` | Bigint Unsigned | FK, Nullable, Cascade | ID grup terkait (null jika fase knockout) |
| `round` | Enum('group', 'quarterfinal', 'semifinal', 'final') | Default 'group' | Babak pertandingan |
| `team1_id` | Bigint Unsigned | FK, Constrained, Cascade | ID Tim Kesatu |
| `team2_id` | Bigint Unsigned | FK, Constrained, Cascade | ID Tim Kedua |
| `team1_score` | Integer | Nullable | Skor gol Tim Kesatu |
| `team2_score` | Integer | Nullable | Skor gol Tim Kedua |
| `winner_id` | Bigint Unsigned | FK, Nullable, Cascade | ID Tim pemenang |
| `match_date` | Datetime | Not Null | Waktu pertandingan dimulai |
| `status` | Enum('scheduled', 'played') | Default 'scheduled' | Status pelaksanaan pertandingan |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |

### 8. Tabel `standings`
Menyimpan data klasemen tim di dalam grup.
| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | Bigint Unsigned | PK, Auto Increment | ID unik klasemen |
| `tournament_id` | Bigint Unsigned | FK, Constrained, Cascade | ID turnamen terkait |
| `group_id` | Bigint Unsigned | FK, Constrained, Cascade | ID grup terkait |
| `team_id` | Bigint Unsigned | FK, Constrained, Cascade | ID tim terkait |
| `played` | Integer | Default 0 | Jumlah pertandingan dimainkan |
| `won` | Integer | Default 0 | Jumlah kemenangan |
| `drawn` | Integer | Default 0 | Jumlah seri / imbang |
| `lost` | Integer | Default 0 | Jumlah kekalahan |
| `goals_for` | Integer | Default 0 | Jumlah gol dicetak |
| `goals_against` | Integer | Default 0 | Jumlah gol kemasukan |
| `goals_difference` | Integer | Default 0 | Selisih gol |
| `points` | Integer | Default 0 | Jumlah poin |
| `created_at` | Timestamp | Nullable | Waktu data dibuat |
| `updated_at` | Timestamp | Nullable | Waktu data diupdate |


