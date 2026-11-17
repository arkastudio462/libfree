<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: LegalPage -->
<section class="space-y-6" data-purpose="legal-page">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </RouterLink>
<span>/</span>
<span class="px-2 py-0.5 rounded-full bg-brand-blue-light border border-brand-dark/20 text-brand-dark">{{ content.title }}</span>
</nav>

<!-- Header -->
<div class="neo-card p-5 sm:p-8 bg-white shadow-solid relative overflow-hidden">
<div class="absolute -right-16 -top-16 w-48 h-48 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
<div class="space-y-2 max-w-3xl">
<span class="neo-pill px-3 py-1 text-xs font-bold text-brand-dark" :class="content.badgeClass">{{ content.kicker }}</span>
<h1 class="font-display font-extrabold text-2xl sm:text-4xl text-brand-dark tracking-tight">{{ content.title }}</h1>
<p class="text-sm text-brand-muted font-medium leading-relaxed">{{ content.summary }}</p>
<p class="text-[11px] font-bold text-brand-muted">
            Terakhir diperbarui: {{ content.updated }}
          </p>
</div>
<div class="w-16 h-16 rounded-full border-2 border-brand-dark flex items-center justify-center shrink-0" :class="content.iconClass">
<span class="material-symbols-outlined text-3xl text-brand-dark">{{ content.icon }}</span>
</div>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
<!-- Table of contents -->
<aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-3">
<div class="neo-card p-4 sm:p-5 bg-white shadow-solid space-y-3">
<div class="flex items-center justify-between gap-2">
<span class="neo-pill bg-brand-cream px-3 py-1 text-[11px] font-bold text-brand-dark">Isi Halaman</span>
<span class="text-[11px] font-bold text-brand-muted">{{ content.sections.length }} bagian</span>
</div>
<ol class="space-y-1.5">
<li v-for="(section, i) in content.sections" :key="section.id">
<a class="flex items-start gap-2 rounded-xl px-2.5 py-2 text-xs font-bold text-brand-dark/80 hover:bg-brand-yellow-light transition-colors" :href="'#' + section.id">
<span class="shrink-0 w-5 h-5 rounded-full border border-brand-dark/40 bg-brand-cream flex items-center justify-center text-[10px] font-extrabold">{{ i + 1 }}</span>
<span class="min-w-0">{{ section.title }}</span>
</a>
</li>
</ol>
<div class="pt-3 border-t-2 border-brand-dark/10 space-y-2">
<p class="text-[11px] font-semibold text-brand-muted leading-relaxed">
              Ada pertanyaan tentang dokumen ini?
            </p>
<a class="neo-pill w-full text-center px-3 py-2 text-[11px] font-bold bg-brand-yellow hover:bg-[#ebb035] text-brand-dark border-2 border-brand-dark" href="mailto:halo@libfree.id">
              halo@libfree.id
            </a>
</div>
</div>

<div class="neo-card p-4 bg-brand-green-light border-2 border-brand-dark shadow-xs space-y-1.5">
<div class="flex items-center gap-2 text-brand-dark font-extrabold text-xs">
<span class="material-symbols-outlined text-base">verified_user</span>
            Ringkasnya
          </div>
<p class="text-[11px] font-semibold text-brand-dark/80 leading-relaxed">{{ content.tldr }}</p>
</div>
</aside>

<!-- Document -->
<article class="lg:col-span-8 neo-card p-5 sm:p-8 lg:p-10 bg-white shadow-solid-lg space-y-8 min-w-0">
<div class="space-y-3">
<p v-for="(paragraph, i) in content.intro" :key="'intro-' + i" class="text-sm sm:text-base text-brand-dark/90 font-medium leading-relaxed">
            {{ paragraph }}
          </p>
</div>

<section v-for="(section, i) in content.sections" :id="section.id" :key="section.id" class="scroll-mt-28 space-y-3">
<div class="flex items-center gap-2.5">
<span class="w-7 h-7 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center text-xs font-extrabold text-brand-dark shrink-0">{{ i + 1 }}</span>
<h2 class="font-display font-extrabold text-lg sm:text-xl text-brand-dark tracking-tight">{{ section.title }}</h2>
</div>
<p v-for="(paragraph, j) in section.paragraphs || []" :key="'p-' + i + '-' + j" class="text-sm text-brand-dark/80 font-medium leading-relaxed">
            {{ paragraph }}
          </p>
<ul v-if="section.items?.length" class="space-y-2 pt-1">
<li v-for="(item, j) in section.items" :key="'li-' + i + '-' + j" class="flex items-start gap-2.5 text-sm text-brand-dark/80 font-medium leading-relaxed">
<span class="material-symbols-outlined text-brand-green text-base shrink-0 mt-0.5">check_circle</span>
<span>{{ item }}</span>
</li>
</ul>
</section>

<div class="pt-6 border-t-2 border-brand-dark/10 space-y-3">
<h2 class="font-display font-extrabold text-lg text-brand-dark">Butuh bantuan?</h2>
<p class="text-sm text-brand-dark/80 font-medium leading-relaxed">
            Kirim pertanyaanmu ke <a class="font-bold underline hover:text-brand-green" href="mailto:halo@libfree.id">halo@libfree.id</a>. Kami berusaha membalas pada hari kerja.
          </p>
<div class="flex flex-col sm:flex-row gap-2.5 pt-1">
<RouterLink class="neo-pill px-4 py-2.5 text-xs font-extrabold bg-brand-yellow hover:bg-[#ebb035] text-brand-dark border-2 border-brand-dark shadow-xs text-center" to="/katalog">
              Kembali Menjelajah
            </RouterLink>
<RouterLink class="neo-pill px-4 py-2.5 text-xs font-bold bg-white hover:bg-brand-cream text-brand-dark border-2 border-brand-dark shadow-xs text-center" to="/#faq">
              Lihat FAQ
            </RouterLink>
</div>
</div>
</article>
</div>
</section>
<!-- END: LegalPage -->
</main>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  doc: { type: String, default: 'privacy' },
})

const docs = {
  privacy: {
    kicker: 'Dokumen Legal',
    title: 'Kebijakan Privasi',
    icon: 'shield_person',
    iconClass: 'bg-brand-blue-light',
    badgeClass: 'bg-brand-blue-light',
    updated: '15 November 2026',
    summary: 'Kami hanya mengumpulkan data yang benar-benar dibutuhkan agar rak baca, riwayat, dan ulasanmu berfungsi. Tidak ada penjualan data, tidak ada iklan bertarget.',
    tldr: 'Akun, email, dan aktivitas baca dipakai untuk menjalankan layanan. Kamu bisa meminta ekspor atau penghapusan data kapan saja.',
    intro: [
      'libfree adalah perpustakaan digital terbuka. Privasi pembaca kami perlakukan sebagai bagian dari kebebasan membaca: seminimal mungkin data, sejelas mungkin penjelasan.',
      'Dokumen ini menjelaskan data apa yang kami simpan, mengapa, dan bagaimana kamu dapat mengendalikannya.',
    ],
    sections: [
      {
        id: 'data-yang-dikumpulkan',
        title: 'Data yang kami kumpulkan',
        paragraphs: [
          'Kami membedakan antara data yang wajib untuk menjalankan akun dan data opsional yang hanya aktif bila kamu menggunakan fitur tertentu.',
        ],
        items: [
          'Data akun: nama, alamat email, dan kata sandi yang disimpan dalam bentuk hash.',
          'Data rak baca: daftar buku yang kamu simpan beserta waktu penyimpanannya.',
          'Data teknis: alamat IP dan user agent dalam log server untuk keamanan dan pencegahan penyalahgunaan.',
          'Preferensi baca: ukuran teks dan bab terakhir disimpan di penyimpanan lokal peramban, bukan di server.',
        ],
      },
      {
        id: 'penggunaan-data',
        title: 'Bagaimana data digunakan',
        paragraphs: [
          'Data akun dipakai untuk autentikasi dan menampilkan identitasmu pada ulasan. Data rak baca dipakai untuk menyusun halaman Rak Baca dan rekomendasi sederhana.',
          'Kami tidak menjual, menyewakan, atau membagikan data pembaca kepada pengiklan. Log server hanya diakses bila diperlukan untuk menyelidiki gangguan keamanan.',
        ],
      },
      {
        id: 'cookie',
        title: 'Cookie dan penyimpanan lokal',
        paragraphs: [
          'libfree memakai cookie sesi untuk menjaga status masuk. Cookie ini tidak digunakan untuk pelacakan lintas situs.',
          'Preferensi tampilan pembaca disimpan di localStorage peramban. Kamu dapat menghapusnya kapan saja melalui pengaturan peramban tanpa kehilangan akun.',
        ],
      },
      {
        id: 'hak-pembaca',
        title: 'Hak kamu',
        paragraphs: [
          'Kamu berhak mengetahui data yang kami simpan, meminta koreksi, serta meminta penghapusan akun beserta data turunannya.',
          'Permintaan dapat dikirim ke halo@libfree.id. Kami akan memverifikasi kepemilikan akun sebelum memproses permintaan.',
        ],
      },
      {
        id: 'keamanan',
        title: 'Keamanan dan retensi',
        paragraphs: [
          'Kata sandi disimpan menggunakan algoritma hashing satu arah. Akses ke basis data dibatasi pada pengelola yang membutuhkan.',
          'Data akun disimpan selama akun aktif. Setelah penghapusan, data rak baca dan ulasan ikut dihapus atau dianonimkan dalam waktu 30 hari.',
        ],
      },
    ],
  },
  terms: {
    kicker: 'Dokumen Legal',
    title: 'Syarat & Ketentuan',
    icon: 'gavel',
    iconClass: 'bg-brand-yellow-light',
    badgeClass: 'bg-brand-yellow-light',
    updated: '15 November 2026',
    summary: 'Aturan sederhana agar libfree tetap aman, sopan, dan bebas untuk semua pembaca. Dengan membuat akun, kamu menyetujui ketentuan berikut.',
    tldr: 'Gunakan layanan secara wajar, hormati hak cipta, dan jangan menyalahgunakan akun atau konten komunitas.',
    intro: [
      'Dengan mengakses libfree atau membuat akun, kamu setuju untuk mematuhi syarat dan ketentuan ini. Bila tidak setuju, kamu tetap dapat membaca katalog sebagai tamu, tetapi tidak dapat menggunakan fitur akun.',
      'Ketentuan dapat diperbarui seiring pertumbuhan layanan. Perubahan penting akan diumumkan di halaman ini beserta tanggal pembaruan.',
    ],
    sections: [
      {
        id: 'penggunaan-layanan',
        title: 'Penggunaan layanan',
        paragraphs: [
          'libfree disediakan untuk membaca, menyimpan, dan mendiskusikan karya yang tersedia secara legal. Kamu bertanggung jawab atas keamanan kredensial akunmu.',
        ],
        items: [
          'Satu akun untuk satu pembaca; jangan berbagi kata sandi.',
          'Dilarang mengotomatiskan pengambilan konten secara masif tanpa izin tertulis.',
          'Dilarang mengunggah malware, spam, atau konten yang melanggar hukum.',
        ],
      },
      {
        id: 'akun',
        title: 'Akun dan kontribusi',
        paragraphs: [
          'Kontribusi ulasan dan unggahan naskah harus menghormati hak cipta serta privasi orang lain. Kami dapat meninjau, menyunting, atau menolak kontribusi yang melanggar aturan.',
          'Unggahan naskah hanya diterima bila karya berada dalam domain publik atau memiliki lisensi terbuka yang sah. Pemilik hak cipta dapat mengajukan takedown melalui halaman Lisensi & Hak Cipta.',
        ],
      },
      {
        id: 'kekayaan-intelektual',
        title: 'Kekayaan intelektual',
        paragraphs: [
          'Merek, antarmuka, dan kode libfree dilindungi hukum. Naskah yang diunggah tetap milik penciptanya atau pemegang haknya, dan tunduk pada lisensi yang dinyatakan pada metadata buku.',
          'Kutipan pendek untuk keperluan pendidikan atau ulasan diperbolehkan dengan mencantumkan sumber.',
        ],
      },
      {
        id: 'batasan-tanggung-jawab',
        title: 'Batasan tanggung jawab',
        paragraphs: [
          'Layanan disediakan apa adanya. Kami berusaha menjaga ketersediaan dan akurasi metadata, tetapi tidak menjamin layanan bebas gangguan sepenuhnya.',
          'libfree tidak bertanggung jawab atas kerugian tidak langsung yang timbul dari penggunaan layanan, sejauh diizinkan hukum yang berlaku.',
        ],
      },
      {
        id: 'penghentian',
        title: 'Penghentian akses',
        paragraphs: [
          'Kami dapat menangguhkan akun yang terbukti menyalahgunakan layanan, melanggar hak cipta, atau mengganggu pembaca lain. Kamu dapat berhenti menggunakan layanan dan meminta penghapusan akun kapan saja.',
        ],
      },
    ],
  },
  lisensi: {
    kicker: 'Lisensi & Hak Cipta',
    title: 'Lisensi, Hak Cipta & Panduan Unggah',
    icon: 'copyright',
    iconClass: 'bg-brand-coral-light',
    badgeClass: 'bg-brand-coral-light',
    updated: '15 November 2026',
    summary: 'Setiap naskah di libfree mencantumkan status lisensinya. Halaman ini menjelaskan cara membaca label tersebut dan cara mengunggah karya yang benar-benar bebas.',
    tldr: 'Domain publik atau Creative Commons saja. Selalu simpan bukti lisensi dan cantumkan sumber saat mengunggah.',
    intro: [
      'libfree hanya mendistribusikan naskah yang boleh dibagikan ulang secara legal. Status lisensi ditampilkan pada halaman detail buku dan metadata unduhan.',
      'Halaman ini juga menjadi panduan singkat bagi kontributor sebelum mengirim naskah.',
    ],
    sections: [
      {
        id: 'domain-publik',
        title: 'Domain publik',
        paragraphs: [
          'Karya domain publik bebas digunakan dan didistribusikan tanpa izin pemegang hak. Di Indonesia, masa berlaku hak cipta umumnya 70 tahun setelah pencipta wafat, dengan pengecualian tertentu.',
          'Pastikan edisi yang kamu unggah tidak menambahkan terjemahan, pengantar, atau ilustrasi baru yang masih berhak cipta.',
        ],
      },
      {
        id: 'creative-commons',
        title: 'Lisensi Creative Commons',
        paragraphs: [
          'Karya berlisensi CC boleh dibagikan selama ketentuan lisensinya dipatuhi. Periksa apakah lisensi mengizinkan penggunaan komersial dan modifikasi.',
        ],
        items: [
          'CC BY: wajib mencantumkan atribusi pencipta.',
          'CC BY-SA: turunan harus memakai lisensi yang sama.',
          'CC BY-NC: dilarang untuk penggunaan komersial.',
          'CC BY-ND: boleh dibagikan, tetapi tidak boleh diubah.',
        ],
      },
      {
        id: 'panduan',
        title: 'Panduan mengunggah naskah',
        paragraphs: [
          'Sebelum mengirim naskah, siapkan sumber, bukti lisensi, dan metadata dasar. Kurator akan memeriksa kelengkapan sebelum naskah dipublikasikan.',
        ],
        items: [
          'Cantumkan judul, penulis, tahun terbit, dan tautan sumber asli.',
          'Lampirkan tangkapan layar atau tautan yang menunjukkan status domain publik/CC.',
          'Gunakan hasil pindai atau OCR yang bersih; hindari edisi berhak cipta milik penerbit lain.',
          'Jangan mengunggah karya yang masih dalam masa hak cipta tanpa izin tertulis.',
        ],
      },
      {
        id: 'takedown',
        title: 'Pengajuan takedown',
        paragraphs: [
          'Pemegang hak cipta yang menemukan karyanya tersedia tanpa izin dapat mengirim laporan ke halo@libfree.id dengan subjek "Takedown Hak Cipta".',
          'Sertakan identitas karya, tautan halaman libfree, dan bukti kepemilikan hak. Kami akan menonaktifkan akses sementara selama proses verifikasi.',
        ],
      },
    ],
  },
}

const content = computed(() => docs[props.doc] ?? docs.privacy)
</script>
