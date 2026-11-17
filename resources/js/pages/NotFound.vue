<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16">
<!-- BEGIN: NotFoundPage -->
<section class="min-h-[60vh] flex items-center justify-center py-10" data-purpose="not-found">
<div class="neo-card w-full max-w-2xl p-8 sm:p-12 bg-white shadow-solid-lg text-center space-y-5 relative overflow-hidden">
<!-- Decorative rings -->
<div class="absolute -right-16 -top-16 w-48 h-48 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="absolute -left-20 -bottom-20 w-56 h-56 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>

<span class="neo-pill bg-brand-coral-light px-3.5 py-1 text-xs font-bold text-brand-dark relative z-10">
          Halaman Tidak Ditemukan
        </span>

<h1 class="font-display font-black text-6xl sm:text-8xl text-brand-dark tracking-tight leading-none relative z-10">
          404
        </h1>

<div class="space-y-2 relative z-10">
<h2 class="font-display font-extrabold text-xl sm:text-2xl text-brand-dark">
            Sepertinya halaman ini belum masuk arsip.
          </h2>
<p class="text-sm text-brand-muted font-medium max-w-md mx-auto leading-relaxed">
            Alamat yang kamu buka mungkin salah ketik, sudah dipindahkan, atau naskahnya belum tersedia di libfree.
          </p>
</div>

<div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2 relative z-10">
<RouterLink class="neo-pill w-full sm:w-auto px-6 py-3 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold shadow-solid" to="/">
            Kembali ke Beranda
          </RouterLink>
<RouterLink class="neo-pill w-full sm:w-auto px-6 py-3 bg-white hover:bg-brand-cream text-brand-dark text-sm font-bold border-2 border-brand-dark shadow-xs" to="/katalog">
            Jelajahi Katalog
          </RouterLink>
</div>

<form class="neo-card p-2 flex items-stretch sm:items-center gap-2 bg-brand-cream max-w-md mx-auto relative z-10" @submit.prevent="search">
<span class="material-symbols-outlined text-brand-muted pl-2">search</span>
<input v-model="query" aria-label="Cari buku" class="w-full border-0 p-0 text-sm font-semibold placeholder:text-brand-muted focus:ring-0 text-brand-dark bg-transparent" placeholder="Cari judul atau penulis…" type="text">
<button class="neo-pill shrink-0 px-4 py-2 bg-brand-dark text-white hover:bg-brand-muted text-xs font-bold" type="submit">
            Cari
          </button>
</form>
</div>
</section>
<!-- END: NotFoundPage -->
</main>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const query = ref('')

function search() {
  const q = query.value.trim()
  router.push(q ? { path: '/katalog', query: { q } } : { path: '/katalog' })
}
</script>
