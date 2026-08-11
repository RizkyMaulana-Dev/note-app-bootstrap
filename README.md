# 📝 Scribble Notes - Smart Note-Taking App

![PHP Native](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Scribble Notes** adalah aplikasi manajemen catatan berbasis web yang dirancang dengan antarmuka modern, responsif, dan bersih (*clean UI*). Aplikasi ini membantu pengguna mencatat ide, tugas, dan prioritas sehari-hari secara cepat, efisien, dan bebas gangguan (*distraction-free*).

Dibuat menggunakan **PHP Native** berarsitektur *asynchronous* (Fetch API) dan didesain menggunakan **Tailwind CSS**.

---

## 📸 Pratinjau Tampilan (Screenshots)

| Landing Page | Dashboard Catatan |
| :---: | :---: |
| ![Landing Page](assets/img/screenshots/MainPages.png) | ![Note Management](assets/img/screenshots/NoteManagement.png) |

| Tambah Catatan (Color Picker) | Modal Edit & Detail |
| :---: | :---: |
| ![Add Note](assets/img/screenshots/AddNote.png) | ![Note Detail](assets/img/screenshots/NoteDetail.png) |

---

## ✨ Fitur-Fitur Utama

### 1. 🏠 Landing Page Interaktif & Modern
* **Hero Section dengan Animasi Typing**: Efek teks mengetik otomatis untuk menampilkan berbagai jenis catatan (Ide, Tugas, Proyek, dll).
* **Glassmorphic Navigation Bar**: Header transparan dengan efek *blur* kekinian.
* **Informasi Tim & Teknologi**: Modul *About Us* dan daftar anggota tim pengembang proyek.

### 2. 📋 Manajemen Catatan (Full CRUD)
* **Tambah Catatan (`tambahNote.php`)**: Form pembuatan catatan lengkap dengan pilihan judul, isi, kategori, dan warna latar (*color picker*).
* **Auto-Drafting (LocalStorage)**: Menjaga draf tulisan agar tidak hilang saat halaman tidak sengaja ter-refresh.
* **Edit Catatan tanpa Reload (Modal Interaktif)**: Mengubah isi catatan secara instan melalui modal *popup*.
* **Hapus Catatan**: Penghapusan catatan secara aman dengan konfirmasi modal.

### 3. 🎨 Smart Color Contrast Ratio (YIQ Algorithm)
* Aplikasi secara otomatis menghitung tingkat kecerahan (*luminance*) dari warna latar belakang (*background*) catatan yang dipilih pengguna.
* Jika latar belakang terang/pastel, warna teks otomatis menjadi **Hitam/Abu Gelap**.
* Jika latar belakang gelap, warna teks otomatis berubah menjadi **Putih**.
* *Mencegah teks "tidak terlihat" atau kontras yang buruk.*

### 4. 🏷️ Kategori & Manajemen Label
* Pengelompokan catatan berdasarkan label/kategori.
* Modal **Manajemen Label** khusus untuk menambah dan menghapus label kategori.
* Relasi basis data aman (*foreign key handling*): jika label dihapus, catatan terkait otomatis berubah menjadi *Tanpa Kategori*.

### 5. 🔍 Live Search & Sorting Instant
* **Pencarian Realtime**: Pencarian kata kunci pada judul atau isi catatan secara langsung tanpa perlu klik tombol cari atau mereload halaman.
* **Pengurutan Dinamis (Sort)**: Mengurutkan catatan berdasarkan *Tanggal Dibuat*, *Judul (A-Z)*, atau *Label/Kategori*.

---

## 🛠️ Teknologi yang Digunakan

* **Backend**: PHP Native (Prosedural/OOP dasar)
* **Database**: MySQL / MariaDB (Driver MySQLi)
* **Frontend Framework**: Tailwind CSS v3 (Custom Utility)
* **Iconography**: FontAwesome v6 & Bootstrap Icons
* **Scripting**: Pure JavaScript (ES6+ Fetch API, DOM Manipulation, LocalStorage)
* **Server Environment**: Laragon / XAMPP (Apache Web Server)

---

## 📁 Struktur Direktori Proyek

```text
note-app-bootstrap/
│
├── assets/                  # Asset statis (Gambar, logo, favicon, JS vendor)
├── dist/                    # Compiled Tailwind CSS output (output.css)
├── font/                    # File FontAwesome & Custom Fonts
├── forms/                   # Script penanganan form
├── node_modules/            # NPM Dependencies (TailwindCSS CLI)
│
├── data.php                 # Dashboard utama manajemen & grid catatan
├── index.php                # Landing Page (Home, About, Team, Features)
├── koneksi.php              # Konfigurasi koneksi ke database MySQL
├── tambahNote.php           # Halaman form pembuatan catatan baru
├── note-app.sql             # Export file database / struktur tabel
│
├── package.json             # Konfigurasi NPM & script build Tailwind
├── package-lock.json        # NPM lockfile
├── tailwind.config.js       # Konfigurasi custom Tailwind CSS
├── portfolio-details.php    # Halaman pendukung template
├── service-details.php      # Halaman pendukung template
├── starter-page.php         # Starter template page
└── Readme.txt               # Catatan proyek bawaan