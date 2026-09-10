# DeSiWeM (Digital School Information & Wall Magazine)

> Platform Mading Digital Modern berbasis **Laravel 11** dengan antarmuka editorial berkelas tinggi (*Lexington Carbon Design*), sistem pelaporan analitik interaktif, dan sidebar panel admin terinspirasi estetika WhatsApp Web.

---

## 📌 Gambaran Proyek

**DeSiWeM** dirancang sebagai solusi portal informasi mading kampus/sekolah modern yang memadukan pengalaman membaca artikel publik yang dinamis dengan panel administrasi editorial yang efisien, tanpa "AI slop", beranimasi halus, dan responsif.

---

## ✨ Fitur Unggulan

### 1. Halaman Publik (Public Portal)
- **Tampilan Editorial Modern**: Menggunakan palet warna hangat (*Warm Terracotta* dan *Charcoal Neutrals*) dengan tipografi bersih.
- **Kategori & Tag Interaktif**: Memudahkan navigasi berdasarkan rubrik (Prestasi, Teknologi, Seni & Budaya, dll.).
- **Desain Responsif**: Menu drawer layar penuh khusus mobile dengan transisi mulus.
- **Pembaca Artikel Nyaman**: Tata letak tipografi terukur dengan visual gambar berkualitas tinggi.

### 2. Panel Admin (Admin Dashboard)
- **WhatsApp Web-Themed Sidebar**: Navigasi sidebar beranimasi dengan icon badge vektor, *sliding indicator bar*, dan kartu profil sesi aktif.
- **Analitik Visual Presisi**: Diagram batang bulanan (Chart.js) dan donut ring distribusi kategori dengan palet carbon solid.
- **Manajemen Konten Lengkap**:
  - CRUD Artikel (Buat, Edit, Hapus, Upload Gambar).
  - Manajemen Kategori & Rubrik.
  - Cetak Laporan Administrasi.

---

## 🛠️ Stack Teknologi

- **Backend**: [Laravel 11](https://laravel.com) (PHP 8.2+)
- **Database**: SQLite / MySQL
- **Frontend**: Blade Templating, Vanilla CSS (Design Tokens OKLCH), Bootstrap 5.3 & Bootstrap Icons
- **Visualisasi**: Chart.js

---

## 🚀 Panduan Instalasi Lokal

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM (opsional untuk asset tooling)

### Langkah Setup
1. **Clone Repositori**:
   ```bash
   git clone https://github.com/Alarave/desiwem-mading.git
   cd desiwem-mading
   ```

2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeder**:
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Buka peramban di `http://localhost:8000`.

---

## 🧪 Pengujian (Tests)

Jalankan rangkaian automated tests:
```bash
php artisan test
```

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
