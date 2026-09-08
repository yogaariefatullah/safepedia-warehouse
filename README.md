# Safepedia Warehouse

## 1. Deskripsi

**Safepedia Warehouse** merupakan aplikasi untuk mengelola pengajuan kebutuhan warehouse secara terstruktur, mulai dari pembuatan pengajuan, pengunggahan dokumen pendukung, proses approval berjenjang, hingga pencatatan histori approval.

Aplikasi dibangun menggunakan **Laravel** dengan database relasional dan menerapkan mekanisme approval berdasarkan role dan level approval.

---

## 2. Fitur Utama

Fitur utama aplikasi meliputi:

- Login dan autentikasi pengguna.
- Pengajuan kebutuhan warehouse.
- Pengelolaan data pengajuan.
- Upload dan pengelolaan dokumen pendukung.
- Approval pengajuan secara berjenjang.
- Reject pengajuan dengan alasan penolakan.
- Pencatatan histori setiap proses approval.
- Monitoring status pengajuan.
- Pembatasan proses approval berdasarkan role dan urutan level approval.

---

## 3. Alur Approval

Approval pada aplikasi menggunakan mekanisme **berjenjang berdasarkan role**.

Alur secara umum:

```text
Pengajuan dibuat
      │
      ▼
Approval Level 1
      │
      ├── Reject ──► Pengajuan ditolak
      │
      ▼
Approval Level 2
      │
      ├── Reject ──► Pengajuan ditolak
      │
      ▼
Approval Level berikutnya
      │
      ▼
Semua level approve
      │
      ▼
Pengajuan disetujui
```

Approver pada level berikutnya **tidak dapat melakukan approval sebelum level sebelumnya menyelesaikan proses approval**.

Setiap approver dapat:

- **Approve** apabila data pengajuan sesuai.
- **Reject** apabila terdapat ketidaksesuaian data, budget, dokumen, lokasi, atau kebutuhan lainnya.

Pada saat melakukan reject, **alasan penolakan wajib diisi** agar dapat digunakan sebagai informasi dan histori pengajuan.

---

## 4. Struktur Model

Model utama yang digunakan dalam aplikasi:

```text
app/
└── Models/
    ├── WarehouseRequest.php
    ├── ApprovalLevel.php
    ├── WarehouseDocument.php
    └── ApprovalHistory.php
```

### WarehouseRequest

Digunakan untuk menyimpan data utama pengajuan warehouse.

Relasi utamanya berkaitan dengan:

- User/pengaju
- Dokumen pengajuan
- Approval history

### ApprovalLevel

Digunakan untuk menentukan level approval berdasarkan role pengguna.

Contohnya:

```text
Role A → Level 1
Role B → Level 2
Role C → Level 3
```

Urutan level menentukan siapa yang berhak memproses pengajuan berikutnya.

### WarehouseDocument

Digunakan untuk menyimpan dokumen pendukung yang berkaitan dengan pengajuan warehouse.

### ApprovalHistory

Digunakan untuk mencatat histori proses approval.

Informasi yang dapat dicatat antara lain:

- Pengajuan
- Approver
- Level approval
- Status approval
- Catatan/alasan reject
- Waktu proses

---

# 5. Requirement

Sebelum menjalankan aplikasi, pastikan environment sudah memiliki:

- PHP sesuai versi Laravel yang digunakan.
- Composer.
- Node.js dan NPM.
- Database MySQL/MariaDB.
- Git.
- Web server lokal seperti Laragon, XAMPP, atau Laravel Artisan Server.

Versi PHP yang digunakan sebaiknya disesuaikan dengan requirement pada `composer.json`.

Untuk mengecek versi:

```bash
php -v
composer -V
node -v
npm -v
```

---

# 6. Instalasi

Clone repository:

```bash
git clone <repository-url>
```

Masuk ke folder project:

```bash
cd <nama-folder-project>
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

---

# 7. Konfigurasi Environment

Copy file `.env.example` menjadi `.env`.

Linux/macOS:

```bash
cp .env.example .env
```

Windows:

```bash
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Kemudian sesuaikan konfigurasi database pada `.env`.

Contoh:

```env
APP_NAME=Safepedia
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=safepedia
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nilai database dengan konfigurasi pada environment masing-masing.

---

# 8. Database

Buat database baru sesuai nama yang digunakan pada `.env`.

Contoh:

```sql
CREATE DATABASE safepedia;
```

Kemudian jalankan migration:

```bash
php artisan migrate
```

Jika project memiliki seeder:

```bash
php artisan db:seed
```

Atau sekaligus melakukan migration dan seeding:

```bash
php artisan migrate --seed
```

> Gunakan `migrate:fresh --seed` hanya pada environment development/testing karena perintah tersebut akan menghapus tabel dan data yang sudah ada.

---

# 9. Storage

Jika aplikasi menggunakan file upload untuk dokumen warehouse, buat symbolic link storage:

```bash
php artisan storage:link
```

Pastikan direktori storage memiliki permission yang sesuai.

Pada Linux:

```bash
chmod -R 775 storage bootstrap/cache
```

Jika aplikasi dijalankan menggunakan web server, pastikan user web server memiliki akses terhadap direktori tersebut.

---

# 10. Menjalankan Aplikasi

### Menjalankan Laravel

Gunakan:

```bash
php artisan serve
```

Secara default aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

### Menjalankan Frontend

Untuk development:

```bash
npm run dev
```

Jika menggunakan Vite, proses tersebut perlu tetap berjalan selama development apabila asset frontend membutuhkan Vite development server.

Untuk production:

```bash
npm run build
```

---

# 11. Urutan Menjalankan dari Awal

Untuk setup baru, urutan yang direkomendasikan:

```bash
git clone <repository-url>
cd <project-folder>

composer install
npm install

cp .env.example .env

php artisan key:generate

# Konfigurasi database pada .env

php artisan migrate --seed

php artisan storage:link

npm run build

php artisan serve
```

Kemudian buka:

```text
http://127.0.0.1:8000
```

---

# 12. Penggunaan Aplikasi

## Login

User melakukan login menggunakan akun yang telah tersedia.

Setelah berhasil login, sistem akan menampilkan halaman sesuai dengan hak akses dan role pengguna.

## Membuat Pengajuan

Pengaju dapat membuat pengajuan warehouse dengan mengisi data yang diperlukan serta melampirkan dokumen pendukung.

Setelah pengajuan disimpan, status pengajuan akan masuk ke tahap approval.

## Proses Approval

Approver membuka daftar pengajuan yang menjadi tanggung jawabnya.

Sistem akan melakukan pengecekan terhadap:

1. Role pengguna.
2. Level approval pengguna.
3. Status approval level sebelumnya.
4. Status pengajuan.

Jika seluruh kondisi terpenuhi, approver dapat melakukan approval.

## Reject

Jika pengajuan tidak sesuai, approver dapat melakukan reject.

Alasan reject wajib diberikan, misalnya:

```text
Dokumen pendukung belum lengkap.
```

atau:

```text
Data budget tidak sesuai dengan pengajuan.
```

Alasan tersebut disimpan pada histori approval.

---

# 13. Status Pengajuan

Status pengajuan secara umum mengikuti proses:

```text
Draft
  │
  ▼
Submitted
  │
  ▼
Waiting Approval
  │
  ├── Reject ──► Rejected
  │
  ▼
Approved
```

Status aktual dapat menyesuaikan implementasi pada database dan business logic aplikasi.

---

# 14. Validasi Approval

Sistem menerapkan validasi untuk mencegah approval dilakukan oleh user yang tidak memiliki hak akses.

Konsep pengecekan approval:

```text
User Login
    │
    ▼
Cari ApprovalLevel berdasarkan role_id
    │
    ├── Tidak ditemukan
    │       └── User tidak memiliki level approval
    │
    ▼
Cek level approval pengajuan
    │
    ▼
Cek apakah level sebelumnya sudah approve
    │
    ├── Belum
    │    └── Approval tidak dapat dilakukan
    │
    ▼
Proses Approve / Reject
    │
    ▼
Simpan ApprovalHistory
```

Dengan mekanisme tersebut, approver tidak dapat melewati urutan approval yang telah ditentukan.

---

# 15. Troubleshooting

### Composer dependency error

Jalankan:

```bash
composer install
```

Jika dependency berubah:

```bash
composer update
```

Gunakan `composer update` dengan hati-hati pada environment production karena dapat memperbarui versi dependency.

### `.env` tidak terbaca

Jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Storage tidak dapat diakses

Jalankan:

```bash
php artisan storage:link
```

Kemudian cek permission:

```bash
chmod -R 775 storage bootstrap/cache
```

### Database table belum tersedia

Jalankan:

```bash
php artisan migrate
```

Jika membutuhkan data awal:

```bash
php artisan db:seed
```

### Error karena cache/session table

Pastikan konfigurasi `.env` sesuai dengan driver yang digunakan dan tabel yang diperlukan sudah tersedia.

Kemudian:

```bash
php artisan optimize:clear
```

### Perubahan kode tidak terlihat

Jalankan:

```bash
php artisan optimize:clear
```

Untuk asset frontend:

```bash
npm run build
```

---

# 16. Struktur Direktori Umum

Struktur utama project Laravel:

```text
safepedia/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   └── Models/
│       ├── WarehouseRequest.php
│       ├── ApprovalLevel.php
│       ├── WarehouseDocument.php
│       └── ApprovalHistory.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── resources/
│   ├── views/
│   └── js/
│
├── routes/
│   └── web.php
│
├── storage/
│
├── public/
│
├── .env
├── composer.json
├── package.json
└── artisan
```

---

# 17. Kesimpulan

Safepedia Warehouse menyediakan proses pengajuan dan approval warehouse secara terstruktur dengan menerapkan **approval berjenjang berdasarkan role**.

Setiap proses approval dicatat melalui `ApprovalHistory`, sedangkan `ApprovalLevel` digunakan untuk menentukan urutan dan hak akses approver. Mekanisme tersebut memastikan proses approval berjalan sesuai workflow yang telah ditentukan serta menyediakan histori yang dapat digunakan untuk monitoring dan audit.

Untuk menjalankan aplikasi pada environment baru, cukup melakukan instalasi dependency, konfigurasi `.env`, menyiapkan database, menjalankan migration/seeder, mengatur storage, melakukan build asset, kemudian menjalankan Laravel.
