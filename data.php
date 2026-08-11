<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

// Fungsi response JSON
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Fungsi pintar untuk menentukan warna teks berdasarkan warna background (YIQ Ratio)
function getContrastColor($hexcolor) {
    $hexcolor = ltrim($hexcolor, '#');
    // Handle format shorthand (misal: #fff)
    if (strlen($hexcolor) == 3) {
        $hexcolor = $hexcolor[0].$hexcolor[0].$hexcolor[1].$hexcolor[1].$hexcolor[2].$hexcolor[2];
    }
    // Jika tidak ada warna, anggap putih
    if (strlen($hexcolor) != 6) return 'text-slate-800';

    $r = hexdec(substr($hexcolor, 0, 2));
    $g = hexdec(substr($hexcolor, 2, 2));
    $b = hexdec(substr($hexcolor, 4, 2));
    
    // Rumus YIQ untuk kecerahan
    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    
    // Jika YIQ >= 128 (terang), gunakan teks gelap. Jika tidak, gunakan teks putih.
    return ($yiq >= 128) ? 'text-slate-800' : 'text-white';
}

// 1. DELETE LABEL
if (isset($_GET['delete_label_id'])) {
    $deleteLabelId = intval($_GET['delete_label_id']);
    $sql = "DELETE FROM Labels WHERE id_label = $deleteLabelId";
    if ($conn->query($sql) === TRUE) {
        sendJsonResponse(['success' => 'Label berhasil dihapus']);
    } else {
        sendJsonResponse(['error' => 'Gagal menghapus label: ' . $conn->error]);
    }
}

// 2. GET NOTES (SORT & SEARCH)
if (isset($_GET['action']) && $_GET['action'] == 'get_notes') {
    $orderBy = 'ORDER BY tanggal_buat DESC';
    $where = '';

    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'judul': $orderBy = 'ORDER BY judul ASC'; break;
            case 'label': $orderBy = 'ORDER BY nama_label ASC'; break;
            case 'tanggal_ubah': $orderBy = 'ORDER BY tanggal_ubah DESC'; break;
        }
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $conn->real_escape_string($_GET['search']);
        $where = "WHERE judul LIKE '%$searchTerm%' OR isi LIKE '%$searchTerm%'";
    }

    $sql = "SELECT Notes.*, Labels.nama_label FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label $where $orderBy";
    $result = $conn->query($sql);
    $notes = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) $notes[] = $row;
    }
    sendJsonResponse(['notes' => $notes]);
}

// 3. GET SINGLE NOTE
if (isset($_GET['note_id'])) {
    $note_id = intval($_GET['note_id']);
    $sql = "SELECT * FROM Notes WHERE id_catatan = $note_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) sendJsonResponse($result->fetch_assoc());
    else sendJsonResponse(['error' => 'Catatan tidak ditemukan']);
}

// 4. DELETE NOTE
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM Notes WHERE id_catatan = $delete_id";
    if ($conn->query($sql) === TRUE) sendJsonResponse(['success' => 'Catatan dihapus']);
    else sendJsonResponse(['error' => 'Gagal menghapus']);
}

// 5. UPDATE NOTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noteId'])) {
    $noteId = intval($_POST['noteId']);
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $labelId = intval($_POST['label']) > 0 ? intval($_POST['label']) : 'NULL';
    
    $sql = "UPDATE Notes SET judul = '$title', isi = '$content', id_label = $labelId, tanggal_ubah = NOW() WHERE id_catatan = $noteId";
    if ($conn->query($sql) === TRUE) sendJsonResponse(['success' => 'Berhasil diperbarui']);
    else sendJsonResponse(['error' => 'Gagal update']);
}

// HTML Component Generator
function generateNoteCard($row) {
    $bgColor = htmlspecialchars($row['bg_color'] ?? '#ffffff');
    $label = htmlspecialchars($row['nama_label'] ?? 'Tanpa Kategori');
    
    // Menerapkan fungsi pintar pencocokan warna teks
    $textColor = getContrastColor($bgColor);
    
    // Jika teksnya putih, background label harus sedikit disesuaikan agar tidak hilang
    $labelStyle = ($textColor === 'text-white') 
        ? "bg-black/30 backdrop-blur text-white" 
        : "bg-white/80 backdrop-blur text-slate-700";
    
    $judul = htmlspecialchars($row['judul']);
    $isi = htmlspecialchars($row['isi']);
    $idNote = htmlspecialchars($row['id_catatan']);
    $tglBuat = date('d M Y', strtotime($row['tanggal_buat']));

    return "
    <div style='background-color: $bgColor;' class='group rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg transition-all duration-300 p-6 relative cursor-pointer flex flex-col h-full' onclick='openEditModal($idNote)'>
        <span class='absolute top-4 right-4 $labelStyle px-3 py-1 text-xs font-semibold rounded-full shadow-sm'>$label</span>
        <h2 class='text-xl font-bold mb-3 $textColor line-clamp-1 pr-20'>$judul</h2>
        <p class='text-sm opacity-90 mb-4 flex-grow $textColor line-clamp-4 leading-relaxed'>$isi</p>
        <div class='mt-auto pt-4 border-t border-black/10 flex justify-between items-center'>
            <p class='text-xs font-medium opacity-75 $textColor'><i class='fa-regular fa-clock mr-1'></i> $tglBuat</p>
        </div>
    </div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scribble Notes.</title>
    <!-- Ganti ke font Inter untuk UI modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>

<body class="bg-slate-50 text-slate-800">
    <!-- Header (Glassmorphism) -->
    <header class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md shadow-sm z-40 border-b border-slate-200">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-1 group">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">S</div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight ml-2">Scribble Notes<span class="text-blue-600">.</span></h1>
            </a>
            <div class="hidden md:block relative w-96">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchInput" placeholder="Cari catatan..." class="w-full pl-11 pr-4 py-2.5 bg-slate-100 border-none rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm" onkeyup="handleSearch(event)">
            </div>
            <button class="md:hidden text-slate-600"><i class="fas fa-bars text-xl"></i></button>
        </div>
    </header>

    <main class="mt-28 container mx-auto px-6 mb-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Catatan Anda</h1>
                <p class="text-slate-500 text-sm">Kelola ide dan tugas Anda dengan mudah</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="openLabelModal()" class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition-all shadow-sm" title="Manajemen Label">
                    <i class="fas fa-tags"></i>
                </button>
                <button onclick="toggleSort()" id="sortButton" class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition-all shadow-sm" title="Urutkan">
                    <i id="sortIcon" class="fas fa-clock"></i>
                </button>
                <button onclick="window.location.href = 'tambahNote.php';" class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-600 text-white font-medium hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <!-- Notes Grid -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="notesSection">
            <?php
            $sql = "SELECT Notes.*, Labels.nama_label FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label ORDER BY tanggal_buat DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) echo generateNoteCard($row);
            } else {
                echo "<div class='col-span-full text-center py-20 text-slate-500'>
                        <i class='fa-regular fa-folder-open text-5xl mb-4 opacity-50'></i>
                        <p>Belum ada catatan.</p>
                      </div>";
            }
            ?>
        </section>
    </main>

    <!-- Modal Label -->
    <div id="labelModal" class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300" id="labelModalInner">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Manajemen Label</h2>
                <button onclick="closeLabelModal()" class="text-slate-400 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <div class="max-h-60 overflow-y-auto mb-4 bg-slate-50 rounded-xl p-2 border border-slate-100">
                <?php
                $sql = "SELECT * FROM Labels";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='flex items-center justify-between p-3 bg-white rounded-lg mb-2 shadow-sm border border-slate-100'>";
                        echo "<span class='font-medium text-slate-700'>" . htmlspecialchars($row['nama_label']) . "</span>";
                        echo "<button onclick='deleteLabel(" . $row['id_label'] . ")' class='text-red-500 hover:bg-red-50 p-2 rounded-md transition'><i class='fas fa-trash'></i></button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-center text-slate-500 py-4 text-sm'>Belum ada label</p>";
                }
                ?>
            </div>
            
            <div class="mt-4 border-t border-slate-100 pt-4">
                <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Tambah Label Baru</label>
                <div class="flex gap-2">
                    <input type="text" id="newLabelName" placeholder="Nama Label..." class="flex-1 bg-slate-100 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <button onclick="saveLabel()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 z-50">
        <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl transform scale-95 transition-transform duration-300" id="editModalInner">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Edit Catatan</h2>
            <input type="hidden" id="noteId">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Judul</label>
                    <input type="text" id="noteTitle" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Isi Catatan</label>
                    <textarea id="noteContent" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Label</label>
                    <select id="noteLabel" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Tanpa Kategori</option>
                        <?php
                        $sql = "SELECT * FROM Labels";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_label'] . "'>" . htmlspecialchars($row['nama_label']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center mt-8 pt-4 border-t border-slate-100">
                <button onclick="deleteNote()" class="text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                    <i class="fas fa-trash"></i> Hapus
                </button>
                <div class="flex gap-2">
                    <button onclick="closeEditModal()" class="text-slate-600 hover:bg-slate-100 px-4 py-2 rounded-lg font-medium transition">Batal</button>
                    <button onclick="updateNote()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Menggunakan Fetch API (Modern & Clean) -->
    <script>
        let currentSort = 'tanggal';
        let searchTimeout = null;

        // --- Helper Fungsi JavaScript untuk Kontras Otomatis (Saat Fetching) ---
        function getContrastColor(hexcolor) {
            hexcolor = hexcolor.replace('#', '');
            if(hexcolor.length === 3) {
                hexcolor = hexcolor[0]+hexcolor[0]+hexcolor[1]+hexcolor[1]+hexcolor[2]+hexcolor[2];
            }
            if(hexcolor.length !== 6) return 'text-slate-800';
            
            var r = parseInt(hexcolor.substr(0,2),16);
            var g = parseInt(hexcolor.substr(2,2),16);
            var b = parseInt(hexcolor.substr(4,2),16);
            var yiq = ((r*299)+(g*587)+(b*114))/1000;
            return (yiq >= 128) ? 'text-slate-800' : 'text-white';
        }

        // --- Fetch Data Core Function ---
        async function fetchNotes(queryParam = '') {
            try {
                const response = await fetch(`?action=get_notes&${queryParam}`);
                const data = await response.json();
                renderNotes(data.notes);
            } catch (error) {
                console.error("Gagal mengambil data:", error);
            }
        }

        function renderNotes(notes) {
            const container = document.getElementById('notesSection');
            if (!notes || notes.length === 0) {
                container.innerHTML = `<div class='col-span-full text-center py-20 text-slate-500'><i class='fa-regular fa-folder-open text-5xl mb-4 opacity-50'></i><p>Tidak ada catatan ditemukan.</p></div>`;
                return;
            }

            container.innerHTML = notes.map(note => {
                const bgColor = note.bg_color || '#ffffff';
                const label = note.nama_label || 'Tanpa Kategori';
                
                // Gunakan fungsi kontras di JS untuk render Live Search
                const textColor = getContrastColor(bgColor);
                const labelStyle = (textColor === 'text-white') ? "bg-black/30 text-white" : "bg-white/80 text-slate-700";
                
                const tglBuat = new Date(note.tanggal_buat).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});

                return `
                <div style="background-color: ${bgColor};" class="group rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg transition-all duration-300 p-6 relative cursor-pointer flex flex-col h-full" onclick="openEditModal(${note.id_catatan})">
                    <span class="absolute top-4 right-4 ${labelStyle} backdrop-blur px-3 py-1 text-xs font-semibold rounded-full shadow-sm">${label}</span>
                    <h2 class="text-xl font-bold mb-3 ${textColor} line-clamp-1 pr-20">${note.judul}</h2>
                    <p class="text-sm opacity-90 mb-4 flex-grow ${textColor} line-clamp-4 leading-relaxed">${note.isi}</p>
                    <div class="mt-auto pt-4 border-t border-black/10 flex justify-between items-center">
                        <p class="text-xs font-medium opacity-75 ${textColor}"><i class="fa-regular fa-clock mr-1"></i> ${tglBuat}</p>
                    </div>
                </div>`;
            }).join('');
        }

        // --- Fitur Search (Realtime) ---
        function handleSearch(event) {
            clearTimeout(searchTimeout);
            const term = event.target.value;
            searchTimeout = setTimeout(() => fetchNotes(`search=${term}`), 300);
        }

        // --- Fitur Sort ---
        function toggleSort() {
            currentSort = currentSort === 'tanggal' ? 'judul' : currentSort === 'judul' ? 'label' : 'tanggal';
            
            const icon = document.getElementById('sortIcon');
            icon.className = currentSort === 'judul' ? 'fas fa-font' : currentSort === 'label' ? 'fas fa-tags' : 'fas fa-clock';
            
            fetchNotes(`sort=${currentSort}`);
        }

        // --- Modals Controller (Dengan Animasi) ---
        function openModal(id, innerId) {
            const modal = document.getElementById(id);
            const inner = document.getElementById(innerId);
            modal.classList.remove('opacity-0', 'pointer-events-none');
            inner.classList.remove('scale-95');
            inner.classList.add('scale-100');
        }

        function closeModal(id, innerId) {
            const modal = document.getElementById(id);
            const inner = document.getElementById(innerId);
            modal.classList.add('opacity-0', 'pointer-events-none');
            inner.classList.remove('scale-100');
            inner.classList.add('scale-95');
        }

        // --- CRUD Notes ---
        async function openEditModal(noteId) {
            try {
                const response = await fetch(`?note_id=${noteId}`);
                const note = await response.json();
                if (!note.error) {
                    document.getElementById('noteId').value = note.id_catatan;
                    document.getElementById('noteTitle').value = note.judul;
                    document.getElementById('noteContent').value = note.isi;
                    document.getElementById('noteLabel').value = note.id_label || '';
                    openModal('editModal', 'editModalInner');
                }
            } catch (err) { console.error(err); }
        }

        function closeEditModal() { closeModal('editModal', 'editModalInner'); }

        async function updateNote() {
            const data = new URLSearchParams();
            data.append('noteId', document.getElementById('noteId').value);
            data.append('title', document.getElementById('noteTitle').value);
            data.append('content', document.getElementById('noteContent').value);
            data.append('label', document.getElementById('noteLabel').value);

            await fetch('', { method: 'POST', body: data });
            closeEditModal();
            fetchNotes(); // Reload notes silently
        }

        async function deleteNote() {
            if (!confirm('Yakin hapus catatan ini?')) return;
            const id = document.getElementById('noteId').value;
            await fetch(`?delete_id=${id}`);
            closeEditModal();
            fetchNotes();
        }

        // --- CRUD Labels ---
        function openLabelModal() { openModal('labelModal', 'labelModalInner'); }
        function closeLabelModal() { closeModal('labelModal', 'labelModalInner'); }

        async function deleteLabel(id) {
            if(confirm('Hapus label ini? Catatan terkait akan menjadi Tanpa Kategori.')) {
                await fetch(`?delete_label_id=${id}`);
                location.reload(); // Hard reload untuk update dropdown
            }
        }

        async function saveLabel() {
            const labelName = document.getElementById('newLabelName').value;
            if(!labelName) return alert('Nama label tidak boleh kosong');
            
            const data = new URLSearchParams();
            data.append('labelName', labelName);
            // Anggap kamu punya file save_label.php yang menangani POST ini
            await fetch('save_label.php', { method: 'POST', body: data });
            location.reload();
        }
    </script>
</body>
</html>