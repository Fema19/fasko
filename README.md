## Fasilitas Sekolah – Booking & Laporan Kerusakan

Web app manajemen peminjaman fasilitas sekolah dengan alur approval, check-in/out, dan pelaporan kerusakan. Landing/dashboard sederhana memakai Tailwind + Laravel 12. (Gambar/screenshots bisa ditambahkan manual nanti.)

### Konsep
- Admin mengelola user/ruangan/fasilitas/kategori dan menyetujui booking.
- Guru PJ ruangan meng-approve booking untuk ruangannya, cek-in/out, dan reset riwayat manual.
- Guru non-PJ & Siswa mengajukan booking, check-in/out sendiri, dan laporan kerusakan pribadi (auto bersih).

### Fitur Utama
- Booking fasilitas: ajukan, edit, batalkan; auto-cancel bila stok habis/terlambat check-in.
- Approval berjenjang: Admin & Guru PJ approve/reject booking; check-in/out; history dengan filter + export PDF.
- History booking: reset manual (admin/guru PJ) dengan konfirmasi SweetAlert; user auto-clear 2 hari setelah selesai.
- Laporan kerusakan: buat laporan, upload foto, status pending/proses/selesai; export PDF & reset (admin/guru PJ); auto-clear laporan user setelah 7 hari.
- Role & akses: Admin, Guru (PJ/non-PJ), Siswa dengan batasan sesuai ruangan/fasilitasnya.
- Monitoring: dashboard ringkas, pencarian/filter dasar.

### Diagram
- ERD (Mermaid): `diagrams/erd.mmd`
- UML alur booking/check-in/out & auto reset: `diagrams/uml.mmd`
- Tambahkan gambar atau render mermaid sesuai kebutuhan (contoh: plugin VSCode atau GitHub mermaid).

### Teknologi
- Backend: Laravel 12, PHP 8.2+, Eloquent ORM.
- PDF: barryvdh/laravel-dompdf.
- Frontend: TailwindCSS 4 + Vite, Alpine (di layout admin).
- Dev tools: PHPUnit, Pint, Pail, Sail (opsional), Faker, Mockery, Collision.

### Prasyarat
- PHP ≥ 8.2, Composer.
- Node.js + npm.
- Database MySQL/MariaDB/PostgreSQL.

### Instalasi Cepat
```bash
git clone <repo-url>
cd fasko
composer install
npm install
cp .env.example .env
# set DB_*, MAIL_*, dll di .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run dev   # atau npm run build untuk produksi
php artisan serve
```

### Akun Uji (seed)
- Admin: `admin@example.com` / `admin123`
- Buat akun guru/siswa secara manual via UI/admin atau seeder tambahan.

### Perintah Berguna
- Jalankan test: `php artisan test`
- Lint format: `./vendor/bin/pint`
- Daftar route: `php artisan route:list`

### Catatan
- Export PDF tersedia di History Booking admin/guru PJ dan Laporan Kerusakan admin/guru PJ.
- Reset manual (dengan SweetAlert) hanya untuk admin/guru PJ; auto reset berjalan untuk user sesuai kebijakan di atas.
- Tambahkan gambar/ERD/UML sendiri pada folder `diagrams/` lalu tautkan di README jika diperlukan.
