<template>
<!-- BEGIN: MainFooter -->
<!-- Rounded white container with crisp black outline and rich sitemap -->
<footer class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 lg:pb-12" data-purpose="site-footer">
<div class="neo-card p-8 sm:p-12 bg-white shadow-solid">
<div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">
<!-- Left: Brand info & Newsletter -->
<div class="md:col-span-5 space-y-4">
<div class="flex items-center gap-2.5">
<div class="w-9 h-9 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center">
<svg class="w-5 h-5 text-brand-dark" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<span class="font-display font-extrabold text-2xl tracking-tight text-brand-dark">lib<span class="text-brand-green">free</span></span>
</div>
<p class="text-sm text-brand-muted font-medium leading-relaxed max-w-sm">
            Inisiatif perpustakaan nirkabel mandiri untuk merawat literasi, menyediakan naskah klasik nusantara, dan membebaskan akses pengetahuan untuk semua anak bangsa.
          </p>
<!-- Newsletter Box -->
<div class="pt-2">
<span class="text-xs font-bold text-brand-dark block mb-2">Langganan Rekomendasi Buku Mingguan:</span>
<form action="#" class="flex gap-2 max-w-sm">
<input aria-label="Email langganan buletin" class="neo-pill px-4 py-2 text-xs sm:text-sm bg-brand-cream border-2 border-brand-dark w-full focus:ring-0 focus:border-brand-dark" placeholder="nama@email.com" type="email">
<button class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark px-4 py-2 text-xs font-bold shrink-0" type="submit">
                Langganan
              </button>
</form>
</div>
</div>
<!-- Right: Links Columns -->
<div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-6 text-sm">
<div class="space-y-3">
<h4 class="font-display font-extrabold text-base text-brand-dark">Jelajahi</h4>
<ul class="space-y-2 text-xs sm:text-sm font-semibold text-brand-muted">
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/katalog">Buku Terpopuler</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#kategori">Semua Kategori</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#koleksi-khusus">Klasik Balai Pustaka</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#koleksi-khusus">Cerita Dongeng Anak</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#peringkat">Indeks Penulis</RouterLink></li>
</ul>
</div>
<div class="space-y-3">
<h4 class="font-display font-extrabold text-base text-brand-dark">Komunitas</h4>
<ul class="space-y-2 text-xs sm:text-sm font-semibold text-brand-muted">
<li><RouterLink class="hover:text-brand-green transition-colors" to="/#unggah">Unggah Naskah</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/#panduan">Panduan Hak Cipta</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/#relawan">Program Relawan</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/#donasi">Donasi Server</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/#api">API Pengembang</RouterLink></li>
</ul>
</div>
<div class="space-y-3">
<h4 class="font-display font-extrabold text-base text-brand-dark">Tentang Kami</h4>
<ul class="space-y-2 text-xs sm:text-sm font-semibold text-brand-muted">
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#tentang">Visi &amp; Misi</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#faq">Pertanyaan Umum</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#kebijakan">Kebijakan Privasi</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#lisensi">Lisensi CC</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#kontak">Hubungi Tim</RouterLink></li>
</ul>
</div>
</div>
</div>
<!-- Bottom Bar -->
<div class="mt-10 pt-6 border-t-2 border-brand-dark/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-bold text-brand-muted">
<div>
          © 2024 libfree. Dibuat dengan cinta untuk literasi Indonesia. Semua naskah berlisensi domain publik atau Creative Commons.
        </div>
<div class="flex items-center gap-4 text-brand-dark">
<a class="hover:underline" href="#">Twitter/X</a>
<span>•</span>
<a class="hover:underline" href="#">Instagram</a>
<span>•</span>
<a class="hover:underline" href="#">GitHub</a>
</div>
</div>
</div>
</footer>
<!-- END: MainFooter -->
</template>
