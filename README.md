# SIM-KKN Online Farmasi UMMADA Cirebon

Sistem Informasi Manajemen Kuliah Kerja Nyata (SIM-KKN) Online merupakan platform berbasis web untuk mengelola administrasi, pendaftaran, pembimbingan, penempatan lokasi, logbook harian (LKH), dan penilaian kegiatan KKN di Universitas Muhammadiyah Ahmad Dahlan (UMMADA) Cirebon.

---

## 🛠️ Tech Stack

- **Backend Framework**: PHP CodeIgniter 3.x (Dioptimalkan kompatibel dengan PHP 8.1+)
- **Database**: MySQL / MariaDB
- **Frontend UI**: Bootstrap 5 (Mazer Admin Template)
- **Javascript Library**: jQuery, DataTables, SweetAlert2, Select2, Bootstrap Material DatePicker
- **Styling**: Vanilla CSS (kustomisasi melalui `assets/myapp.css`)

---

## 📂 Struktur Direktori Utama

Berikut penjelasan singkat mengenai folder dan file penting dalam proyek ini:

```text
├── application/                # Kode inti aplikasi (MVC)
│   ├── config/                 # Konfigurasi aplikasi (database, routes, config, dll)
│   ├── controllers/            # Controller Backend
│   │   ├── app/                # Fitur administrasi admin (jadwal, lokasi, DPL, evaluasi)
│   │   ├── mahasiswa/          # Halaman portal mahasiswa KKN
│   │   ├── pembimbing/         # Halaman portal Dosen Pembimbing Lapangan (DPL)
│   │   ├── master/             # Master data wilayah (wilayah desa, kecamatan, dll)
│   │   ├── Login.php           # Controller otentikasi login
│   │   └── Daftar.php          # Controller pendaftaran peserta
│   ├── helpers/                # Fungsi bantuan kustom (web_helper.php, dll)
│   ├── models/                 # Model data database (Model_data.php)
│   └── views/                  # File template antarmuka user (HTML/PHP)
│       ├── app/                # Tampilan portal admin & DPL
│       ├── mahasiswa/          # Tampilan portal mahasiswa
│       ├── pembimbing/         # Tampilan portal Dosen Pembimbing Lapangan (DPL)
│       ├── auth.php            # Kerangka halaman login/register
│       └── index.php           # Kerangka utama portal
│
├── assets/                     # File statis frontend
│   ├── css/                    # CSS Tambahan
│   ├── js/                     # JS Tambahan
│   ├── plugins/                # Plugin eksternal (jQuery, SweetAlert, Select2)
│   ├── web/                    # Script logika JS per halaman (lokasi.js, pembimbing.js, dll)
│   ├── myapp.css               # File override stylesheet kustom (Warna & Font)
│   └── myapp.js                # Inisialisasi API AJAX global
│
├── db/                         # Backup data SQL database
│
└── templates/
    └── mazer/                  # Assets default template Mazer Bootstrap 5
```

---

## 🚀 Panduan Instalasi Lokal (Laragon)

Ikuti langkah-langkah di bawah ini untuk menjalankan sistem di lingkungan pengembangan lokal:

### 1. Salin Proyek ke Direktori Laragon
Pindahkan folder project ini ke dalam direktori `C:\laragon\www\KKN-Online-Farmasi-UMMADA-Cirebon`.

### 2. Impor Database
1. Buka database manager Anda (HeidiSQL/phpMyAdmin).
2. Buat database baru dengan nama, misalnya, `kkn_reb`.
3. Impor dump sql terbaru yang ada di dalam folder `db/` ke database tersebut.

### 3. Konfigurasi Database Aplikasi
Sesuaikan kredensial koneksi database di file [database.php](file:///c:/laragon/www/KKN-Online-Farmasi-UMMADA-Cirebon/application/config/database.php):
```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',        // User MySQL lokal Anda
    'password' => '',            // Password MySQL lokal Anda
    'database' => 'kkn_reb',     // Nama database lokal Anda
    'dbdriver' => 'mysqli',
    // ...
);
```

### 4. Konfigurasi Base URL
Sesuaikan URL dasar web Anda di file [config.php](file:///c:/laragon/www/KKN-Online-Farmasi-UMMADA-Cirebon/application/config/config.php):
```php
$config['base_url'] = 'http://localhost/KKN-Online-Farmasi-UMMADA-Cirebon/';
```
*Catatan: Jika Anda menggunakan auto-vhost Laragon, URL-nya mungkin berupa `http://kkn-online-farmasi-ummada-cirebon.test/`.*

---

## 💡 Kustomisasi & Modifikasi Penting

Berikut adalah rangkuman penyesuaian khusus yang telah diimplementasikan agar web berjalan optimal dan fleksibel:

### 1. Kompatibilitas CORS & Multi-Domain (Lokal/Staging/Prod)
Untuk mencegah error AJAX saat menguji aplikasi di localhost atau subdomain staging, URL dasar API diinisialisasi secara dinamis di file [myapp.js](file:///c:/laragon/www/KKN-Online-Farmasi-UMMADA-Cirebon/assets/myapp.js):
```javascript
var vBase_url = typeof vBase_url !== 'undefined' ? vBase_url : "https://kkn.ummada.ac.id/";
```

### 2. Penanganan Error PHP 8.1+ (Callback Validation)
CodeIgniter 3 sebelumnya akan memicu warning/deprecation error `preg_match(): Passing null to parameter #2 ($subject) of type string is deprecated` ketika field post kosong dikirim ke callback form validation (seperti `alphacostum`). Masalah ini diatasi dengan melakukan casting parameter input menjadi `string` di seluruh controller:
```php
public function alphacostum($string)
{
    $string = (string) $string; // Casting untuk mencegah warning PHP 8.1+
    // ...
}
```

### 3. Sistem Pendaftaran Otomatis Modul Database (Self-Healing)
Pada modul Evaluasi (`/app/evaluasi`), ditambahkan mekanisme *self-healing* di constructor controller [Evaluasi.php](file:///c:/laragon/www/KKN-Online-Farmasi-UMMADA-Cirebon/application/controllers/app/Evaluasi.php). Jika modul belum terdaftar di tabel `module` atau hak akses admin belum ada di `aksesgrup`, sistem secara otomatis akan mendaftarkannya saat halaman diakses pertama kali, mencegah terjadinya redirect loop ke halaman login/luar.

### 4. Kustomisasi Tema CSS (Teal Theme & Poppins Font)
Kustomisasi tampilan dilakukan secara aman dengan teknik *override* di file [myapp.css](file:///c:/laragon/www/KKN-Online-Farmasi-UMMADA-Cirebon/assets/myapp.css) tanpa merusak script inti Bootstrap 5:
- **Font**: Memuat font **Poppins** dari Google Fonts dan menerapkannya secara global.
- **Warna Utama**: Mengubah warna utama bawaan Mazer (biru `#435ebe`) menjadi hijau toska/teal (**`#03a49b`** dengan hover warna **`#028079`**) pada komponen tombol, link, sidebar active, checkbox, progress bar, dan pagination.

### 5. Fitur Baru & Peningkatan Pengalaman Pengguna (UX)

Beberapa fitur canggih dan peningkatan performa sistem yang baru saja ditambahkan meliputi:
- **Upload Paralel & Kompresi Gambar Logbook**: Mahasiswa dapat mengunggah beberapa dokumentasi sekaligus pada logbook. Gambar dikompres secara otomatis (kualitas `70%`, lebar/tinggi maks `1200px`) di backend menggunakan library image manipulation GD2 untuk menghemat ruang penyimpanan server.
- **Tampilan Galeri Logbook Dinamis**: Lampiran gambar diurutkan secara estetik (gambar pertama tampil penuh di atas, dan gambar berikutnya tampil sebagai deretan thumbnail kecil di bawahnya) lengkap dengan tombol hapus lampiran langsung.
- **Preview Modal Bootstrap**: Mengganti perilaku pembukaan tab baru (`window.open`) dengan jendela modal dialog Bootstrap terpadu untuk pratinjau gambar secara langsung di halaman dashboard dan feed logbook.
- **News Carousel Sliding Otomatis**: Widget "Berita Terbaru" di halaman depan dikonversi menjadi Bootstrap Carousel dengan transisi slide otomatis setiap 3 detik dan tombol kontrol melayang (*floating teal circle arrow*).
- **Galeri Kegiatan 7 Kolom & Infinite Scroll**: Mengganti menu "Bantuan" dengan halaman "Galeri Kegiatan" yang menampilkan feed foto logbook berbentuk 7 kolom horizontal di desktop (mirip layout Instagram Web) dan memuat foto tambahan secara otomatis ke bawah (*infinite scroll* via AJAX) saat pengguna men-scroll halaman.
- **Validasi dan Decoding NIK Aman**: Pendaftaran akun secara otomatis mengekstrak tanggal lahir (`tgllahir`) dan jenis kelamin (`kel`) berdasarkan parsing NIK. Validasi regex ditambahkan untuk mensterilkan input NIK dari karakter berbahaya, dan ditambahkan batas toleransi threshold tahun lahir `- 15` tahun untuk mencegah kesalahan penafsiran abad (misalnya tahun `99` diterjemahkan sebagai `1999` bukan `2099`).
- **Mekanisme Auto-Upload Otomatis**: Menyembunyikan tombol manual "Mulai upload" dan "Batal upload" di form file dropzone, sehingga upload otomatis berjalan instan setelah file dipilih.
