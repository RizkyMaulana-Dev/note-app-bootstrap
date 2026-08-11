<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Scribble Notes - Catatan Pintar Anda</title>
  <meta name="description" content="Aplikasi catatan sederhana dan efektif">
  
  <!-- Fonts: Menggunakan Inter untuk tampilan modern dan bersih -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS (Menggunakan CDN untuk preview, ganti ke file lokal jika sudah build) -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Konfigurasi Tailwind untuk Font -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-blue-200 selection:text-blue-900">

  <!-- Header dengan efek Glassmorphism -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 transition-all duration-300">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <a href="index.php" class="flex items-center gap-1 group">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-blue-700 transition-colors">
          S
        </div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Scribble Notes<span class="text-blue-600">.</span></h1>
      </a>

      <nav class="hidden md:flex space-x-8">
        <a href="#hero" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Home</a>
        <a href="#main" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Fitur</a>
        <a href="#about" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Tentang</a>
        <a href="#team" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Tim</a>
      </nav>

      <div class="hidden md:block">
        <a href="data.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
          Mulai Sekarang
        </a>
      </div>

      <button class="md:hidden text-slate-600 hover:text-blue-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
  </header>

  <main>
    <!-- Hero Section -->
    <section id="hero" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50">
      <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center" id="welcome-text">
          <span class="inline-block py-1 px-3 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold mb-6 border border-blue-200">
            Selamat Datang di
          </span>
          <h2 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
            Scribble Notes
          </h2>
          <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto">
            Ruang kerja digital yang sederhana, bersih, dan cepat untuk mencatat setiap ide brilian Anda.
          </p>
          <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="#main" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-full font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
              Pelajari Lebih Lanjut
            </a>
          </div>
        </div>
      </div>
      <!-- Decorative Elements -->
      <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
      <div class="absolute top-1/2 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    </section>

    <!-- Main/Typing Section -->
    <section id="main" class="py-24 bg-white">
      <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl font-extrabold text-slate-900 mb-6">Mulai Mencatat</h2>
        <p class="text-xl md:text-2xl text-slate-600 font-medium mb-12 h-10">
          Simpan <span id="typing-text" class="text-blue-600 border-b-2 border-blue-600 pr-1"></span> Anda dengan rapi.
        </p>
        
        <a href="data.php" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-full font-bold text-lg hover:bg-blue-700 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-300">
          Buat Catatan Baru
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-slate-900 text-white">
      <div class="container mx-auto px-6 max-w-5xl">
        <div class="grid md:grid-cols-2 gap-16 items-center">
          <div>
            <h2 class="text-4xl font-bold mb-6">Tentang Kami</h2>
            <p class="text-slate-300 text-lg leading-relaxed mb-8">
              Website ini dirancang oleh <strong>Kelompok 2</strong>. Tujuan kami adalah menciptakan pengalaman mencatat yang minimalis, bebas gangguan, dan efektif untuk meningkatkan produktivitas Anda sehari-hari.
            </p>
            <a href="#team" class="inline-block px-6 py-3 border border-slate-700 text-white rounded-full hover:bg-white hover:text-slate-900 transition-colors font-medium">
              Kenali Tim Kami
            </a>
          </div>
          
          <div class="bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-2xl">
            <h3 class="text-xl font-semibold mb-6 text-center text-slate-200">Dibangun dengan Teknologi</h3>
            <div class="flex justify-center items-center gap-6 flex-wrap">
              <div class="bg-slate-700/50 p-4 rounded-2xl hover:bg-slate-700 transition-colors" title="TailwindCSS">
                <img src="assets/logo/tailwind.png" alt="TailwindCSS" class="w-16 h-16 object-contain" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/d/d5/Tailwind_CSS_Logo.svg'">
              </div>
              <div class="bg-slate-700/50 p-4 rounded-2xl hover:bg-slate-700 transition-colors" title="PHP">
                <img src="assets/logo/php.png" alt="PHP" class="w-16 h-16 object-contain" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/2/27/PHP-logo.svg'">
              </div>
              <div class="bg-slate-700/50 p-4 rounded-2xl hover:bg-slate-700 transition-colors" title="ChatGPT">
                <img src="assets/logo/chatgpt.png" alt="ChatGPT" class="w-16 h-16 object-contain filter invert opacity-80" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/0/04/ChatGPT_logo.svg'">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="py-24 bg-slate-50">
      <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
          <h2 class="text-4xl font-bold text-slate-900 mb-4">Tim Kami</h2>
          <p class="text-slate-600 text-lg">Orang-orang di balik layar yang membuat Scribble Notes menjadi nyata.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Card 1 -->
          <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl border border-slate-100 hover:-translate-y-1 transition-all duration-300 text-center group">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-6 ring-4 ring-slate-50 group-hover:ring-blue-100 transition-all">
              <img src="assets/img/team/team-1.jpg" class="w-full h-full object-cover" alt="Rizky Maulana" onerror="this.src='https://ui-avatars.com/api/?name=Rizky+Maulana&background=0D8ABC&color=fff&size=200'">
            </div>
            <h4 class="text-xl font-bold text-slate-900 mb-1">Rizky Maulana</h4>
            <p class="text-sm text-blue-600 font-medium mb-3">BackEnd Dev</p>
            <p class="text-sm text-slate-500">Flow Chart & Logic System</p>
          </div>

          <!-- Card 2 -->
          <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl border border-slate-100 hover:-translate-y-1 transition-all duration-300 text-center group">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-6 ring-4 ring-slate-50 group-hover:ring-blue-100 transition-all">
              <img src="assets/img/team/team-2.jpg" class="w-full h-full object-cover" alt="Rafi B" onerror="this.src='https://ui-avatars.com/api/?name=Rafi+B&background=0D8ABC&color=fff&size=200'">
            </div>
            <h4 class="text-xl font-bold text-slate-900 mb-1">Rafi B</h4>
            <p class="text-sm text-blue-600 font-medium mb-3">FrontEnd Dev</p>
            <p class="text-sm text-slate-500">Desain & Implementasi UI</p>
          </div>

          <!-- Card 3 -->
          <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl border border-slate-100 hover:-translate-y-1 transition-all duration-300 text-center group">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-6 ring-4 ring-slate-50 group-hover:ring-blue-100 transition-all">
              <img src="assets/img/team/team-3.jpg" class="w-full h-full object-cover" alt="Dika" onerror="this.src='https://ui-avatars.com/api/?name=Dika&background=0D8ABC&color=fff&size=200'">
            </div>
            <h4 class="text-xl font-bold text-slate-900 mb-1">Dika</h4>
            <p class="text-sm text-blue-600 font-medium mb-3">UI/UX & QA</p>
            <p class="text-sm text-slate-500">Test Case & User Experience</p>
          </div>

          <!-- Card 4 -->
          <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl border border-slate-100 hover:-translate-y-1 transition-all duration-300 text-center group">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-6 ring-4 ring-slate-50 group-hover:ring-blue-100 transition-all">
              <img src="assets/img/team/team-4.jpg" class="w-full h-full object-cover" alt="Dearly" onerror="this.src='https://ui-avatars.com/api/?name=Dearly&background=f1f5f9&color=64748b&size=200'">
            </div>
            <h4 class="text-xl font-bold text-slate-900 mb-1">Dearly</h4>
            <p class="text-sm text-slate-400 font-medium mb-3">Anggota</p>
            <p class="text-sm text-slate-400 italic">Support</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-white border-t border-slate-200 pt-12 pb-8">
    <div class="container mx-auto px-6 text-center">
      <div class="flex justify-center items-center gap-2 mb-4">
        <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center text-white text-xs font-bold">
          S
        </div>
        <span class="font-bold text-slate-900">Scribble Notes.</span>
      </div>
      <p class="text-slate-500 text-sm mb-2">&copy; 2024 Kelompok 2. All Rights Reserved.</p>
      <p class="text-slate-400 text-xs uppercase tracking-wider">SMKN 4 Padalarang</p>
    </div>
  </footer>

  <!-- Typing Script (Diperhalus) -->
  <script>
    const texts = ["Ide", "Tugas Sekolah", "Proyek", "Daftar Belanja", "Kata-kata Penting", "Nomor Telepon", "Impian", "Jadwal", "Lirik Lagu", "Daftar Bacaan", "Akun & Password"];
    let currentTextIndex = 0;
    let currentCharIndex = 0;
    let isDeleting = false;
    
    // Kecepatan disesuaikan agar lebih natural
    const typingSpeed = 100; 
    const deletingSpeed = 40; 
    const delayBetweenTexts = 2000; 

    function type() {
      const typingTextElement = document.getElementById("typing-text");
      if (!typingTextElement) return;

      const currentText = texts[currentTextIndex];

      if (isDeleting) {
        typingTextElement.textContent = currentText.substring(0, currentCharIndex - 1);
        currentCharIndex--;

        if (currentCharIndex === 0) {
          isDeleting = false;
          currentTextIndex = (currentTextIndex + 1) % texts.length;
          setTimeout(type, typingSpeed);
        } else {
          setTimeout(type, deletingSpeed);
        }
      } else {
        typingTextElement.textContent = currentText.substring(0, currentCharIndex + 1);
        currentCharIndex++;

        if (currentCharIndex === currentText.length) {
          isDeleting = true;
          setTimeout(type, delayBetweenTexts);
        } else {
          setTimeout(type, typingSpeed);
        }
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      setTimeout(type, 500); // Sedikit delay sebelum animasi mulai
    });
  </script>
</body>

</html>