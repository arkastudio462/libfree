<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20 pb-28"><div class="flex flex-col w-full space-y-12">
<!-- 1. BREADCRUMB & HEADER NAVIGASI -->
<section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs sm:text-sm font-bold flex-wrap">
<a class="text-neutral-500 hover:text-brand-dark transition-colors flex items-center gap-1" href="#hero">
<span class="material-symbols-outlined text-base">home</span> Beranda
      </a>
<span class="text-neutral-400">/</span>
<a class="text-neutral-500 hover:text-brand-dark transition-colors" href="#katalog">Katalog &amp; Koleksi</a>
<span class="text-neutral-400">/</span>
<span class="px-2.5 py-0.5 rounded-full bg-brand-coral/20 text-brand-dark border-2 border-brand-dark text-xs">Sastra Klasik</span>
<span class="text-neutral-400">/</span>
<span class="text-brand-dark font-extrabold">Bumi Manusia</span>
</nav>
<div class="flex items-center gap-2.5 shrink-0">
<a class="neo-pill px-3.5 py-1.5 text-xs bg-white hover:bg-neutral-100 text-brand-dark shadow-solid" href="#katalog">
<span class="material-symbols-outlined text-sm mr-1">arrow_back</span> Kembali ke Katalog
      </a>
<button class="neo-pill px-3.5 py-1.5 text-xs bg-white hover:bg-neutral-100 text-brand-dark shadow-solid" id="btn-share" title="Bagikan Buku">
<span class="material-symbols-outlined text-sm mr-1">share</span> Bagikan
      </button>
<button class="neo-pill px-3.5 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebae34] text-brand-dark shadow-solid flex items-center gap-1.5" id="btn-bookmark">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">bookmark</span>
<span>Simpan</span>
<span class="bg-brand-dark text-white rounded-full px-2  text-[10px] ml-0.5">1.420</span>
</button>
</div>
</section>
<!-- 2. HERO SECTION DETAIL BUKU (2 KOLOM LAYOUT) -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- KOLOM KIRI (Visual Sampul, Aksi Cepat Unduh/Baca, Lisensi) -->
<div class="lg:col-span-5 space-y-6">
<div class="neo-card p-6 bg-white shadow-solid-lg flex flex-col items-center relative overflow-hidden">
<!-- Banner Strip Sudut -->
<div class="absolute -right-12 top-6 bg-brand-coral border-2 border-brand-dark text-brand-dark text-[11px] font-extrabold uppercase px-12 py-1 rotate-45 shadow-sm">
          Mahakarya
        </div>
<!-- Frame Sampul Buku -->
<div class="w-full max-w-xs aspect-[3/4.2] neo-card bg-[#F4EDE2] border-2 border-brand-dark p-6 relative flex flex-col justify-between shadow-solid overflow-hidden group">
<div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-multiply group-hover:scale-105 transition-transform duration-500" data-alt="Vintage colonial era Indonesian novel cover aesthetic with subtle typography, textured paper grain, sepia tones, and architectural hints of late 19th-century Surabaya." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuCdDUwGh2Cdel20ocRUJ81k7Fmo2WIlBHuvO54atTxh9LsUWVy0rJwW0DaD5l8iMNAVm95IIZMeIY-GkpSBbWIyyvxBuUZUkTfgDn7nR0Q2eialPfTUTEU7On70NUxAA7AnI31JWMRHvoNqvqGS1oD7LBNf-35oYvbXSUgJdlvNzcZItaHAVyomuGHPzsKFlTZv95be87PMkvGYeYXKdrPNbpY0XctjlO14FglgsEY&quot;);"></div>
<!-- Bagian Atas Sampul -->
<div class="relative z-10 space-y-1">
<span class="neo-pill px-2.5 py-0.5 text-[10px] bg-brand-dark text-white font-extrabold tracking-wider uppercase">
              Tetralogi Buru #1
            </span>
<p class="text-[11px] font-bold tracking-widest text-brand-dark/70 uppercase pt-2">Roman Sejarah Indonesia</p>
</div>
<!-- Judul Sampul -->
<div class="relative z-10 py-4 my-auto">
<h1 class="font-display font-extrabold text-3xl sm:text-4xl text-brand-dark tracking-tight leading-none sm:leading-10 mb-1">
              BUMI<br><span class="text-brand-coral">MANUSIA</span>
</h1>
<div class="w-16 h-1.5 bg-brand-yellow border border-brand-dark my-3"></div>
<p class="font-sans font-bold text-xs uppercase tracking-wider text-neutral-800">
              Pramoedya Ananta Toer
            </p>
</div>
<!-- Bagian Bawah Sampul -->
<div class="relative z-10 flex items-center justify-between pt-2 border-t-2 border-brand-dark/20 text-[10px] font-bold text-neutral-700">
<span>Restorasi Arsip 2024</span>
<span class="flex items-center gap-1 text-brand-green">
<span class="material-symbols-outlined text-xs" style="font-variation-settings: &quot;FILL&quot; 1;">verified</span> Hak Cipta Terbuka
            </span>
</div>
</div>
<!-- Badges di Bawah Sampul -->
<div class="flex flex-wrap items-center justify-center gap-2 mt-4">
<span class="neo-pill px-3 py-1 text-xs bg-brand-green/20 text-brand-dark">
<span class="material-symbols-outlined text-xs mr-1 text-brand-green" style="font-variation-settings: &quot;FILL&quot; 1;">lock_open</span> Domain Publik Bebas
          </span>
<span class="neo-pill px-3 py-1 text-xs bg-brand-yellow-light text-brand-dark">
<span class="material-symbols-outlined text-xs mr-1">history_edu</span> Klasik Abadi
          </span>
</div>
</div>
<!-- Panel Aksi Baca & Unduh -->
<div class="neo-card p-5 bg-white shadow-solid space-y-4">
<a class="neo-pill w-full py-3.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm sm:text-base font-extrabold text-center shadow-solid flex items-center justify-center gap-2 group" href="#reader-modal">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">menu_book</span>
          Baca Sekarang di Web
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
</a>
<div class="grid grid-cols-2 gap-2.5 pt-1">
<a class="neo-pill py-2.5 px-3 bg-brand-blue-light hover:bg-[#d6eefb] text-brand-dark text-xs font-bold text-center justify-center" href="#download-epub">
<span class="material-symbols-outlined text-sm mr-1">download</span> EPUB (4.2 MB)
          </a>
<a class="neo-pill py-2.5 px-3 bg-brand-coral-light hover:bg-[#fad3d3] text-brand-dark text-xs font-bold text-center justify-center" href="#download-pdf">
<span class="material-symbols-outlined text-sm mr-1">picture_as_pdf</span> PDF Asli (12.8 MB)
          </a>
</div>
<!-- Dropdown / Format Ekstra -->
<div class="relative">
<button class="neo-pill w-full py-2 bg-neutral-100 hover:bg-neutral-200 text-xs font-bold text-neutral-800 justify-between px-4" id="format-toggle">
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-sm">devices</span> Opsi Format Lain (MOBI, TXT, Daisy, Audio)
            </span>
<span class="material-symbols-outlined text-sm">expand_more</span>
</button>
<div class="hidden mt-2 p-3 bg-brand-cream neo-card text-xs space-y-2" id="format-menu">
<div class="flex justify-between items-center py-1 border-b border-brand-dark/10">
<span class="font-bold">MOBI (Kindle Legacy)</span>
<a class="neo-pill px-2.5 py-0.5 bg-white text-[11px]" href="#mobi">3.9 MB</a>
</div>
<div class="flex justify-between items-center py-1 border-b border-brand-dark/10">
<span class="font-bold">Plain Text (UTF-8)</span>
<a class="neo-pill px-2.5 py-0.5 bg-white text-[11px]" href="#txt">1.1 MB</a>
</div>
<div class="flex justify-between items-center py-1">
<span class="font-bold">Buku Suara Bab 1-3 (MP3)</span>
<a class="neo-pill px-2.5 py-0.5 bg-brand-yellow text-[11px]" href="#audio">48.0 MB</a>
</div>
</div>
</div>
<!-- Status Lisensi & Naskah -->
<div class="rounded-xl p-3.5 bg-brand-green-light border-2 border-brand-dark space-y-2 text-xs">
<div class="flex items-start gap-2 text-brand-dark font-bold">
<span class="material-symbols-outlined text-brand-green text-base shrink-0" style="font-variation-settings: &quot;FILL&quot; 1;">verified_user</span>
<span>Terverifikasi Naskah Domain Publik Terbuka &amp; Bebas Royalti Komersial.</span>
</div>
<div class="flex items-center gap-2 text-neutral-700 font-medium pl-6">
<span class="material-symbols-outlined text-brand-green text-xs">done_all</span>
<span>Koreksi OCR 100% tervalidasi sukarelawan arsip.</span>
</div>
</div>
</div>
</div>
<!-- KOLOM KANAN (Informasi Utama, Statistik, Tab Sinopsis, Spesifikasi) -->
<div class="lg:col-span-7 space-y-6">
<div class="neo-card p-6 sm:p-8 bg-white shadow-solid-lg space-y-6">
<!-- Badges Kategori -->
<div class="flex flex-wrap items-center gap-2">
<span class="neo-pill px-3 py-1 text-xs bg-brand-coral text-brand-dark">Sastra Klasik</span>
<span class="neo-pill px-3 py-1 text-xs bg-brand-blue text-brand-dark">Pra-Kemerdekaan / Hindia Belanda</span>
<span class="neo-pill px-3 py-1 text-xs bg-brand-green-light text-brand-dark">Edisi Restorasi 2024</span>
</div>
<!-- Judul & Subjudul -->
<div class="space-y-2">
<h2 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-brand-dark leading-tight sm:leading-10 lg:leading-none tracking-tight">
            Bumi Manusia
          </h2>
<p class="font-sans font-bold text-base sm:text-lg text-brand-muted">
            Tetralogi Buru — Bagian Pertama (Pentalogi Sejarah Kebangkitan Nasional)
          </p>
</div>
<!-- Profil Singkat Penulis -->
<div class="flex items-center gap-3.5 p-3 rounded-2xl bg-brand-yellow-light border-2 border-brand-dark">
<div class="w-12 h-12 rounded-full border-2 border-brand-dark overflow-hidden shrink-0 bg-neutral-200" data-alt="Classic portrait of Pramoedya Ananta Toer wearing his signature glasses and batik, gentle dignified expression, archival photography style." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuCVI_lxmu64GpXQ57UnDbReOWmuPXDXCLovIY5YiL_T6Aq3p1H6cLNAfPdQdR6YBfQq4vJarvV7K8uhAdu74-z4wVnpOZTsGbt6qGnkNhzg_M-hPhakHX4TNtm4rNOqpyg8ONCagZLewRwnjj8ygqSKTq-yrwoyqIfBcq2mX_RY2YERNQTrinMNxOA_KuV87toETyKHNUYTyCDsZnrgbIisem-C6UyKQP1Ur4tqVXk&quot;);"></div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-1.5">
<span class="font-display font-extrabold text-base text-brand-dark truncate">Pramoedya Ananta Toer</span>
<span class="material-symbols-outlined text-brand-blue text-sm" style="font-variation-settings: &quot;FILL&quot; 1;" title="Penulis Legendaris Terverifikasi">verified</span>
</div>
<p class="text-xs text-neutral-600 font-semibold truncate">Sastrawan Besar Indonesia • 1925 – 2006</p>
</div>
<a class="neo-pill px-3 py-1.5 text-xs bg-white hover:bg-neutral-50 text-brand-dark shrink-0" href="#penulis-section">
            Biografi
          </a>
</div>
<!-- Metrik & Rating Interaktif -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
<div class="neo-card p-3 bg-brand-cream border-2 border-brand-dark text-center">
<div class="flex items-center justify-center gap-1 text-brand-yellow text-sm font-extrabold">
<span class="material-symbols-outlined text-base" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="text-brand-dark text-base">4.9</span>
</div>
<span class="text-[11px] font-bold text-neutral-600 block mt-0.5">3.840 Ulasan</span>
</div>
<div class="neo-card p-3 bg-brand-cream border-2 border-brand-dark text-center">
<div class="flex items-center justify-center gap-1 text-brand-blue text-sm font-extrabold">
<span class="material-symbols-outlined text-base">auto_stories</span>
<span class="text-brand-dark text-base">48.2k</span>
</div>
<span class="text-[11px] font-bold text-neutral-600 block mt-0.5">Kali Dibaca</span>
</div>
<div class="neo-card p-3 bg-brand-cream border-2 border-brand-dark text-center">
<div class="flex items-center justify-center gap-1 text-brand-coral text-sm font-extrabold">
<span class="material-symbols-outlined text-base">timer</span>
<span class="text-brand-dark text-base">8.5 Jam</span>
</div>
<span class="text-[11px] font-bold text-neutral-600 block mt-0.5">535 Halaman</span>
</div>
<div class="neo-card p-3 bg-brand-cream border-2 border-brand-dark text-center">
<div class="flex items-center justify-center gap-1 text-brand-green text-sm font-extrabold">
<span class="material-symbols-outlined text-base">translate</span>
<span class="text-brand-dark text-base">ID/EYD</span>
</div>
<span class="text-[11px] font-bold text-neutral-600 block mt-0.5">+ Glosarium</span>
</div>
</div>
<!-- Tab Navigasi Sinopsis & Konteks -->
<div class="space-y-4 pt-2">
<div class="flex items-center gap-2 border-b-2 border-brand-dark/15 pb-2 overflow-x-auto">
<button class="tab-btn neo-pill px-4 py-1.5 text-xs bg-brand-dark text-white" data-target="tab-synopsis">Sinopsis Lengkap</button>
<button class="tab-btn neo-pill px-4 py-1.5 text-xs bg-white text-brand-dark hover:bg-neutral-100" data-target="tab-curator">Catatan Kurator Arsip</button>
<button class="tab-btn neo-pill px-4 py-1.5 text-xs bg-white text-brand-dark hover:bg-neutral-100" data-target="tab-history">Konteks Sejarah (1898)</button>
</div>
<!-- Konten Tab: Sinopsis -->
<div class="tab-pane space-y-3 text-sm text-neutral-800 leading-relaxed font-medium" id="tab-synopsis">
<p>
              Berlatar akhir abad ke-19 di Surabaya dan Wonokromo, <strong class="font-extrabold text-brand-dark">Bumi Manusia</strong> menuturkan perjalanan Minke, seorang pemuda pribumi berdarah priyayi yang berhasil menembus pendidikan bergengsi di H.B.S. (Hoogere Burgerschool)—sekolah yang lazimnya hanya dinikmati keturunan Eropa murni. Minke adalah sosok pemikir bebas, penulis berbakat dalam bahasa Belanda, dan pengagum pencapaian revolusi industri modern Eropa.
            </p>
<p>
              Namun cakrawala pemikirannya diguncang ketika ia dipertemukan dengan keluarga Mellema di Boerderij Buitenzorg: seorang gadis jelita berdarah campuran bernama <strong class="font-extrabold text-brand-dark">Annelies Mellema</strong>, dan ibunya, <strong class="font-extrabold text-brand-dark">Nyai Ontosoroh</strong> (Sanikem). Sanikem, meski berstatus gundik yang dicibir strata sosial feodal kolonial, membuktikan dirinya sebagai pribadi berpendidikan autodidak, bermartabat tinggi, dan pengelola bisnis agrikultur yang tangguh tiada tanding.
            </p>
<p>
              Hubungan cinta Minke dan Annelies membentur dinding tebal sistem hukum kolonial Hindia Belanda yang diskriminatif, menguji batas keberanian, akal budi, dan harga diri manusia yang dijajah di negerinya sendiri.
            </p>
</div>
<!-- Konten Tab: Catatan Kurator (Hidden by default) -->
<div class="tab-pane hidden space-y-3 text-sm text-neutral-800 leading-relaxed font-medium" id="tab-curator">
<p>
              Naskah ini awalnya dinarasikan secara lisan oleh Pramoedya Ananta Toer kepada sesama tahanan politik di Kamp Konsentrasi Pulau Buru selama periode 1973–1975, ketika alat tulis dilarang keras. Restorasi digital libfree mengacu pada cetakan perdana Hasta Mitra (1980) yang disandingkan dengan arsip catatan terjemahan Max Lane.
            </p>
<div class="p-3 bg-brand-lavender rounded-xl border-2 border-brand-dark text-xs space-y-1">
<span class="font-bold block text-brand-dark">Tingkat Integritas Teks Digital:</span>
<p>100% akurasi teks asli, ortografi EYD disesuaikan tanpa mengubah satu pun istilah arkaik serapan bahasa Belanda, Melayu Klasik, maupun Jawa halus.</p>
</div>
</div>
<!-- Konten Tab: Konteks Sejarah (Hidden by default) -->
<div class="tab-pane hidden space-y-3 text-sm text-neutral-800 leading-relaxed font-medium" id="tab-history">
<p>
              Tahun 1898 adalah momentum peralihan abad di mana Hindia Belanda mulai merasakan denyut Politik Etis (*Ethische Politiek*). Karakter Minke terinspirasi nyata dari sosok perintis pers nasional dan pahlawan nasional, <strong class="font-extrabold text-brand-dark">Tirto Adhi Soerjo</strong> (Raden Djokomono).
            </p>
<p>
              Roman ini membedah pertentangan kelas antara Totok (Eropa murni), Indo (campuran), Priyayi feodal yang tunduk, serta kaum jelata yang tertindas oleh sistem kolonialisme.
            </p>
</div>
</div>
<!-- Spesifikasi & Metadata Grid Mini -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t-2 border-brand-dark/10 text-xs">
<div>
<span class="text-neutral-500 font-bold block">Tahun Terbit Asli</span>
<span class="font-extrabold text-brand-dark">1980 (Latar: 1898)</span>
</div>
<div>
<span class="text-neutral-500 font-bold block">Penerbit Digital</span>
<span class="font-extrabold text-brand-dark">Arsip Terbuka libfree</span>
</div>
<div>
<span class="text-neutral-500 font-bold block">ID Katalog Arsip</span>
<span class="font-extrabold text-brand-dark">LIB-1980-BURU01</span>
</div>
<div>
<span class="text-neutral-500 font-bold block">Lisensi Karya</span>
<span class="font-extrabold text-brand-dark">Domain Publik / CC0</span>
</div>
</div>
</div>
</div>
</section>
<!-- 3. FITUR INTERAKTIF BACA & INTISARI -->
<section class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full items-stretch">
  <!-- Kartu Kutipan Populer (7 Kolom) -->
  <div class="md:col-span-7 neo-card p-6 sm:p-8 bg-brand-yellow-light/60 border-2 border-brand-dark shadow-solid rounded-2xl flex flex-col justify-between relative overflow-hidden">
    <div class="space-y-4 relative z-10">
      <div class="flex items-center justify-between gap-3 flex-wrap">
        <span class="neo-pill px-3 py-1 text-xs bg-brand-coral text-brand-dark font-extrabold uppercase border-2 border-brand-dark shadow-sm">
          Paling Sering Ditandai
        </span>
        <button class="neo-pill px-3 py-1.5 text-xs bg-white hover:bg-neutral-100 text-brand-dark flex items-center gap-1.5 shadow-sm border-2 border-brand-dark transition-colors" id="copy-quote">
          <span class="material-symbols-outlined text-xs">content_copy</span>
          <span id="copy-text" class="font-bold">Salin Kutipan</span>
        </button>
      </div>
      <div class="pt-2">
        <span class="text-4xl sm:text-5xl font-serif text-brand-dark/20 font-bold block leading-none select-none -mb-3">“</span>
        <blockquote class="font-display font-extrabold text-lg sm:text-xl md:text-2xl text-brand-dark leading-snug sm:leading-7 md:leading-8 tracking-tight pl-2" id="quote-text">
          “Seorang terpelajar harus sudah berbuat adil sejak dalam pikiran, apalagi dalam perbuatan.”
        </blockquote>
      </div>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-6 mt-4 border-t-2 border-brand-dark/15 text-xs font-bold text-neutral-800 relative z-10">
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-coral border border-brand-dark"></span>
        <span class="font-extrabold text-sm text-brand-dark">Nyai Ontosoroh (Sanikem)</span>
        <span class="text-neutral-500 font-medium text-xs hidden sm:inline">• Tokoh Sentral</span>
      </div>
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border-2 border-brand-dark text-neutral-700 shadow-sm">
        <span class="material-symbols-outlined text-sm text-brand-coral" style="font-variation-settings: 'FILL' 1;">favorite</span>
        <span>2.148 Pembaca Menandai</span>
      </div>
    </div>
  </div>

  <!-- Kartu Distribusi Nilai Pembaca (5 Kolom) -->
  <div class="md:col-span-5 neo-card p-6 sm:p-8 bg-white border-2 border-brand-dark shadow-solid rounded-2xl flex flex-col justify-between">
    <div>
      <div class="flex items-start justify-between pb-4 border-b-2 border-brand-dark/10">
        <div>
          <h4 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-tight sm:leading-7">Distribusi Nilai</h4>
          <p class="text-xs text-neutral-500 font-bold mt-0.5">Berdasarkan 3.840 ulasan pembaca</p>
        </div>
        <div class="text-right shrink-0">
          <div class="flex items-center justify-end gap-1.5">
            <span class="material-symbols-outlined text-brand-yellow text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="font-display font-extrabold text-2xl text-brand-dark leading-none">4.9</span>
          </div>
          <span class="text-[11px] font-bold text-neutral-500 block mt-1">Skala 5.0</span>
        </div>
      </div>

      <!-- Breakdown Bar Nilai 5 ke 1 -->
      <div class="space-y-2.5 pt-4 text-xs font-bold">
        <!-- 5 Bintang -->
        <div class="flex items-center gap-2.5">
          <span class="w-16 shrink-0 flex items-center gap-1 text-neutral-700 font-extrabold">
            5 <span class="material-symbols-outlined text-xs text-brand-yellow" style="font-variation-settings: 'FILL' 1;">star</span>
          </span>
          <div class="flex-1 h-3.5 bg-neutral-100 rounded-full border-2 border-brand-dark overflow-hidden">
            <div class="h-full bg-brand-yellow" style="width: 88%;"></div>
          </div>
          <span class="w-10 text-right text-brand-dark font-extrabold">88%</span>
        </div>
        <!-- 4 Bintang -->
        <div class="flex items-center gap-2.5">
          <span class="w-16 shrink-0 flex items-center gap-1 text-neutral-700 font-extrabold">
            4 <span class="material-symbols-outlined text-xs text-brand-yellow" style="font-variation-settings: 'FILL' 1;">star</span>
          </span>
          <div class="flex-1 h-3.5 bg-neutral-100 rounded-full border-2 border-brand-dark overflow-hidden">
            <div class="h-full bg-brand-coral" style="width: 9%;"></div>
          </div>
          <span class="w-10 text-right text-brand-dark font-extrabold">9%</span>
        </div>
        <!-- 3 Bintang -->
        <div class="flex items-center gap-2.5">
          <span class="w-16 shrink-0 flex items-center gap-1 text-neutral-700 font-extrabold">
            3 <span class="material-symbols-outlined text-xs text-brand-yellow" style="font-variation-settings: 'FILL' 1;">star</span>
          </span>
          <div class="flex-1 h-3.5 bg-neutral-100 rounded-full border-2 border-brand-dark overflow-hidden">
            <div class="h-full bg-brand-blue" style="width: 2%;"></div>
          </div>
          <span class="w-10 text-right text-brand-dark font-extrabold">2%</span>
        </div>
        <!-- 2 Bintang -->
        <div class="flex items-center gap-2.5">
          <span class="w-16 shrink-0 flex items-center gap-1 text-neutral-700 font-extrabold">
            2 <span class="material-symbols-outlined text-xs text-brand-yellow" style="font-variation-settings: 'FILL' 1;">star</span>
          </span>
          <div class="flex-1 h-3.5 bg-neutral-100 rounded-full border-2 border-brand-dark overflow-hidden">
            <div class="h-full bg-brand-green" style="width: 1%;"></div>
          </div>
          <span class="w-10 text-right text-neutral-500 font-extrabold">&lt;1%</span>
        </div>
        <!-- 1 Bintang -->
        <div class="flex items-center gap-2.5">
          <span class="w-16 shrink-0 flex items-center gap-1 text-neutral-700 font-extrabold">
            1 <span class="material-symbols-outlined text-xs text-brand-yellow" style="font-variation-settings: 'FILL' 1;">star</span>
          </span>
          <div class="flex-1 h-3.5 bg-neutral-100 rounded-full border-2 border-brand-dark overflow-hidden">
            <div class="h-full bg-neutral-400" style="width: 1%;"></div>
          </div>
          <span class="w-10 text-right text-neutral-500 font-extrabold">&lt;1%</span>
        </div>
      </div>
    </div>

    <div class="pt-4 mt-2 border-t-2 border-brand-dark/10 flex items-center justify-between text-xs font-bold">
      <span class="text-brand-green flex items-center gap-1">
        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span> 97% Pembaca Puas
      </span>
      <a href="#btn-write-review" class="text-brand-dark hover:underline flex items-center gap-0.5">
        <span>Lihat Semua Ulasan</span>
        <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </a>
    </div>
  </div>
</section>
<!-- 4. DAFTAR ISI & STRUKTUR BAB -->

<!-- 5. TENTANG PENULIS & TETRALOGI BURU -->
<section class="neo-card p-6 sm:p-8 bg-brand-cream shadow-solid-lg space-y-6" id="penulis-section">
<div class="flex flex-col md:flex-row items-start md:items-center gap-6">
<div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl border-2 border-brand-dark bg-white overflow-hidden shrink-0 shadow-solid" data-alt="High contrast monochromatic portrait photograph of Pramoedya Ananta Toer in late adulthood with profound thoughtful expression, framed in neo-brutalist outline." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuAC3bU7xIJo0CpaNwC9DfKfVgH8XrIBvS0KL-MaOQONdK-hBOgeo9EWAgyDRBHzEHVpPwj43S7LIHGtqWbINSKDPUJSQS5WY940aXPDBek3y36ViN4Gzr3ZmgOIWRPOpAuFDcdA1r75Jbrc5bF9gl08wmrCP4xPFjEyjWut1OZmh4yPkR3q_eZ0sYdu28VH2Q-Ni72Li3RkM5GpDwsx5T-LFAfJg8OU7FmZvcMo-Xo&quot;);"></div>
<div class="space-y-2 flex-1">
<div class="flex flex-wrap items-center gap-2">
<h3 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark">Pramoedya Ananta Toer</h3>
<span class="neo-pill px-3 py-0.5 text-xs bg-brand-yellow text-brand-dark font-extrabold">Kandidat Nobel Sastra</span>
</div>
<p class="text-xs sm:text-sm text-neutral-700 leading-relaxed sm:leading-5 font-medium">
          Dilahirkan di Blora (1925), Pramoedya adalah salah satu sastrawan terbesar Asia Tenggara. Karya-karyanya telah diterjemahkan ke dalam lebih dari 42 bahasa dunia. Menghabiskan belasan tahun dalam pembuangan politik tanpa peradilan di Pulau Buru, ia tetap melahirkan mahakarya literatur yang menjadi pilar kesadaran sejarah dan antikolonialisme modern.
        </p>
</div>
</div>
<!-- Serial Tetralogi Buru Lainnya -->
<div class="space-y-3 pt-4 border-t-2 border-brand-dark/10">
<h4 class="font-display font-extrabold text-base text-brand-dark">Urutan Serial Tetralogi Buru:</h4>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<!-- Buku 1: Aktif Saat Ini -->
<div class="neo-card p-4 bg-brand-yellow-light border-2 border-brand-dark relative">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-dark text-white font-extrabold mb-2">Sedang Dilihat</span>
<h5 class="font-display font-extrabold text-base text-brand-dark">1. Bumi Manusia</h5>
<p class="text-xs text-neutral-600 mt-1">Kebangkitan benih kesadaran diri dan pergolakan batin.</p>
</div>
<!-- Buku 2 -->
<a class="neo-card p-4 bg-white hover:bg-neutral-50 border-2 border-brand-dark group transition-transform hover:-translate-y-1" href="#anak-semua-bangsa">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-blue-light text-brand-dark font-extrabold mb-2">Buku ke-2</span>
<h5 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-blue">2. Anak Semua Bangsa</h5>
<p class="text-xs text-neutral-600 mt-1">Minke terjun menyelami kepedihan rakyat jelata pedesaan.</p>
</a>
<!-- Buku 3 -->
<a class="neo-card p-4 bg-white hover:bg-neutral-50 border-2 border-brand-dark group transition-transform hover:-translate-y-1" href="#jejak-langkah">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-green-light text-brand-dark font-extrabold mb-2">Buku ke-3</span>
<h5 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-green">3. Jejak Langkah</h5>
<p class="text-xs text-neutral-600 mt-1">Pelopor organisasi pribumi modern &amp; surat kabar nasional.</p>
</a>
<!-- Buku 4 -->
<a class="neo-card p-4 bg-white hover:bg-neutral-50 border-2 border-brand-dark group transition-transform hover:-translate-y-1" href="#rumah-kaca">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-coral-light text-brand-dark font-extrabold mb-2">Buku ke-4</span>
<h5 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-coral">4. Rumah Kaca</h5>
<p class="text-xs text-neutral-600 mt-1">Sudut pandang mata-mata kolonial mengawasi pergerakan.</p>
</a>
</div>
</div>
</section>
<!-- 6. BUKU SERUPA / REKOMENDASI REDAKSI -->
<section class="space-y-6">
<div class="flex items-center justify-between">
<div>
<h3 class="font-display font-extrabold text-2xl text-brand-dark">Koleksi Sastra Klasik Terkait</h3>
<p class="text-xs sm:text-sm text-brand-muted font-bold">Rekomendasi kurator untuk penjelajahan sejarah nusantara</p>
</div>
<a class="neo-pill px-4 py-2 text-xs bg-white hover:bg-neutral-100 text-brand-dark shadow-solid hidden sm:inline-flex" href="#katalog">
        Lihat Semua Klasik →
      </a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
<!-- Rekomendasi 1: Tenggelamnya Kapal Van der Wijck -->
<div class="neo-card p-5 bg-white shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-transform">
<div class="space-y-3">
<div class="w-full aspect-[4/5] rounded-xl bg-brand-peach border-2 border-brand-dark relative overflow-hidden flex items-end p-3" data-alt="Cover artwork for Tenggelamnya Kapal Van der Wijck novel, vintage West Sumatra Minangkabau sailing ship background in warm editorial pastel style." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuCaUUV0WWXHpXvaKjtrM5sj4dXadboAlpEErRvrWdXk406ADSpGRvD1Qh0cxKcTtXtg9oV0cgf-ik-5TrtrpQ2SikgV-ymHjoP7OHl_9OpDQtVF3U2lGzY2QwMd4vi54nzboWWUHmn6ftXWsW1rkWV4dkTLBtBkMI8H6y1RcVPaY4TRxOkcLGhUQxWrhqESFe22cGffKILSBtRb_W2EGOoNJSMt6O53ZqmfSsOLMJo&quot;);">
<span class="neo-pill px-2.5 py-0.5 text-[10px] bg-white text-brand-dark font-extrabold shadow-sm">Klasik Romantik</span>
</div>
<div>
<div class="flex items-center gap-1 text-brand-yellow text-xs font-bold mb-1">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span> 4.8
            </div>
<h4 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-coral leading-snug">
              Tenggelamnya Kapal Van der Wijck
            </h4>
<p class="text-xs font-bold text-neutral-500 mt-0.5">Hamka (1938)</p>
</div>
</div>
<a class="neo-pill w-full py-2 bg-brand-cream hover:bg-brand-yellow text-xs font-extrabold text-center mt-4" href="#baca-hamka">
          Buka Buku
        </a>
</div>
<!-- Rekomendasi 2: Salah Asuhan -->
<div class="neo-card p-5 bg-white shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-transform">
<div class="space-y-3">
<div class="w-full aspect-[4/5] rounded-xl bg-brand-blue-light border-2 border-brand-dark relative overflow-hidden flex items-end p-3" data-alt="Book cover artistic rendering for Salah Asuhan by Abdul Muis, early 20th century colonial Batavia imagery with soft blue and earth tones." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuA-lqpq0-tpYMktt2OG5Qj3dDSuSsq-DRzn7yePA-AGHLdwXAVVL5IdGXThXphWOVRHcj1OCK8eKpm8ATd1yOJfCUIEhIcV-yxUHZo27eXGpRud9CXG2vkDHXug964vFFSzfsDMijfHV8iW8dyJ2Zivwo7AaPYbQyjDGvgFRWu29ypdTbMOD_go-C_KTiWp6VdJ-reS3-Bn9MbY5c623YjORMAqLGfyDH5DoN1OB8Q&quot;);">
<span class="neo-pill px-2.5 py-0.5 text-[10px] bg-white text-brand-dark font-extrabold shadow-sm">Balai Pustaka</span>
</div>
<div>
<div class="flex items-center gap-1 text-brand-yellow text-xs font-bold mb-1">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span> 4.7
            </div>
<h4 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-blue leading-snug">
              Salah Asuhan
            </h4>
<p class="text-xs font-bold text-neutral-500 mt-0.5">Abdul Muis (1928)</p>
</div>
</div>
<a class="neo-pill w-full py-2 bg-brand-cream hover:bg-brand-blue text-xs font-extrabold text-center mt-4" href="#baca-abdul-muis">
          Buka Buku
        </a>
</div>
<!-- Rekomendasi 3: Atheis -->
<div class="neo-card p-5 bg-white shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-transform">
<div class="space-y-3">
<div class="w-full aspect-[4/5] rounded-xl bg-brand-lavender border-2 border-brand-dark relative overflow-hidden flex items-end p-3" data-alt="Artistic book cover illustration for Atheis by Achdiat K. Mihardja, philosophical Bandung 1940s mood, subtle geometric expressive shapes." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDv6x-BPl8gqEXSmmmIqjFyNGXeqgQFPjiKbItmhHXoluoF9z9pZhgMdPQd19l7NQG66IiL5ZWygrvurdnRMX1RUAY_nxJA7nOJ1NUYEn8TfvW4JYhdLeZxzjjHMsbRSRa8d9UjE_tryIAyIWClfqLNEAiJFAOwIr8gDpCmGS1QSjSg3YwNMXRTKsL6JdtQbCIovVjrA4_XeVnsxvTo88RLj2s66JY-7onuwmYRFjQ&quot;);">
<span class="neo-pill px-2.5 py-0.5 text-[10px] bg-white text-brand-dark font-extrabold shadow-sm">Sastra Eksistensial</span>
</div>
<div>
<div class="flex items-center gap-1 text-brand-yellow text-xs font-bold mb-1">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span> 4.6
            </div>
<h4 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-dark leading-snug">
              Atheis
            </h4>
<p class="text-xs font-bold text-neutral-500 mt-0.5">Achdiat K. Mihardja (1949)</p>
</div>
</div>
<a class="neo-pill w-full py-2 bg-brand-cream hover:bg-brand-yellow text-xs font-extrabold text-center mt-4" href="#baca-atheis">
          Buka Buku
        </a>
</div>
<!-- Rekomendasi 4: Max Havelaar -->
<div class="neo-card p-5 bg-white shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-transform">
<div class="space-y-3">
<div class="w-full aspect-[4/5] rounded-xl bg-brand-green-light border-2 border-brand-dark relative overflow-hidden flex items-end p-3" data-alt="Max Havelaar by Multatuli classic book jacket art, depicting coffee plantations of Lebak Banten during the Dutch colonial era, detailed vintage engraving style." style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuCcDD-QBFu-BmVsr4GmRYEyhFqIlnNDuF-qEQBYCMYkRaARiwhbzhc7992IYMUYYycDH3LabeXhs5HGdjHBmmN55WeBINUCxoP9QQyVGmqCcjUV0SVwQgTzaALJkMu20koybeV8APIdpy1zWtGW6LtO13peZ7eiLW_uPM1CUeKql58BXvUMmEhEwZV1chNuR_zazV7f_Y8oleTlHoDMBlXgfT6iyYCJkZShISXWkuM&quot;);">
<span class="neo-pill px-2.5 py-0.5 text-[10px] bg-white text-brand-dark font-extrabold shadow-sm">Gugatan Kolonial</span>
</div>
<div>
<div class="flex items-center gap-1 text-brand-yellow text-xs font-bold mb-1">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span> 4.9
            </div>
<h4 class="font-display font-extrabold text-base text-brand-dark group-hover:text-brand-green leading-snug">
              Max Havelaar
            </h4>
<p class="text-xs font-bold text-neutral-500 mt-0.5">Multatuli (1860)</p>
</div>
</div>
<a class="neo-pill w-full py-2 bg-brand-cream hover:bg-brand-green text-xs font-extrabold text-center mt-4" href="#baca-multatuli">
          Buka Buku
        </a>
</div>
</div>
</section>
<!-- 7. KOMENTAR & ULASAN PEMBACA -->
<section class="neo-card p-6 sm:p-8 bg-white shadow-solid space-y-6">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<div>
<h3 class="font-display font-extrabold text-2xl text-brand-dark">Ulasan &amp; Diskusi Komunitas</h3>
<p class="text-xs sm:text-sm text-brand-muted font-bold">3.840 pembaca telah membagikan catatan pemikiran</p>
</div>
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-extrabold shadow-solid" id="btn-write-review">
        + Tulis Ulasan Kamu
      </button>
</div>
<!-- Form Tulis Ulasan (Default Tersembunyi, Toggle via JS) -->
<div class="hidden p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark space-y-3" id="review-form-container">
<h4 class="font-display font-bold text-sm text-brand-dark">Kirimkan Ulasanmu untuk Bumi Manusia:</h4>
<div class="flex items-center gap-2 text-xs font-bold">
<span>Beri Nilai:</span>
<div class="flex text-brand-yellow cursor-pointer">
<span class="material-symbols-outlined text-lg" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-lg" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-lg" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-lg" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-lg" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
</div>
</div>
<textarea class="w-full neo-card p-3 text-xs sm:text-sm border-2 border-brand-dark focus:ring-0" placeholder="Tuliskan analisis, kesan, atau bab yang paling berkesan bagimu..." rows="3"></textarea>
<div class="flex justify-end gap-2">
<button class="neo-pill px-4 py-1.5 text-xs bg-white text-brand-dark" id="cancel-review">Batal</button>
<button class="neo-pill px-4 py-1.5 text-xs bg-brand-green text-brand-dark" id="submit-review">Kirimkan Ulasan</button>
</div>
</div>
<!-- Daftar 3 Ulasan Teratas -->
<div class="space-y-4">
<!-- Ulasan 1 -->
<div class="p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark space-y-3">
<div class="flex items-start justify-between gap-3">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-brand-coral border-2 border-brand-dark flex items-center justify-center font-extrabold text-sm text-brand-dark">
              DK
            </div>
<div>
<div class="flex items-center gap-1.5">
<span class="font-display font-extrabold text-sm text-brand-dark">Dewi Kartika</span>
<span class="neo-pill px-2  bg-brand-green/20 text-brand-green text-[10px] font-extrabold">
                  ✓ Pembaca Terverifikasi
                </span>
</div>
<span class="text-[11px] font-semibold text-neutral-500">Menyelesaikan membaca dalam 9 jam • 2 hari lalu</span>
</div>
</div>
<div class="flex text-brand-yellow">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
</div>
</div>
<p class="text-xs sm:text-sm text-neutral-800 leading-relaxed sm:leading-5 font-medium">
          Sosok Nyai Ontosoroh adalah puncak penggambaran keteguhan karakter wanita Indonesia di tengah kungkungan feodalisme dan supremasi kolonial Belanda. Dialognya tentang berbuat adil sejak dalam pikiran membuat saya merinding setiap kali membacanya ulang di libfree.
        </p>
<div class="flex items-center gap-4 text-xs font-bold text-neutral-600 pt-1">
<button class="hover:text-brand-dark flex items-center gap-1">
<span class="material-symbols-outlined text-sm">thumb_up</span> 142 Bermanfaat
          </button>
<span>•</span>
<a class="hover:text-brand-dark" href="#balas">Balas Diskusi</a>
</div>
</div>
<!-- Ulasan 2 -->
<div class="p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark space-y-3">
<div class="flex items-start justify-between gap-3">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-brand-blue border-2 border-brand-dark flex items-center justify-center font-extrabold text-sm text-brand-dark">
              RH
            </div>
<div>
<div class="flex items-center gap-1.5">
<span class="font-display font-extrabold text-sm text-brand-dark">Rian Hidayat</span>
<span class="neo-pill px-2  bg-brand-yellow/30 text-brand-dark text-[10px] font-extrabold">
                  ✓ Pengkaji Arsip
                </span>
</div>
<span class="text-[11px] font-semibold text-neutral-500">Membaca via Web Reader • 1 minggu lalu</span>
</div>
</div>
<div class="flex text-brand-yellow">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
</div>
</div>
<p class="text-xs sm:text-sm text-neutral-800 leading-relaxed sm:leading-5 font-medium">
          Format EPUB dari libfree sangat bersih dan rapi. Glosarium istilah bahasa Belanda-Jawa abad ke-19 disertakan di catatan kaki interaktif, membuat konteks hukum pengadilan putih (*Raad van Justitie*) di bab terakhir sangat jernih dipahami.
        </p>
<div class="flex items-center gap-4 text-xs font-bold text-neutral-600 pt-1">
<button class="hover:text-brand-dark flex items-center gap-1">
<span class="material-symbols-outlined text-sm">thumb_up</span> 98 Bermanfaat
          </button>
<span>•</span>
<a class="hover:text-brand-dark" href="#balas">Balas Diskusi</a>
</div>
</div>
<!-- Ulasan 3 -->
<div class="p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark space-y-3">
<div class="flex items-start justify-between gap-3">
<div class="w-10 h-10 rounded-full bg-brand-green border-2 border-brand-dark flex items-center justify-center font-extrabold text-sm text-brand-dark">
            AS
          </div>
<div class="flex-1">
<div class="flex items-center gap-1.5">
<span class="font-display font-extrabold text-sm text-brand-dark">Arya Setyawan</span>
<span class="neo-pill px-2  bg-brand-green/20 text-brand-green text-[10px] font-extrabold">
                ✓ Pembaca Terverifikasi
              </span>
</div>
<span class="text-[11px] font-semibold text-neutral-500">Membaca via EPUB Reader • 2 minggu lalu</span>
</div>
<div class="flex text-brand-yellow">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: &quot;FILL&quot; 1;">star_half</span>
</div>
</div>
<p class="text-xs sm:text-sm text-neutral-800 leading-relaxed sm:leading-5 font-medium">
          Ending buku ini adalah salah satu penutup paling membekas dalam sejarah novel Indonesia: "Kita telah melawan, Nak, Nyo. Sebaik-baiknya, sehormat-hormatnya." Luar biasa berterima kasih pada inisiatif digitalisasi terbuka ini.
        </p>
<div class="flex items-center gap-4 text-xs font-bold text-neutral-600 pt-1">
<button class="hover:text-brand-dark flex items-center gap-1">
<span class="material-symbols-outlined text-sm">thumb_up</span> 204 Bermanfaat
          </button>
<span>•</span>
<a class="hover:text-brand-dark" href="#balas">Balas Diskusi</a>
</div>
</div>
</div>
</section>
<!-- Interactive Inline Script -->

</div></main>
</template>

<script setup>
import { onMounted, ref } from 'vue'

// Port of the template's inline interaction handlers, scoped to this page.
const root = ref(null)

onMounted(() => {
  const $ = (selector) => root.value?.querySelector(selector)
  const $$ = (selector) => root.value?.querySelectorAll(selector) ?? []

  // 1. Tab switcher: Sinopsis / Catatan Kurator / Konteks Sejarah
  const tabBtns = $$('.tab-btn')
  const tabPanes = $$('.tab-pane')

  tabBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      tabBtns.forEach((b) => {
        b.classList.remove('bg-brand-dark', 'text-white')
        b.classList.add('bg-white', 'text-brand-dark')
      })
      tabPanes.forEach((p) => p.classList.add('hidden'))

      btn.classList.add('bg-brand-dark', 'text-white')
      btn.classList.remove('bg-white', 'text-brand-dark')

      const pane = $('#' + btn.getAttribute('data-target'))
      if (pane) pane.classList.remove('hidden')
    })
  })

  // 2. Format menu dropdown toggle
  const formatToggle = $('#format-toggle')
  const formatMenu = $('#format-menu')
  if (formatToggle && formatMenu) {
    formatToggle.addEventListener('click', () => formatMenu.classList.toggle('hidden'))
  }

  // 3. Bookmark button toggle state
  const bookmarkBtn = $('#btn-bookmark')
  let bookmarked = false
  if (bookmarkBtn) {
    bookmarkBtn.addEventListener('click', () => {
      bookmarked = !bookmarked
      const badge = bookmarkBtn.querySelector('span:last-child')
      if (bookmarked) {
        bookmarkBtn.classList.remove('bg-brand-yellow')
        bookmarkBtn.classList.add('bg-brand-coral')
        bookmarkBtn.querySelector('span:nth-child(2)').textContent = 'Tersimpan'
        if (badge) badge.textContent = '1.421'
      } else {
        bookmarkBtn.classList.add('bg-brand-yellow')
        bookmarkBtn.classList.remove('bg-brand-coral')
        bookmarkBtn.querySelector('span:nth-child(2)').textContent = 'Simpan'
        if (badge) badge.textContent = '1.420'
      }
    })
  }

  // 4. Copy quote feedback
  const copyBtn = $('#copy-quote')
  const copyText = $('#copy-text')
  const quoteText = $('#quote-text')
  if (copyBtn && copyText && quoteText) {
    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(quoteText.textContent.trim()).then(() => {
        copyText.textContent = 'Tersalin!'
        copyBtn.classList.add('bg-brand-green', 'text-white')
        setTimeout(() => {
          copyText.textContent = 'Salin Kutipan'
          copyBtn.classList.remove('bg-brand-green', 'text-white')
        }, 2000)
      })
    })
  }

  // 5. Write-review form toggle
  const writeReviewBtn = $('#btn-write-review')
  const reviewForm = $('#review-form-container')
  const cancelReviewBtn = $('#cancel-review')
  const submitReviewBtn = $('#submit-review')

  if (writeReviewBtn && reviewForm) {
    writeReviewBtn.addEventListener('click', () => reviewForm.classList.toggle('hidden'))
  }
  if (cancelReviewBtn && reviewForm) {
    cancelReviewBtn.addEventListener('click', () => reviewForm.classList.add('hidden'))
  }
  if (submitReviewBtn && reviewForm) {
    submitReviewBtn.addEventListener('click', () => {
      alert('Terima kasih! Ulasan Anda telah berhasil diterbitkan ke komunitas pembaca libfree.')
      reviewForm.classList.add('hidden')
    })
  }

  // 6. Share API trigger
  const shareBtn = $('#btn-share')
  if (shareBtn) {
    shareBtn.addEventListener('click', () => {
      if (navigator.share) {
        navigator.share({
          title: 'Bumi Manusia - Pramoedya Ananta Toer | libfree',
          text: 'Baca karya klasik legendaris Bumi Manusia secara gratis dan legal di libfree.',
          url: window.location.href,
        })
      } else {
        navigator.clipboard.writeText(window.location.href)
        alert('Tautan halaman buku telah disalin ke papan klip!')
      }
    })
  }
})
</script>
