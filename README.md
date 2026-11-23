# SIPA - Sistem Informasi Pengumpulan Tugas

[![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![Framework](https://img.shields.io/badge/Framework-Flight_PHP-00b4d8)](https://flightphp.com/)
[![Database](https://img.shields.io/badge/Database-SQLite_WAL-003B57?logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![UI](https://img.shields.io/badge/UI-Tabler_Core-206bc4?logo=bootstrap&logoColor=white)](https://tabler.io/)

Aplikasi web manajemen perkuliahan dan pengumpulan tugas mahasiswa berbasis kontainer (**Cloud-Native Containerized Application**) untuk mata kuliah **Komputasi Awan**, Program Studi **Teknologi Informasi**, **Universitas Sari Mulia (UNISM)**.

---

## ☁️ Korelasi dengan Komputasi Awan (Cloud Computing)

Projek ini menerapkan prinsip-prinsip modern komputasi awan:
1. **Containerization (Docker)**: Memenuhi prinsip *"Build Once, Run Anywhere"*. Dependensi aplikasi (Apache, PHP 8.2, modul SQLite, Opcache) terisolasi utuh dalam kontainer independen.
2. **PaaS & CaaS Ready (Render / Cloud Run)**: Skrip inisialisasi (`docker/entrypoint.sh`) mendukung *Dynamic Port Binding* (`PORT`) dari lingkungan cloud tanpa konfigurasi manual.
3. **The Twelve-Factor App Principles**: Pemisahan konfigurasi, port binding otonom, dan proses *stateless* yang cepat dinyalakan (*disposability*).
4. **Cloud Storage Segregation**: Direktori database (`database/`) dan berkas unggahan (`storage/uploads/`) dipisahkan dari web root agar siap dipetakan ke *Cloud Persistent Volume* atau *Object Storage* (AWS S3/GCS).

---

## 🛠️ Tech Stack & Arsitektur

- **Backend Runtime**: PHP 8.2 (Opcache enabled)
- **Web Server**: Apache 2.4 (VirtualHost, mod_rewrite, mod_headers)
- **Micro-Framework**: [Flight PHP](https://flightphp.com/) (v3.19+)
- **Database**: SQLite 3 via PDO (Foreign Keys ON, Journal Mode WAL untuk performa baca/tulis tinggi)
- **Frontend / UI**: [Tabler UI](https://tabler.io/) (Bootstrap 5 based) & Tabler Icons
- **Security Standard**: Standar OWASP Top 10 (CSRF Protection, SQL Injection Prevention via PDO Prepared Statements, Contextual Output Escaping, Magic MIME file validation, dan Anti Brute-Force Rate Limiting)

---

## 🚀 Fitur Utama

### 👨‍🏫 Panel Dosen
- **Dashboard Perkuliahan**: Statistik ringkasan pertemuan, tugas aktif, dan rasio pengumpulan tugas.
- **Manajemen Pertemuan**: Tambah jadwal dan unggah materi kuliah (PDF, PPT, DOCX).
- **Manajemen Tugas**: Atur batas waktu (dibuka pada & ditutup pada) secara presisi dengan status real-time.
- **Pemeriksaan & Penilaian**: Berikan nilai angka dan feedback catatan koreksi.
- **Unduh Tugas Massal (Bulk ZIP)**: Unduh seluruh tugas mahasiswa dalam 1 file ZIP terkompresi.
- **Rekap & Ekspor Nilai**: Ekspor rekapitulasi nilai satu semester ke format CSV/Excel.
- **Manajemen Mahasiswa**: Kelola data mahasiswa dan reset password jika lupa sandi.

### 🎓 Panel Mahasiswa
- **Timeline Perkuliahan**: Lihat jadwal pertemuan dan unduh materi kuliah.
- **Status Tugas Real-time**: Indikator otomatis (*Belum Dibuka*, *Terbuka*, *Ditutup*, *Sudah Dinilai*).
- **Pengumpulan Berkas**: Unggah berkas tugas dengan validasi ekstensi dan batas ukuran (20 MB).
- **Transparansi Nilai**: Pantau nilai dan catatan koreksi langsung dari dosen.
- **Profil & Keamanan**: Wajib ganti password awal pada login perdana dan edit avatar profil.

---

## 🐳 Menjalankan dengan Docker

### 1. Build Image Docker
```bash
docker build -t sipa-app:latest .
```

### 2. Jalankan Container
```bash
docker run -d -p 8080:80 --name sipa-container sipa-app:latest
```
Buka browser di `http://localhost:8080`.

---

## 💻 Menjalankan di Lingkungan Lokal (Laragon / Apache Native)

1. Pastikan ekstensi `pdo_sqlite`, `zip`, dan `fileinfo` aktif pada `php.ini`.
2. Arahkan DocumentRoot Apache ke folder `/public`.
3. Jalankan migrasi dan seeder awal:
   ```bash
   php scripts/reseed_all.php
   ```
4. Buka aplikasi melalui host lokal Anda (misal `http://sipa.test` atau `http://localhost/sipa/public`).

---

## 🔒 Standar Keamanan Sistem (OWASP Defense)

- **SQL Injection**: 100% menggunakan PDO Prepared Statements dengan parameter binding.
- **XSS (Cross-Site Scripting)**: Helper `e()` dengan `htmlspecialchars` pada seluruh render variabel template.
- **CSRF**: Verifikasi token kriptografis acak pada setiap request metode `POST`.
- **Upload Security**: Berkas tugas disimpan di luar direktori publik (`storage/uploads`), validasi MIME type menggunakan PHP `finfo`, dan obfuscated random filename.
- **Rate Limiting**: Maksimal 5 kali kegagalan login dalam 15 menit per NIM/IP sebelum diblokir sementara.
