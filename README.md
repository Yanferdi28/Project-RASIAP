# RASIAP - Sistem Rekam Arsip

Aplikasi Sistem Manajemen Arsip berbasis web yang dibangun menggunakan Laravel 12 dan Filament 4.0.

## Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Fitur Utama](#fitur-utama)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Struktur Proyek](#struktur-proyek)
- [Role & Permission](#role--permission)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Optimasi Performa](#optimasi-performa)
- [Perintah Artisan](#perintah-artisan)

---

## Tentang Aplikasi

RASIAP adalah sistem manajemen arsip yang dirancang untuk mengelola dokumen arsip secara digital. Aplikasi ini memungkinkan pengelolaan arsip unit, berkas arsip, klasifikasi, dan verifikasi dokumen dengan sistem role-based access control.

## Fitur Utama

### Manajemen Arsip
- **Arsip Unit (Naskah)**: Pengelolaan arsip individual dengan sistem verifikasi
- **Berkas Arsip**: Pengelompokan arsip unit ke dalam folder/berkas
- **Kode Klasifikasi**: Pengaturan kode klasifikasi arsip
- **Kategori & Sub Kategori**: Pengelompokan arsip berdasarkan kategori

### Sistem Verifikasi
- Status verifikasi: Pending, Diterima, Ditolak
- Catatan verifikasi oleh verifikator
- Riwayat perubahan status

### Ekspor & Laporan
- Ekspor ke PDF (menggunakan DomPDF)
- Ekspor ke Excel (menggunakan Laravel Excel)
- Cetak daftar isi berkas
- Laporan arsip unit per periode

### Manajemen Pengguna
- Registrasi dengan verifikasi admin
- Multi-role system (Superadmin, Admin, Operator, User, Manajemen)
- Pembatasan akses berdasarkan Unit Pengolah

## Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 8.0 atau MariaDB >= 10.4
- Laravel 12.x

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/Project-RASIAP.git
cd Project-RASIAP
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Konfigurasi Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rasiap
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi & Seeder

```bash
# Jalankan migrasi database
php artisan migrate

# Jalankan seeder (opsional, untuk data awal)
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Aplikasi

```bash
# Mode development (dengan hot reload)
composer dev

# Atau jalankan secara terpisah:
php artisan serve
npm run dev
php artisan queue:listen
```

Akses aplikasi di: `http://localhost:8000`

## Konfigurasi

### Storage Link

Untuk mengakses file dokumen yang diupload:

```bash
php artisan storage:link
```

### Queue

Aplikasi menggunakan queue untuk notifikasi. Pastikan queue worker berjalan:

```bash
php artisan queue:listen
```

### Cache

Untuk production, optimalkan dengan caching:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Struktur Proyek

```
Project-RASIAP/
├── app/
│   ├── Actions/              # Custom actions (Export, Import)
│   ├── Events/               # Event classes
│   ├── Exports/              # Excel export classes
│   ├── Filament/             # Filament admin panel
│   │   ├── Actions/          # Filament actions
│   │   ├── Imports/          # Import handlers
│   │   ├── Resources/        # CRUD resources
│   │   └── Widgets/          # Dashboard widgets
│   ├── Helpers/              # Helper functions
│   ├── Http/
│   │   ├── Controllers/      # HTTP Controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form requests
│   ├── Listeners/            # Event listeners
│   ├── Models/               # Eloquent models
│   ├── Notifications/        # Notification classes
│   ├── Observers/            # Model observers
│   ├── Policies/             # Authorization policies
│   ├── Providers/            # Service providers
│   ├── Services/             # Business logic services
│   └── View/Components/      # Blade components
├── config/                   # Configuration files
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── templates/            # Import templates
├── lang/                     # Language files
├── public/                   # Public assets
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/                   # Route definitions
├── storage/                  # Application storage
└── tests/                    # Test files
```

## Role & Permission

### Daftar Role

| Role | Deskripsi |
|------|-----------|
| `superadmin` | Akses penuh ke semua fitur |
| `admin` | Manajemen user, verifikasi, dan konfigurasi |
| `operator` | Verifikasi arsip dan pengelolaan kategori |
| `user` | Input dan edit arsip di unit sendiri |
| `manajemen` | Lihat laporan dan data semua unit |

### Akses Panel

- **Panel Admin** (`/admin`): superadmin, admin
- **Panel User** (`/user`): semua role yang terverifikasi

### Pembatasan Data

- User biasa hanya dapat melihat data dari Unit Pengolah miliknya
- Admin dan Superadmin dapat melihat semua data
- Operator dapat melihat data dengan kategori yang sudah ditentukan

## Panduan Penggunaan

### Login

1. Akses `http://localhost:8000/login`
2. Masukkan email dan password
3. Sistem akan mengarahkan ke panel sesuai role

### Menambah Arsip Unit

1. Masuk ke menu **Arsip Unit**
2. Klik tombol **Buat Baru**
3. Isi form dengan data arsip
4. Upload dokumen (opsional)
5. Klik **Simpan**

### Verifikasi Arsip

1. Admin/Operator masuk ke menu **Arsip Unit**
2. Klik ikon centang pada arsip yang akan diverifikasi
3. Pilih keputusan (Terima/Tolak)
4. Tambahkan keterangan jika diperlukan
5. Klik **Simpan**

### Ekspor Laporan

1. Masuk ke menu **Arsip Unit** atau **Berkas Arsip**
2. Klik tombol **Cetak**
3. Pilih format (PDF/Excel)
4. Tentukan rentang tanggal
5. Klik **Ekspor**

### Import Data

1. Download template import dari sistem
2. Isi data sesuai format template
3. Masuk ke menu **Arsip Unit**
4. Klik tombol **Import**
5. Upload file Excel
6. Klik **Import**

## Optimasi Performa

### Database Indexing

Tabel-tabel utama sudah dilengkapi dengan index pada kolom yang sering digunakan:

- `arsip_units`: kode_klasifikasi_id, unit_pengolah_arsip_id, status, kategori_id, dll.
- `berkas_arsip`: klasifikasi_id, unit_pengolah_id, created_at, nama_berkas

### Eager Loading

Model sudah dilengkapi dengan scope untuk eager loading:

```php
// Contoh penggunaan
ArsipUnit::withCommonRelationships()->get();
BerkasArsip::withCommonRelationships()->get();
```

### Caching

Data referensi di-cache untuk mengurangi query database:

- `KodeKlasifikasi::getAllCached()` - Cache 1 jam
- `Kategori::getAllCached()` - Cache 1 jam
- `UnitPengolah::getAllCached()` - Cache 1 jam

Cache otomatis di-invalidate saat data berubah.

### N+1 Query Prevention

Di mode development, lazy loading dicegah untuk mendeteksi N+1 queries.

## Perintah Artisan

### Development

```bash
# Jalankan development server dengan queue dan vite
composer dev

# Clear semua cache
php artisan optimize:clear

# Refresh database (HATI-HATI: menghapus semua data)
php artisan migrate:fresh --seed
```

### Production

```bash
# Optimasi untuk production
php artisan optimize

# Atau jalankan secara terpisah:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Maintenance

```bash
# Clear cache spesifik
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Membuat storage link
php artisan storage:link
```

## Teknologi yang Digunakan

| Teknologi | Versi | Deskripsi |
|-----------|-------|-----------|
| Laravel | 12.x | PHP Framework |
| Filament | 4.0 | Admin Panel |
| Spatie Permission | 6.x | Role & Permission |
| DomPDF | 3.x | PDF Generation |
| Laravel Excel | 3.x | Excel Export/Import |
| Tailwind CSS | 3.x | CSS Framework |
| Vite | 6.x | Build Tool |

## Struktur Database

### Diagram ERD

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  unit_pengolah  │     │ kode_klasifikasi│     │    kategori     │
├─────────────────┤     ├─────────────────┤     ├─────────────────┤
│ id              │     │ id              │     │ id              │
│ nama_unit       │     │ kode_klasifikasi│     │ nama_kategori   │
└────────┬────────┘     │ uraian          │     │ deskripsi       │
         │              │ retensi_aktif   │     └────────┬────────┘
         │              │ retensi_inaktif │              │
         │              │ status_akhir    │     ┌────────┴────────┐
         │              │ klasifikasi_    │     │  sub_kategori   │
         │              │ keamanan        │     ├─────────────────┤
         │              └────────┬────────┘     │ id              │
         │                       │              │ kategori_id     │
         ▼                       │              │ nama_sub_kategori│
┌─────────────────┐              │              └────────┬────────┘
│     users       │              │                       │
├─────────────────┤              │                       │
│ id              │              │                       │
│ name            │              │                       │
│ email           │              │                       │
│ unit_pengolah_id├──────────────┤                       │
│ verification_   │              │                       │
│ status          │              │                       │
└────────┬────────┘              │                       │
         │                       │                       │
         │              ┌────────┴────────┐              │
         │              │  berkas_arsip   │              │
         │              ├─────────────────┤              │
         │              │ nomor_berkas    │              │
         │              │ nama_berkas     │              │
         │              │ klasifikasi_id  ├──────────────┤
         │              │ unit_pengolah_id│              │
         │              │ retensi_aktif   │              │
         │              │ retensi_inaktif │              │
         │              │ penyusutan_akhir│              │
         │              │ lokasi_fisik    │              │
         │              └────────┬────────┘              │
         │                       │                       │
         │              ┌────────┴────────┐              │
         │              │  arsip_units    │              │
         │              ├─────────────────┤              │
         └──────────────┤ id_berkas       │              │
                        │ berkas_arsip_id │              │
                        │ kode_klasifikasi│──────────────┤
                        │ _id             │              │
                        │ unit_pengolah_  │              │
                        │ arsip_id        │              │
                        │ kategori_id     ├──────────────┘
                        │ sub_kategori_id │
                        │ status          │
                        │ verifikasi_oleh │
                        │ dokumen         │
                        │ ...             │
                        └─────────────────┘
```

### Deskripsi Tabel

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna sistem |
| `unit_pengolah` | Daftar unit/divisi dalam organisasi |
| `kode_klasifikasi` | Kode klasifikasi arsip sesuai standar |
| `kategori` | Kategori arsip (Arsip Aktif, dll) |
| `sub_kategori` | Sub kategori dari kategori |
| `berkas_arsip` | Folder/berkas untuk mengelompokkan arsip |
| `arsip_units` | Data arsip individual (naskah) |
| `roles` | Daftar role pengguna |
| `permissions` | Daftar permission |
| `model_has_roles` | Relasi user dengan role |

## Lisensi

Aplikasi ini dilindungi hak cipta. Penggunaan tanpa izin tidak diperbolehkan.

## Kontak

Untuk pertanyaan atau dukungan teknis, hubungi tim pengembang.
