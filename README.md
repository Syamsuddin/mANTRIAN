# mANTRIAN

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://img.shields.io/badge/build-pending-lightgrey.svg)](#)

Deskripsi
--
mANTRIAN adalah aplikasi sistem antrian berbasis web yang dirancang untuk memudahkan pengelolaan layanan, loket, dan alur pemanggilan pelanggan. Aplikasi ini dibangun dengan Laravel untuk backend dan Tailwind/Vite untuk antarmuka modern. Tema default telah diperbarui menjadi palet light-blue yang bersih dan profesional.

Fitur Utama
--
- Manajemen layanan (create / edit / disable)
- Pengaturan loket dan assignment operator
- Kiosk untuk pengambilan tiket oleh pengunjung
- Display panggilan real-time untuk layar publik
- Console operator untuk panggilan/ulang/skip/selesai
- Laporan rekap per-hari dan export sederhana
- Audit log aktivitas (tracking perubahan)

Screenshot
--
- Silakan jalankan aplikasi secara lokal untuk melihat tampilan baru pada `kiosk`, `display`, dan `operator`.

Persyaratan Sistem
--
- PHP >= 8.1
- Composer
- Node.js >= 18 dan npm
- MySQL / MariaDB atau database lain yang didukung Laravel

Instalasi (pengembang)
--
1. Clone repo

```bash
git clone https://github.com/Syamsuddin/mANTRIAN.git
cd mANTRIAN
```

2. Pasang dependensi PHP dan Node

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build   # atau `npm run dev` untuk pengembangan
```

3. Konfigurasi database

Edit file `.env` untuk mengatur koneksi database, lalu jalankan migrasi dan seed (opsional):

```bash
php artisan migrate --seed
```

Menjalankan Aplikasi
--
```bash
php artisan serve
# akses: http://127.0.0.1:8000
```

Pengembangan Frontend
--
- Untuk pengembangan dengan hot reload:

```bash
npm run dev
```

Catatan Tema
--
Saya telah mengaplikasikan tema `light-blue` modern pada `resources/css/app.css` dan menyesuaikan beberapa view utama (`layouts`, `kiosk`, `display`, `operator`, `admin`) agar tampilan lebih konsisten.

Kontribusi
--
Kontribusi sangat disambut. Silakan buka issue untuk diskusi fitur atau bug, dan kirimkan pull request yang berisi perubahan terpisah dan deskriptif.

Lisensi
--
Proyek ini dilisensikan di bawah MIT License — lihat file `LICENSE`.

Kontak
--
Untuk pertanyaan atau bantuan, hubungi pemilik repo.
