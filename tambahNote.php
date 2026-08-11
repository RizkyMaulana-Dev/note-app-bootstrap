<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "koneksi.php";

// Proses penambahan kategori baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newCategory'])) {
    $newCategory = $conn->real_escape_string($_POST['newCategory']);
    
    // Default warna jika tidak dipilih
    $categoryColor = isset($_POST['categoryColor']) && $_POST['categoryColor'] != '' ? $conn->real_escape_string($_POST['categoryColor']) : '#ffffff';
    
    $conn->query("INSERT INTO Labels (nama_label, warna) VALUES ('$newCategory', '$categoryColor')");
    
    // Redirect untuk mencegah resubmission form saat direfresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Proses penambahan catatan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $color = isset($_POST['color']) && $_POST['color'] !== '' ? $conn->real_escape_string($_POST['color']) : '#ffffff';
    
    // Jika kategori dipilih, pastikan itu ID yang valid, jika tidak set NULL
    $labelId = (isset($_POST['category']) && is_numeric($_POST['category'])) ? intval($_POST['category']) : 'NULL';

    $query = "INSERT INTO Notes (judul, isi, id_label, bg_color, tanggal_buat, tanggal_ubah) 
              VALUES ('$title', '$content', $labelId, '$color', NOW(), NOW())";

    if ($conn->query($query)) {
        echo "<script>
                localStorage.clear();
                window.location.href = 'index.php'; // Ganti ke data.php jika itu halaman utamanya
              </script>";
        exit();
    } else {
        $errorMsg = "Gagal menyimpan catatan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Catatan - Scribble Notes</title>
    <!-- Font Inter & Tailwind CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        
        /* Animasi ring untuk color picker */
        .color-option {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .color-option.selected {
            transform: scale(1.15);
            box-shadow: 0 0 0 3px white, 0 0 0 5px #3b82f6; /* Blue ring */
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-200 selection:text-blue-900">

    <!-- Header Sederhana -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 sticky top-0 z-40">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between max-w-3xl">
            <a href="index.php" class="flex items-center gap-2 text-slate-600 hover:text-blue-600 transition font-medium">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-xl font-bold text-slate-800">Catatan Baru</h1>
            <div class="w-20"></div> <!-- Spacer -->
        </div>
    </header>

    <main class="container mx-auto px-6 py-10 max-w-3xl">
        
        <?php if(isset($errorMsg)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-200 flex items-center gap-3">
                <i class="fas fa-circle-exclamation"></i> <?= $errorMsg ?>
            </div>
        <?php endif; ?>

        <!-- Form Container -->
        <form action="" method="POST" class="bg-white shadow-sm border border-slate-200 rounded-3xl p-6 md:p-10">
            
            <!-- Judul -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Catatan</label>
                <input type="text" id="title" name="title" required oninput="saveInputValues()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-lg font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all placeholder:font-normal placeholder:text-slate-400" 
                    placeholder="Masukkan judul singkat...">
            </div>

            <!-- Isi Catatan -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-2">Isi Catatan</label>
                <textarea id="content" name="content" required oninput="saveInputValues()" rows="6"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none placeholder:text-slate-400" 
                    placeholder="Mulai ketikkan idemu di sini..."></textarea>
            </div>

            <!-- Kategori / Label -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kategori</label>
                <div class="relative">
                    <select id="category" name="category" onchange="checkCategory()"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none">
                        <option value="">Tanpa Kategori</option>
                        <?php
                        $sql = "SELECT * FROM Labels";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Value dikirim berupa ID (lebih aman untuk database)
                                echo "<option value='" . $row['id_label'] . "'>" . htmlspecialchars($row['nama_label']) . "</option>";
                            }
                        }
                        ?>
                        <option value="tambah" class="font-bold text-blue-600">+ Buat Kategori Baru</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <!-- Color Picker (Warna Pastel) -->
            <div class="mb-10">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Warna Latar</label>
                <div class="flex flex-wrap gap-4" id="colorPicker">
                    <div class="color-option w-10 h-10 rounded-full border border-slate-200 selected" style="background-color: #ffffff;" onclick="selectColor(this, '#ffffff')"></div>
                    <div class="color-option w-10 h-10 rounded-full border border-slate-200" style="background-color: #fef08a;" onclick="selectColor(this, '#fef08a')" title="Kuning Pastel"></div>
                    <div class="color-option w-10 h-10 rounded-full border border-slate-200" style="background-color: #bbf7d0;" onclick="selectColor(this, '#bbf7d0')" title="Hijau Pastel"></div>
                    <div class="color-option w-10 h-10 rounded-full border border-slate-200" style="background-color: #bfdbfe;" onclick="selectColor(this, '#bfdbfe')" title="Biru Pastel"></div>
                    <div class="color-option w-10 h-10 rounded-full border border-slate-200" style="background-color: #fbcfe8;" onclick="selectColor(this, '#fbcfe8')" title="Pink Pastel"></div>
                    <div class="color-option w-10 h-10 rounded-full border border-slate-200" style="background-color: #fed7aa;" onclick="selectColor(this, '#fed7aa')" title="Orange Pastel"></div>
                </div>
                <input type="hidden" name="color" id="selectedColor" value="#ffffff">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="index.php" class="px-6 py-3 text-center text-slate-600 font-semibold rounded-xl hover:bg-slate-100 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-3 text-center bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                    <i class="fas fa-save mr-2"></i> Simpan Catatan
                </button>
            </div>
        </form>
    </main>

    <!-- Modal Tambah Kategori -->
    <div id="addCategoryModal" class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 z-50">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300" id="addCategoryModalInner">
            
            <div class="flex justify-between items-center mb-5">
                <h5 class="text-xl font-bold text-slate-800">Kategori Baru</h5>
                <button type="button" class="text-slate-400 hover:text-red-500 transition-colors" onclick="closeModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="" method="POST">
                <div class="mb-5">
                    <label for="newCategory" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" id="newCategory" name="newCategory" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Cth: Pekerjaan, Ide...">
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg font-medium transition" onclick="closeModal()">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript untuk Fungsionalitas -->
    <script>
        // --- Sistem Modal Kategori ---
        function checkCategory() {
            const select = document.getElementById('category');
            if (select.value === 'tambah') {
                openModal();
            }
        }

        function openModal() {
            const modal = document.getElementById('addCategoryModal');
            const inner = document.getElementById('addCategoryModalInner');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            inner.classList.remove('scale-95');
            inner.classList.add('scale-100');
        }

        function closeModal() {
            const modal = document.getElementById('addCategoryModal');
            const inner = document.getElementById('addCategoryModalInner');
            modal.classList.add('opacity-0', 'pointer-events-none');
            inner.classList.remove('scale-100');
            inner.classList.add('scale-95');
            // Kembalikan select ke opsi default jika batal
            document.getElementById('category').selectedIndex = 0;
        }

        // --- Sistem Color Picker ---
        function selectColor(element, hexColor) {
            // Hapus class selected dari semua opsi warna
            const options = document.querySelectorAll('#colorPicker .color-option');
            options.forEach(opt => opt.classList.remove('selected'));
            
            // Tambahkan class selected ke opsi yang di klik
            element.classList.add('selected');
            
            // Set value ke input hidden
            document.getElementById('selectedColor').value = hexColor;
            saveInputValues(); // Simpan otomatis saat warna diubah
        }

        // --- Sistem LocalStorage (Menyimpan Draft Saat Refresh) ---
        function saveInputValues() {
            localStorage.setItem('draft_noteTitle', document.getElementById('title').value);
            localStorage.setItem('draft_noteContent', document.getElementById('content').value);
            localStorage.setItem('draft_noteCategory', document.getElementById('category').value);
            localStorage.setItem('draft_noteColor', document.getElementById('selectedColor').value);
        }

        function loadInputValues() {
            const title = localStorage.getItem('draft_noteTitle');
            const content = localStorage.getItem('draft_noteContent');
            const category = localStorage.getItem('draft_noteCategory');
            const color = localStorage.getItem('draft_noteColor');

            if (title) document.getElementById('title').value = title;
            if (content) document.getElementById('content').value = content;
            if (category && category !== 'tambah') document.getElementById('category').value = category;
            
            if (color) {
                document.getElementById('selectedColor').value = color;
                // Update UI Color Picker
                const options = document.querySelectorAll('#colorPicker .color-option');
                options.forEach(opt => {
                    opt.classList.remove('selected');
                    // Konversi RGB ke HEX atau bandingkan langsung (karena kita set dari JS, harusnya match)
                    if (opt.getAttribute('onclick').includes(color)) {
                        opt.classList.add('selected');
                    }
                });
            }
        }

        // Muat draf saat halaman dibuka
        document.addEventListener("DOMContentLoaded", loadInputValues);
    </script>
</body>
</html>