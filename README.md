## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Simpan Project ke Web Server

Letakkan folder proyek di dalam direktori server lokal (Laragon/XAMPP):

- **Laragon**: `C:\laragon\www\note-app-bootstrap`
- **XAMPP**: `C:\xampp\htdocs\note-app-bootstrap`

### 2. Setup Database

1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Buat database baru (misal: `note-app`).
3. Cari file **`note-app.sql`** yang ada di root direktori project, lalu **Import** file tersebut ke dalam database yang baru dibuat di phpMyAdmin.
4. Sesuaikan konfigurasi nama database dan kredensial di file `koneksi.php` jika diperlukan.

### 3. Install Dependencies & Build Tailwind CSS

Buka terminal/PowerShell di dalam direktori project, lalu jalankan perintah berikut:

```bash
# 1. Install seluruh package/dependencies
npm install

# 2. Jalankan perintah ini untuk mode pengembangan (watch mode)
npm run dev

# ATAU jalankan ini untuk memproduksi CSS versi kompilasi akhir
npm run build

### 4. Buka Aplikasi
Akses aplikasi melalui browser:
* **Landing Page**: `http://localhost/note-app-bootstrap/index.php`
* **Dashboard Catatan**: `http://localhost/note-app-bootstrap/data.php`

```

## 👥 Tim Pengembang (Kelompok 2)

Proyek ini dikembangkan oleh **Kelompok 2** - **SMKN 4 Padalarang**:

* **Rizky Maulana** - *BackEnd Developer & Flowchart System*
* **Rafi B** - *FrontEnd Developer*
* **Dika** - *UI/UX Designer & Quality Assurance (QA)*
* **Dearly** - *Support & Project Documentation*

---

## 📄 Lisensi & Hak Cipta

© **Kelompok 2 - SMKN 4 Padalarang**. All Rights Reserved.
Dikembangkan untuk tujuan pembelajaran dan pengembangan aplikasi web modern.
