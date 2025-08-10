# 📚 Proyek UAS: Integrasi Aplikasi (Sistem Tiket TixGo)

Selamat datang di repositori Proyek Ujian Akhir Semester (UAS) untuk mata kuliah Integrasi Aplikasi. Proyek ini mendemonstrasikan integrasi antara beberapa platform untuk membangun sebuah sistem pemesanan tiket yang fungsional dengan nama "TixGo".

Sistem ini terdiri dari tiga aplikasi terpisah yang saling terhubung melalui API:

- **Backend & Panel Admin** (Web - Laravel)

- **Frontend Customer** (Web - Laravel)

- **Aplikasi Mobile Customer** (Android - Flutter)

## 🏛️ Arsitektur Sistem

```
[ Aplikasi Mobile (Flutter) ] <---+
                                  |
                                 API
                                  |
[ Website Customer (Laravel) ] <--+---- [ Backend & API (Laravel) ] ----> [ Database ]
                                              ^
                                              |
                                     [ Admin & Service Provider ]
                                     (Mengelola data via web)
```


## 📂 Komponen Aplikasi

Berikut adalah penjelasan detail untuk setiap folder dalam repositori ini.

### 1. ``backendCRUD/`` (Backend & Panel Admin)

Ini adalah inti dari keseluruhan sistem. Aplikasi web ini dibangun menggunakan Laravel dan Tailwind CSS serta memiliki dua fungsi utama:

1. Menyediakan REST API: Sebagai sumber data terpusat untuk aplikasi web customer dan aplikasi mobile.

2. Web Portal: Menyediakan antarmuka web untuk dua peran pengguna:

- Admin: Mengelola seluruh sistem, termasuk data master dan pengguna.

- Service Provider: Mengelola data terkait layanan mereka (misalnya, event, tiket, dll).

### 2. ``frontendCustomer/`` (Website Customer)

Aplikasi web ini juga dibangun dengan Laravel dan Tailwind CSS. Tujuannya adalah sebagai platform bagi customer untuk melakukan aktivitas berikut:

- Melihat daftar event atau tiket yang tersedia.

- Melakukan pencarian dan pemesanan tiket.

- Melihat riwayat transaksi.

**Aplikasi ini sepenuhnya mengonsumsi data dari API yang disediakan oleh backendCRUD.**

### 3. ``tixgo_mobile/`` (Aplikasi Mobile Customer)

Ini adalah aplikasi mobile untuk platform **Android** yang dibangun menggunakan **Flutter**. Aplikasi ini memberikan pengalaman native kepada customer untuk melakukan fungsi yang sama seperti pada website customer, yaitu memesan tiket. Sama seperti web frontend, aplikasi ini juga terhubung ke API dari ``backendCRUD``.

## 🚀 Panduan Instalasi & Setup

Untuk menjalankan keseluruhan sistem, Anda perlu menjalankan ketiga aplikasi secara terpisah.

#### A. Setup backendCRUD

**1. Buka terminal baru, masuk ke direktori ``backendCRUD``:**
```
cd backendCRUD
```
**2. Instal dependensi Composer:**
```
composer install
```
**3. Salin file ``.env.example`` menjadi ``.env`` dan konfigurasikan koneksi database Anda.**
```
cp .env.example .env
```
**4. Generate kunci aplikasi:**
```
php artisan key:generate
```
**5. Jalankan migrasi dan seeder untuk membuat tabel dan data awal:**
```
php artisan migrate --seed
```
**6. Instal dependensi NPM:**
```
npm install
```
**7. Jalankan server pengembangan (catat alamat IP dan port-nya, misal: ``127.0.0.1:8000)``:**
```
npm run dev & php artisan serve
```

#### B. Setup ``frontendCustomer``

**1. Buka terminal baru, masuk ke direktori ``frontendCustomer``:**
```
cd frontendCustomer
```
**2. Instal dependensi Composer:**
```
composer install
```
**3. Salin file ``.env.example`` menjadi ``.env`` dan konfigurasikan koneksi database Anda.**
```
cp .env.example .env
```
**4. PENTING: Buka file ``.env`` dan arahkan endpoint API ke alamat server ``backendCRUD`` yang sedang berjalan.**
```
API_BASE_URL=http://127.0.0.1:8000/api
```
**5. Jalankan server pengembangan di port yang berbeda:**
```
php artisan serve --port=8001
```

#### C. Setup ``tixgo_mobile`` (Flutter)
**1. Pastikan Anda sudah menginstal Flutter SDK.**
**2. Buka terminal baru, masuk ke direktori ``tixgo_mobile``:**
```
cd tixgo_mobile
```
**3. Instal dependensi Flutter:**
```
flutter pub get
```
**4. PENTING: Buka file konfigurasi API di dalam proyek Flutter (misalnya di ``lib/config/api.dart`` atau sejenisnya). Ubah alamat IP base URL ke alamat IP jaringan lokal dari server ``backendCRUD` Anda (jangan gunakan ``localhost`` atau ``127.0.0.1`` jika mengetes di HP fisik).**

```
// Contoh di dalam file config Flutter
class ApiConfig {
  static const String baseUrl = 'http://192.168.1.10:8000/api'; // Gunakan IP jaringan Anda
}
```
**5. Jalankan aplikasi di emulator atau perangkat fisik:**
```
flutter run
```

_Sekarang, ketiga bagian dari sistem seharusnya sudah berjalan dan saling terintegrasi._
