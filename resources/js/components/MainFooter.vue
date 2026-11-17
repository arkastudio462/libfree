<template>
<!-- BEGIN: MainFooter -->
<!-- Rounded white container with crisp black outline and rich sitemap -->
<footer class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12" data-purpose="site-footer">
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
<!-- Newsletter / contact box -->
<div class="pt-2">
<div class="rounded-2xl border-2 border-brand-dark bg-brand-cream p-4 space-y-2 max-w-sm">
<p class="text-xs font-extrabold text-brand-dark">Ada pertanyaan atau usulan koleksi?</p>
<p class="text-[11px] font-semibold text-brand-muted leading-relaxed">
                Kirim surel ke tim kurator. Kami membalas pada hari kerja.
              </p>
<a class="inline-flex text-[11px] font-extrabold text-brand-dark underline hover:text-brand-green" href="mailto:halo@libfree.id">halo@libfree.id</a>
</div>
</div>
</div>
<!-- Right: Links Columns -->
<div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-6 text-sm">
<div class="space-y-3">
<h4 class="font-display font-extrabold text-base text-brand-dark">Jelajahi</h4>
<ul class="space-y-2 text-xs sm:text-sm font-semibold text-brand-muted">
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/katalog">Jelajahi Katalog</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#kategori">Semua Kategori</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#koleksi-khusus">Koleksi Khusus</RouterLink></li>
<li><RouterLink class="hover:text-brand-coral transition-colors" to="/#peringkat">Peringkat Buku</RouterLink></li>
</ul>
</div>
<div class="space-y-3">
<h4 class="font-display font-extrabold text-base text-brand-dark">Komunitas</h4>
<ul class="space-y-2 text-xs sm:text-sm font-semibold text-brand-muted">
<li><RouterLink class="hover:text-brand-green transition-colors" to="/unggah">Unggah Naskah</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/lisensi#panduan">Panduan Hak Cipta</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/lisensi#creative-commons">Lisensi Creative Commons</RouterLink></li>
<li><RouterLink class="hover:text-brand-green transition-colors" to="/lisensi#takedown">Pengajuan Takedown</RouterLink></li>
</ul>
</div>
<div class="space-y-3">
<h4 class="font-display font-extrabold text-base text-brand-dark">Tentang Kami</h4>
<ul class="space-y-2 text-xs sm:text-sm font-semibold text-brand-muted">
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#tentang">Visi &amp; Misi</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/#faq">Pertanyaan Umum</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/kebijakan-privasi">Kebijakan Privasi</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/syarat-ketentuan">Syarat &amp; Ketentuan</RouterLink></li>
<li><RouterLink class="hover:text-brand-blue transition-colors" to="/lisensi">Lisensi &amp; Hak Cipta</RouterLink></li>
<li><a class="hover:text-brand-blue transition-colors" href="mailto:halo@libfree.id">Hubungi Tim</a></li>
</ul>
</div>
</div>
</div>
<!-- Bottom Bar -->
<div class="mt-10 pt-6 border-t-2 border-brand-dark/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-bold text-brand-muted">
<div>
          © {{ currentYear }} libfree. Dibuat dengan cinta untuk literasi Indonesia. Semua naskah yang tayang telah melewati audit lisensi.
        </div>
<div class="flex items-center gap-3 text-brand-dark">
<a class="hover:underline" href="mailto:halo@libfree.id">Email</a>
<span>•</span>
<RouterLink class="hover:underline" to="/lisensi">Lisensi</RouterLink>
<span>•</span>
<RouterLink class="hover:underline" to="/kebijakan-privasi">Privasi</RouterLink>
</div>
</div>
</div>
</footer>
<!-- END: MainFooter -->
</template>

<script setup>
const currentYear = new Date().getFullYear()
</script>
