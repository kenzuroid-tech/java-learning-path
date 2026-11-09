# JavaPath - Adaptive Learning System

Platform pembelajaran Java yang adaptif dengan sistem placement test, stage progression, dan personalisasi konten berdasarkan kemampuan siswa.

## 🎯 Fitur Utama

### 1. **Placement Test**
- Test awal untuk menentukan level kemampuan siswa
- Soal dengan berbagai tingkat kesulitan (mudah, sedang, sulit)
- Otomatis merekomendasikan stage mulai berdasarkan hasil test

### 2. **Stage Learning (Pembelajaran Bertahap)**
- Pembelajaran Java dibagi menjadi stage-stage
- Setiap stage memiliki:
  - **Materi Pembelajaran** - Konten teori dengan contoh kode
  - **Kuis** - Test pemahaman per stage
  - **Praktikum** - Latihan coding dengan hints bertahap
  
### 3. **Adaptive System**
- Sistem XP (Experience Points) dan Level
- Rekomendasi materi berdasarkan kelemahan siswa
- Tracking progress per topik
- Badge/achievement system

### 4. **Tipe Soal Beragam**
- **Pilihan Ganda** - Multiple choice questions
- **Urutan Kode** - Drag & drop code snippets
- **Tulis Kode** - Write code/output manually

### 5. **Admin Panel (Filament)**
- Kelola konten pembelajaran
- Monitoring progress siswa
- Manajemen grup ujian
- Analisis performa per topik

## 🛠️ Tech Stack

- **Framework**: Laravel 13
- **Admin Panel**: Filament 4
- **Frontend**: Blade, Alpine.js, Tailwind CSS
- **Database**: SQLite (Development) / PostgreSQL (Production)
- **Authentication**: Laravel Breeze

## 📋 Persyaratan Sistem

- PHP 8.3 atau lebih tinggi
- Composer
- Node.js & NPM
- Git

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd "java adaptive learning"
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### 4. Setup Database

**Untuk SQLite (Recommended untuk development):**
```bash
# Buat file database
type nul > database\database.sqlite

# Pastikan .env menggunakan SQLite
# DB_CONNECTION=sqlite
```

**Untuk PostgreSQL:**
Edit `.env` dan sesuaikan:
```env
DB_CONNECTION=pgsql
DB_HOST=your-host
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

### 5. Jalankan Migrasi
```bash
php artisan migrate:fresh
```

### 6. Buat Admin User
```bash
php artisan tinker
```

Lalu ketik di console tinker:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'superadmin'
]);
```

Keluar dengan `Ctrl+C`

### 7. Build Assets
```bash
npm run build
```

### 8. Jalankan Aplikasi

**Opsi A - Development Mode (Recommended):**
```bash
composer dev
```
Ini akan otomatis menjalankan:
- Laravel server (port 8000)
- Queue worker
- Log viewer (Pail)
- Vite dev server

**Opsi B - Manual:**

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

## 🌐 Akses Aplikasi

- **Frontend (Siswa)**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
  - Email: `admin@example.com`
  - Password: `password`

## 📚 Struktur Database

### Tabel Utama

#### **users**
- Data pengguna (siswa & admin)
- Field adaptive: `xp`, `level`, `placement_done`
- Role: `superadmin` atau `siswa`

#### **stages**
- Stage pembelajaran (1, 2, 3, dst)
- Urutan pembelajaran bertahap

#### **exam_groups**
- Grup ujian berdasarkan rentang stage
- Konfigurasi jumlah soal dan min. kelulusan

#### **materials**
- Konten materi per stage
- Mendukung file PDF

#### **questions**
- Bank soal
- Tipe: `placement`, `kuis`, `ujian`
- Tipe soal: `pilihan_ganda`, `urutan_kode`, `tulis_kode`

#### **praktikum**
- Latihan coding interaktif
- Hints bertahap (mudah → detail)
- Expected output untuk validasi

#### **student_progress**
- Tracking kemajuan siswa per stage

#### **exam_results**
- Hasil ujian/kuis siswa

#### **placement_results**
- Hasil placement test
- Rekomendasi stage awal

#### **recommendations**
- Rekomendasi materi adaptif berdasarkan kelemahan

#### **badges**
- Achievement/badge yang didapat siswa

## 📖 Panduan Penggunaan Admin

### 1. Setup Konten Pembelajaran

#### a. Buat Stage
1. Buka menu **Stage / Tahap**
2. Klik **New Stage**
3. Isi:
   - Nama: "Pengenalan Java"
   - Urutan: 1
   - Deskripsi: Penjelasan singkat stage

#### b. Buat Materi
1. Buka menu **Materi**
2. Klik **New Materi**
3. Pilih stage
4. Isi konten materi (WYSIWYG editor)
5. Upload file PDF (opsional)

#### c. Buat Soal
1. Buka menu **Soal**
2. Klik **New Soal**
3. Pilih:
   - **Jenis Soal**: Placement/Kuis/Ujian
   - **Tipe Soal**: Pilihan Ganda/Urutan Kode/Tulis Kode
   - **Stage**: Untuk kuis
   - **Grup Ujian**: Untuk ujian
   - **Topik**: e.g., "variabel", "loop", "array"
   - **Tingkat Kesulitan**: Mudah/Sedang/Sulit

**Contoh Soal Placement (Pilihan Ganda):**
```
Pertanyaan: Apa output dari System.out.println(5 + 3 * 2);
Opsi A: 16
Opsi B: 11 (✓ Benar)
Opsi C: 13
Opsi D: 10
Topik: operator-aritmatika
Kesulitan: Mudah
```

#### d. Buat Praktikum
1. Buka menu **Praktikum**
2. Klik **New Praktikum**
3. Isi:
   - **Stage**: Pilih stage
   - **Judul**: "Membuat Program Hello World"
   - **Instruksi**: Penjelasan tugas
   - **Kode Awal**: Starter code
   - **Expected Output**: Output yang diharapkan
   - **Hints**: 3 level hints (mudah → detail)

#### e. Buat Grup Ujian
1. Buka menu **Grup Ujian**
2. Klik **New Grup Ujian**
3. Isi:
   - **Nama Grup**: "Ujian Dasar Java"
   - **Stage Dari**: Stage 1
   - **Stage Sampai**: Stage 3
   - **Total Soal**: 20
   - **Min. Lulus**: 70

### 2. Monitoring Siswa

- **Dashboard**: Overview statistik
- **Pengguna**: Lihat data siswa, progress, XP, level
- Filter dan search untuk analisis

## 🎓 Flow Pembelajaran Siswa

1. **Register/Login**
2. **Placement Test** (jika belum pernah)
   - Mengerjakan soal placement
   - Sistem menentukan stage awal
3. **Belajar di Stage**
   - Baca materi
   - Kerjakan kuis
   - Selesaikan praktikum
4. **Progress ke Stage Berikutnya**
5. **Ujian** (per grup stage)
6. **Dapatkan Badge & Level Up**

## 🔧 Troubleshooting

### Error: Migration Failed
```bash
# Hapus database dan mulai fresh
del database\database.sqlite
type nul > database\database.sqlite
php artisan migrate:fresh
```

### Error: Filament Cache
```bash
php artisan filament:cache-clear
```

### Error: Permission Denied (Composer/Artisan)
Pastikan menjalankan terminal sebagai Administrator atau cek permission di workspace root.

### Assets Tidak Muncul
```bash
npm run build
php artisan config:clear
php artisan view:clear
```

## 📁 Struktur Project

```
├── app/
│   ├── Filament/Resources/    # Admin panel resources
│   ├── Http/Controllers/      # Controllers
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── views/                 # Blade templates
│   └── js/                    # JavaScript files
├── public/                    # Public assets
├── routes/
│   ├── web.php               # Web routes
│   └── auth.php              # Auth routes
└── storage/                   # File storage
```

## 🤝 Kontribusi

1. Fork repository
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 Lisensi

[MIT License](LICENSE)

## 👨‍💻 Developer

Dibuat dengan ❤️ untuk pembelajaran Java yang lebih adaptif dan personal.

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan buat issue di repository atau hubungi developer.

---

**Happy Learning! 🚀**
