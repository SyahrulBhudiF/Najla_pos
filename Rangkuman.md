# Rangkuman Perbaikan Project Najla_pos

Dokumen ini berisi catatan perbaikan yang dilakukan pada project Laravel POS.

---

## 1. Asset AdminLTE Belum Di-publish

### Sebelumnya
Package `jeroennoten/laravel-adminlte` sudah ditambahkan di `composer.json` dan berhasil di-install lewat Composer. Tapi waktu dibuka di browser, tampilan AdminLTE-nya tidak muncul — CSS dan JS-nya tidak ke-load.

Ini karena file-file asset (CSS, JS, gambar) masih "terkurung" di dalam folder `vendor/` yang memang **tidak bisa diakses oleh browser**. Supaya bisa diakses, file-file itu harus di-copy dulu ke folder `public/`.

### Solusinya
Cukup jalankan:
```bash
php artisan adminlte:install
```
Command ini otomatis meng-copy semua asset yang dibutuhkan ke `public/vendor/adminlte/`, `public/vendor/jquery/`, dll.

Untuk mengecek apakah asset sudah ter-publish atau belum:
```bash
php artisan adminlte:status
```

### Catatan
> Di Laravel, folder `public/` adalah satu-satunya folder yang bisa diakses browser. Jadi kalau install package yang punya file CSS/JS/gambar, jangan lupa **publish asset-nya** supaya browser bisa memuatnya.

---

## 2. Package Yajra DataTables Belum Ter-install

### Sebelumnya
Di `UserController.php`, sudah ada kode yang menggunakan Yajra DataTables:
```php
use Yajra\DataTables\Facades\DataTables;

return DataTables::of($users)->make(true);
```
Tapi package-nya sendiri belum ditambahkan ke project. Jadi kalau halaman `/user` dibuka, Laravel akan bingung karena class `DataTables` tidak ditemukan.

### Solusinya
Install package-nya lewat Composer. Karena project ini pakai **Laravel 12**, maka versi yang kompatibel adalah `^12.0`:
```bash
composer require yajra/laravel-datatables-oracle:^12.0 -W
```
Flag `-W` artinya Composer boleh meng-update dependency lain kalau diperlukan supaya tidak bentrok.

Selain itu, query di controller juga diperbaiki supaya **relasi `level` ikut di-load sekaligus** (tidak satu-satu per baris):
```php
// Sebelumnya
$users = UserModel::select('user_id', 'username', 'nama', 'level_id');

// Sesudahnya — tambah with('level')
$users = UserModel::query()
    ->with('level')
    ->select('user_id', 'username', 'nama', 'level_id');
```
Ini penting karena di tabel ada kolom "Level Pengguna" yang datanya diambil dari relasi. Tanpa `with('level')`, Laravel akan melakukan query tambahan **untuk setiap baris** — ini disebut **N+1 problem** dan bisa bikin lambat kalau datanya banyak.

### Catatan
> Kalau di controller sudah ada `use NamaPackage\...`, pastikan package-nya sudah ter-install. Cek dengan `composer show nama/package`. Kalau belum ada, tambahkan dengan `composer require`.

---

## 3. Path Asset di Template Tidak Sesuai

### Sebelumnya
Di file `template.blade.php`, semua CSS dan JS di-load dari path seperti ini:
```html
<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
```
Tapi setelah menjalankan `php artisan adminlte:install`, file-filenya justru ter-publish ke lokasi yang berbeda, yaitu `public/vendor/...` — bukan `public/adminlte/...`. Jadi browser tidak bisa menemukan file-nya dan muncul error:
```
Loading failed for the <script> with source
"http://127.0.0.1:8000/adminlte/plugins/jquery/jquery.min.js"
```

Selain itu, beberapa plugin seperti **DataTables, pdfmake, dan jszip** memang tidak ikut ter-publish sama sekali karena bukan bagian dari distribusi AdminLTE yang di-publish.

### Solusinya
**File `resources/views/layouts/template.blade.php`** — semua path diperbaiki:

| Sebelumnya | Sesudahnya |
|---|---|
| `asset('adminlte/plugins/jquery/...')` | `asset('vendor/jquery/...')` |
| `asset('adminlte/plugins/bootstrap/...')` | `asset('vendor/bootstrap/...')` |
| `asset('adminlte/plugins/fontawesome-free/...')` | `asset('vendor/fontawesome-free/...')` |
| `asset('adminlte/dist/...')` | `asset('vendor/adminlte/dist/...')` |
| `asset('adminlte/plugins/datatables/...')` | Pakai **CDN** dari `cdn.datatables.net` |

**File `config/adminlte.php`** — plugin DataTables diaktifkan (`'active' => true`) dan link CDN di-update ke versi terbaru. Ini supaya halaman yang pakai `@extends('adminlte::page')` juga otomatis me-load DataTables.

### Catatan
> Helper `asset()` di Laravel menghasilkan URL ke folder `public/`. Jadi `asset('vendor/jquery/...')` artinya file-nya ada di `public/vendor/jquery/...`. Kalau path-nya tidak cocok dengan lokasi file yang sebenarnya, browser akan gagal me-load-nya. Untuk library yang tidak ada di lokal, bisa pakai **CDN** (link dari internet).

---

## 4. Route dan Form Action Tidak Cocok

### Sebelumnya — DataTables POST vs GET
Di `index.blade.php`, DataTables mengirim request **POST** ke `/user/list`:
```js
ajax: { url: "...", dataType: "json", type: "POST" }
```
Tapi di `routes/web.php`, route `/user/list` hanya menerima **GET**. Ini menyebabkan error 405 (Method Not Allowed).

### Solusinya
Karena mengambil data itu sifatnya **membaca** (bukan mengubah), best practice-nya pakai **GET**. Jadi yang diubah adalah sisi JavaScript-nya — `type: "POST"` dihapus (default DataTables sudah GET):
```js
ajax: { url: "{{ route('user.list') }}", dataType: "json" }
```
Route-nya juga ditambahkan **nama** supaya lebih aman kalau URL-nya berubah di kemudian hari:
```php
Route::get('/list', [UserController::class, 'list'])->name('user.list');
```

---

### Sebelumnya — Form Tambah User
Di `user_tambah.blade.php`, form mengarah ke URL yang tidak ada:
```html
<form method="post" action="/user/tambah_simpan">
```
Padahal route yang tersedia untuk menyimpan user baru adalah `POST /user` → `UserController@store`.

### Solusinya
Ganti action form supaya sesuai dengan route yang ada:
```html
<form method="post" action="{{ url('/user') }}">
```

### Catatan
> Sebelum menulis `action` di form atau URL di ajax, cek dulu route yang tersedia dengan `php artisan route:list`. Gunakan helper `url()` atau `route()` supaya URL mengikuti konfigurasi Laravel — jangan di-hardcode manual.

---

## 5. DatabaseSeeder Belum Memanggil Seeder

### Sebelumnya
Sudah ada 7 file seeder yang siap digunakan (LevelSeeder, KategoriSeeder, UserSeeder, dll.), tapi `DatabaseSeeder.php` — yang merupakan "pintu masuk" saat menjalankan `php artisan db:seed` — **isinya masih kosong**. Jadi tidak ada data yang masuk ke database.

### Solusinya
Daftarkan semua seeder di `DatabaseSeeder.php` dengan **urutan yang benar** — tabel induk harus di-seed duluan:
```php
public function run(): void
{
    $this->call([
        LevelSeeder::class,            // 1. Level dulu (dirujuk oleh User)
        KategoriSeeder::class,          // 2. Kategori (dirujuk oleh Barang)
        UserSeeder::class,              // 3. User (butuh Level)
        BarangSeeder::class,            // 4. Barang (butuh Kategori)
        PenjualanSeeder::class,         // 5. Penjualan (butuh User)
        DetailPenjualanSeeder::class,   // 6. Detail (butuh Penjualan + Barang)
        StokSeeder::class,              // 7. Stok (butuh Barang + User)
    ]);
}
```

### Catatan
> Urutan seeder itu penting! Tabel yang menjadi "induk" (yang kolom primary key-nya dirujuk tabel lain) harus diisi duluan. Contoh: `m_level` harus ada isinya sebelum `m_user`, karena `m_user` punya kolom `level_id` yang merujuk ke `m_level`.

---

## Daftar File yang Diubah

| File | Apa yang Diperbaiki |
|---|---|
| `composer.json` | Tambah package `yajra/laravel-datatables-oracle: ^12.0` |
| `config/adminlte.php` | Aktifkan plugin DataTables + update link CDN |
| `routes/web.php` | Tambah named route `user.list` |
| `resources/views/layouts/template.blade.php` | Perbaiki semua path asset + pakai CDN untuk DataTables |
| `resources/views/index.blade.php` | Ubah ajax dari POST ke GET + pakai `route()` |
| `resources/views/user_tambah.blade.php` | Perbaiki form action ke `url('/user')` |
| `app/Http/Controllers/UserController.php` | Tambah `with('level')` untuk eager loading |
| `database/seeders/DatabaseSeeder.php` | Daftarkan semua 7 seeder dengan urutan yang benar |

---

## Command yang Berguna

```bash
# Hapus cache konfigurasi (wajib setelah ubah .env)
php artisan config:clear

# Publish asset AdminLTE
php artisan adminlte:install

# Cek status asset AdminLTE
php artisan adminlte:status

# Install package baru via Composer
composer require yajra/laravel-datatables-oracle:^12.0 -W

# Jalankan migration (buat tabel)
php artisan migrate

# Jalankan semua seeder (isi data awal)
php artisan db:seed

# Lihat semua route yang tersedia
php artisan route:list
```
