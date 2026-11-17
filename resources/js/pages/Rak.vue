<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: ShelfPage -->
<section class="space-y-6" data-purpose="shelf-page">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </RouterLink>
<span>/</span>
<span class="px-2 py-0.5 rounded-full bg-brand-yellow/30 border border-brand-dark/20 text-brand-dark">Rak Baca</span>
</nav>

<div class="neo-card p-5 sm:p-8 bg-white shadow-solid relative overflow-hidden">
<div class="absolute -right-10 -top-10 w-40 h-40 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
<div class="space-y-2">
<span class="neo-pill bg-brand-green-light px-3 py-1 text-xs font-bold text-brand-dark">
            Koleksi Pribadi
          </span>
<h1 class="font-display font-extrabold text-2xl sm:text-4xl text-brand-dark tracking-tight">
            Rak Baca Saya
          </h1>
<p class="text-sm text-brand-muted font-medium max-w-xl leading-relaxed">
            {{ currentUser ? 'Halo, ' + currentUser.name + '. ' : '' }}Buku yang kamu simpan tersimpan di akun ini dan bisa dibuka dari perangkat mana pun.
          </p>
</div>
<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
<div class="neo-card-sm px-4 py-2.5 bg-brand-cream border-2 border-brand-dark flex items-center gap-2 justify-center">
<span class="material-symbols-outlined text-brand-dark">bookmark</span>
<span class="text-xs font-bold text-brand-dark">{{ loading ? '…' : books.length }} Buku Tersimpan</span>
</div>
<RouterLink class="neo-pill px-4 py-2.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-extrabold border-2 border-brand-dark shadow-xs justify-center" to="/katalog">
            + Tambah Buku
          </RouterLink>
</div>
</div>
</div>

<!-- Loading -->
<div v-if="loading" class="grid grid-cols-2 gap-4 sm:gap-6">
<div v-for="n in 3" :key="'skeleton-' + n" class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid animate-pulse">
<div class="w-full h-40 sm:h-48 rounded-2xl bg-brand-cream border-2 border-brand-dark/20 mb-4"></div>
<div class="h-3 w-2/3 bg-brand-cream rounded-full mb-2"></div>
<div class="h-4 w-full bg-brand-cream rounded-full mb-2"></div>
<div class="h-3 w-4/5 bg-brand-cream rounded-full"></div>
</div>
</div>

<!-- Error -->
<div v-else-if="error" class="neo-card p-8 bg-white border-2 border-brand-dark shadow-solid text-center space-y-3">
<span class="material-symbols-outlined text-4xl text-brand-coral">error</span>
<p class="text-sm font-bold text-brand-dark">{{ error }}</p>
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-bold border-2 border-brand-dark" type="button" @click="fetchShelf">Coba Lagi</button>
</div>

<!-- Empty -->
<div v-else-if="!books.length" class="neo-card p-8 sm:p-12 bg-white border-2 border-brand-dark shadow-solid text-center space-y-4">
<div class="w-16 h-16 mx-auto rounded-full bg-brand-yellow-light border-2 border-brand-dark flex items-center justify-center">
<span class="material-symbols-outlined text-3xl text-brand-dark">bookmark_border</span>
</div>
<div class="space-y-1">
<h2 class="font-display font-extrabold text-xl text-brand-dark">Rak bacamu masih kosong</h2>
<p class="text-sm text-brand-muted font-medium max-w-md mx-auto">
              Simpan buku dari katalog atau halaman detail dengan menekan ikon pembatas buku. Buku akan muncul di sini.
            </p>
</div>
<RouterLink class="neo-pill inline-flex px-6 py-3 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-solid" to="/katalog">
            Jelajahi Katalog
          </RouterLink>
</div>

<!-- Shelf grid -->
<div v-else class="grid grid-cols-2 gap-4 sm:gap-6">
<BookCard v-for="book in books" :key="book.id" :book="book" removable @removed="removeBook" />
</div>
</section>
<!-- END: ShelfPage -->
</main>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import BookCard from '../components/BookCard.vue'
import { apiFetch, errorMessage } from '../lib/api'
import { currentUser } from '../stores/auth'

const books = ref([])
const loading = ref(true)
const error = ref('')

async function fetchShelf() {
  loading.value = true
  error.value = ''

  try {
    const payload = await apiFetch('/api/rak?per_page=48')
    books.value = payload.data
  } catch (caught) {
    error.value = errorMessage(caught)
  } finally {
    loading.value = false
  }
}

function removeBook(id) {
  books.value = books.value.filter((book) => book.id !== id)
}

onMounted(fetchShelf)
</script>
