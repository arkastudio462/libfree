<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- Backdrop drawer mobile -->
<div v-if="sidebarOpen" class="fixed inset-0 z-[55] bg-brand-dark/40 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>
<!-- LEFT SIDEBAR: HERO, PANDUAN KURASI, FILTER, STATUS (drawer di mobile) -->
<aside class="fixed inset-y-0 left-0 z-[60] w-[86%] max-w-sm overflow-y-auto bg-brand-cream border-r-2 border-brand-dark p-4 space-y-6 transition-transform duration-300 ease-out lg:static lg:z-auto lg:col-span-3 lg:w-auto lg:max-w-none lg:overflow-visible lg:border-0 lg:bg-transparent lg:p-0 lg:translate-x-0" id="catalog-sidebar" aria-label="Filter dan informasi katalog" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full invisible lg:visible'">
<div class="flex items-center justify-between gap-3 lg:hidden">
<span class="font-display font-extrabold text-lg text-brand-dark">Filter &amp; Info Katalog</span>
<button class="w-8 h-8 rounded-full border-2 border-brand-dark bg-white hover:bg-brand-yellow-light flex items-center justify-center" type="button" aria-label="Tutup sidebar" @click="sidebarOpen = false">
<span class="material-symbols-outlined text-base">close</span>
</button>
</div>
<!-- TOP HERO & SEARCH CARD -->
<section class="w-full">
<div class="neo-card p-5 bg-white shadow-solid relative overflow-hidden">
<!-- Decorative Concentric Circles inside card -->
<div class="absolute -right-16 -top-16 w-56 h-56 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="absolute -right-8 -top-8 w-40 h-40 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="relative flex items-center gap-2 text-[11px] font-bold text-brand-muted mb-3 flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </RouterLink>
<span>/</span>
<span class="text-brand-dark px-2 py-0.5 rounded-full bg-brand-yellow/30 border border-brand-dark/20">Koleksi &amp; Katalog</span>
</nav>
<div class="relative">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-green-light border-2 border-brand-dark text-[11px] font-extrabold text-brand-dark mb-2.5">
<span class="w-2 h-2 rounded-full bg-brand-green animate-pulse"></span>
            Akses Publik Bebas Royalti &amp; Terbuka
          </div>
<h1 class="text-2xl font-extrabold font-display tracking-tight text-brand-dark leading-tight">
            Katalog &amp; Koleksi Buku Terbuka
          </h1>
<p class="mt-2 text-xs text-brand-muted font-medium leading-relaxed">
            Jelajahi <strong class="text-brand-dark font-bold">naskah klasik nusantara</strong> yang telah diverifikasi status lisensinya, ensiklopedia, sastra, dan buku sains gratis tanpa batasan kuota unduh harian.
          </p>
<!-- Quick Summary Micro-badge -->
<div class="neo-card-sm mt-4 p-3 bg-brand-cream border-2 border-brand-dark flex items-center gap-3">
<div class="w-9 h-9 rounded-xl bg-brand-coral border-2 border-brand-dark flex items-center justify-center font-bold text-white shadow-sm shrink-0">
<span class="material-symbols-outlined text-brand-dark text-lg">auto_stories</span>
</div>
<div>
<span class="text-[10px] font-bold text-brand-muted uppercase tracking-wider block">Total Terindeks</span>
<span class="text-base font-display font-extrabold text-brand-dark">{{ totalBooks > 0 ? numberFormat.format(totalBooks) : '…' }} Naskah</span>
</div>
</div>
<!-- Search & Command Bar -->
<div class="mt-4 pt-4 border-t-2 border-brand-dark/10">
<form class="space-y-2" @submit.prevent="submitSearch">
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-brand-dark text-lg pointer-events-none">search</span>
<input id="catalog-search" v-model="searchInput" class="w-full neo-pill pl-10 pr-3 py-2.5 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:border-brand-dark focus:bg-white text-brand-dark placeholder-brand-muted shadow-[2px_2px_0px_#1A1A1A] transition-all" placeholder="Cari judul, penulis, atau kata kunci..." type="text"/>
</div>
<div class="flex items-center gap-2">
<button class="neo-pill w-full px-4 py-2.5 text-xs bg-brand-yellow hover:bg-[#ebb035] border-2 border-brand-dark text-brand-dark font-bold shadow-[2px_2px_0px_#1A1A1A] flex items-center justify-center gap-2 cursor-pointer" type="submit">
<span>Cari Buku</span>
<span class="material-symbols-outlined text-base">arrow_forward</span>
</button>
</div>
</form>
</div>
</div>
</div>
</section>
<!-- CURATED HERO MINI-BANNER -->
<section class="w-full">
<div class="neo-card p-4 bg-gradient-to-r from-brand-peach via-white to-brand-green-light border-2 border-brand-dark shadow-solid space-y-3">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-xl bg-brand-yellow border-2 border-brand-dark flex items-center justify-center shrink-0 shadow-sm">
<span class="material-symbols-outlined text-xl text-brand-dark">workspace_premium</span>
</div>
<div class="min-w-0">
<span class="text-[10px] font-bold tracking-wider uppercase bg-brand-dark text-white px-2.5 py-0.5 rounded-full inline-block mb-1">Panduan Kurasi</span>
<h3 class="text-sm font-extrabold font-display text-brand-dark leading-snug">
            Bagaimana kurator memverifikasi status lisensi?
          </h3>
<p class="mt-1 text-[11px] text-brand-muted font-semibold leading-relaxed">Sumber naskah, tahun wafat penulis, dan status terjemahan diperiksa sebelum sebuah judul tayang.</p>
</div>
</div>
<RouterLink class="neo-pill w-full px-4 py-2 justify-center bg-brand-dark text-white hover:bg-brand-muted text-[11px] font-bold border-2 border-brand-dark shadow-sm flex items-center gap-2" to="/lisensi#domain-publik">
<span>Baca Panduan Lisensi</span>
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</RouterLink>
</div>
</section>
<!-- FILTER -->
<div class="neo-card p-5 bg-white border-2 border-brand-dark shadow-solid space-y-6">
<!-- Filter Header -->
<div class="flex items-center justify-between pb-3 border-b-2 border-brand-dark/10 gap-2">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-brand-dark font-bold">tune</span>
<h3 class="font-display font-extrabold text-base text-brand-dark">Filter Buku</h3>
</div>
<button class="text-xs font-bold text-brand-coral hover:underline cursor-pointer" type="button" @click="resetFilters">
          Reset Filter
        </button>
</div>
<!-- Kategori Filter -->
<div class="space-y-3">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block">Kategori Utama</span>
<div class="space-y-2 text-xs font-semibold text-brand-dark">
<label v-for="cat in categories" :key="cat.slug" class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox" :checked="filters.kategori.includes(cat.slug)" @change="toggleKategori(cat.slug)"/>
<span>{{ cat.name }}</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">{{ cat.books_count }}</span>
</label>
</div>
</div>
<!-- Format Unduhan -->
<div class="space-y-3 pt-3 border-t-2 border-brand-dark/10">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block">Format Tersedia</span>
<div class="grid grid-cols-2 gap-2 text-xs font-bold">
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-coral-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox" :checked="filters.format.includes('epub')" @change="toggleFormat('epub')"/>
<span>EPUB</span>
</label>
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-yellow-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox" :checked="filters.format.includes('pdf')" @change="toggleFormat('pdf')"/>
<span>PDF Asli</span>
</label>
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-blue-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox" :checked="filters.format.includes('txt')" @change="toggleFormat('txt')"/>
<span>Teks TXT</span>
</label>
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-green-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox" :checked="filters.format.includes('audio')" @change="toggleFormat('audio')"/>
<span>Buku Suara</span>
</label>
</div>
</div>
<!-- Rating Minimum -->
<div class="pt-3 border-t-2 border-brand-dark/10">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block mb-2">Nilai Kurasi Minimum</span>
<div class="flex gap-1.5">
<button v-for="option in ratingOptions" :key="option.value" class="neo-pill flex-1 py-1.5 text-xs font-bold border-2 border-brand-dark" :class="filters.min_rating === option.value ? 'bg-brand-yellow' : 'bg-brand-cream hover:bg-white'" type="button" @click="setMinRating(option.value)">
                {{ option.label }}
              </button>
</div>
</div>
</div>
<!-- STATUS TOOLBAR -->
<div class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid space-y-3">
<div class="flex items-start gap-2">
<span class="mt-1 w-2.5 h-2.5 rounded-full bg-brand-green border border-brand-dark shrink-0"></span>
<span class="text-xs font-extrabold text-brand-dark leading-relaxed">
              <template v-if="rangeLabel">Menampilkan <span class="underline decoration-brand-yellow decoration-2">{{ rangeLabel }}</span> dari {{ numberFormat.format(meta.total) }} Buku Terverifikasi</template>
              <template v-else>Tidak ada buku yang cocok dengan filter saat ini</template>
            </span>
</div>
<!-- Active Tags -->
<div v-if="activeTags.length" class="flex flex-wrap items-center gap-1.5">
<span v-for="tag in activeTags" :key="tag.key" class="neo-pill px-2.5 py-0.5 text-[11px] border border-brand-dark text-brand-dark font-bold flex items-center gap-1" :class="tag.classes">
              {{ tag.label }}
              <button class="hover:text-brand-coral text-xs" title="Hapus filter" type="button" @click="tag.remove">✕</button>
</span>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 space-y-1.5">
<label class="text-[10px] font-bold uppercase tracking-wider text-brand-muted block" for="catalog-sort">Urutkan</label>
<select id="catalog-sort" class="w-full neo-pill text-xs font-bold py-2 px-3 bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:border-brand-dark cursor-pointer shadow-sm" :value="filters.sort" @change="onSortChange">
<option value="">Paling Populer</option>
<option value="terbaru">Terbaru Diunggah</option>
<option value="rating">Rating Tertinggi</option>
<option value="judul">Abjad Judul (A–Z)</option>
<option value="tahun">Tahun Terbit (Terlama)</option>
</select>
<div class="flex items-center justify-between gap-2 pt-1">
<span class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">Tampilan</span>
<div class="flex items-center bg-brand-cream p-1 rounded-full border-2 border-brand-dark shadow-sm">
<button class="w-7 h-7 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center text-brand-dark shadow-xs" title="Tampilan Grid">
<span class="material-symbols-outlined text-base">grid_view</span>
</button>
<button class="w-7 h-7 rounded-full hover:bg-white flex items-center justify-center text-brand-muted transition-colors" title="Tampilan List">
<span class="material-symbols-outlined text-base">view_list</span>
</button>
</div>
</div>
</div>
</div>
</aside>
<!-- RIGHT: QUICK FILTERS, BOOK GRID, LOAD MORE -->
<div class="lg:col-span-9 space-y-6">
<!-- QUICK FILTER PILLS (CAROUSEL / WRAP) -->
<section class="w-full">
<div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-bold no-scrollbar">
<button class="neo-pill px-4 py-2 border-2 border-brand-dark whitespace-nowrap" :class="filters.kategori.length === 0 ? 'bg-brand-dark text-white shadow-[2px_2px_0px_#F4B942]' : 'bg-white text-brand-dark shadow-sm hover:bg-brand-coral-light'" type="button" @click="selectAllKategori">
        Semua ({{ totalBooks > 0 ? numberFormat.format(totalBooks) : '…' }})
      </button>
<button v-for="(cat, i) in categories" :key="cat.slug" class="neo-pill px-4 py-2 border-2 border-brand-dark whitespace-nowrap" :class="filters.kategori.includes(cat.slug) ? 'bg-brand-dark text-white shadow-[2px_2px_0px_#F4B942]' : ['bg-white text-brand-dark shadow-sm', pillHoverClasses[i % pillHoverClasses.length]]" type="button" @click="pillClick(cat)">
        {{ cat.name }} <span class="ml-1 text-[11px] opacity-75 font-normal">{{ cat.books_count }}</span>
      </button>
</div>
</section>
<!-- BOOK CARDS GRID (from /api/buku) -->
<div class="grid grid-cols-2 gap-4 sm:gap-6">
<template v-if="loading && !books.length">
<div class="neo-card p-8 bg-white border-2 border-brand-dark shadow-solid col-span-2 text-center text-sm font-bold text-brand-muted">
              Memuat buku…
            </div>
</template>
<template v-else-if="error">
<div class="neo-card p-8 bg-white border-2 border-brand-dark shadow-solid col-span-2 text-center space-y-3">
<p class="text-sm font-bold text-brand-coral">{{ error }}</p>
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-bold border-2 border-brand-dark" type="button" @click="fetchBooks">Coba Lagi</button>
</div>
</template>
<template v-else-if="!books.length">
<div class="neo-card p-8 bg-white border-2 border-brand-dark shadow-solid col-span-2 text-center space-y-3">
<span class="material-symbols-outlined text-4xl text-brand-muted">search_off</span>
<p class="text-sm font-bold text-brand-dark">Tidak ada buku yang cocok dengan filter saat ini.</p>
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-bold border-2 border-brand-dark" type="button" @click="resetFilters">Reset Filter</button>
</div>
</template>
<BookCard v-for="book in books" :key="book.id" :book="book" />
</div>
<!-- LOAD MORE -->
<div v-if="loadMoreError" class="text-center text-xs font-bold text-brand-coral" role="alert">{{ loadMoreError }}</div>
<div v-if="hasMore" class="flex justify-center pt-1">
<button class="neo-pill px-8 py-3 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-solid flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed" type="button" :disabled="loadingMore" @click="loadMore">
<span v-if="loadingMore" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
<span>{{ loadingMore ? 'Memuat…' : 'Muat Lebih Banyak' }}</span>
<span v-if="!loadingMore" class="material-symbols-outlined text-base">expand_more</span>
</button>
</div>
<div v-else-if="books.length" class="text-center text-xs font-bold text-brand-muted">
          Semua {{ numberFormat.format(meta.total) }} buku sudah ditampilkan.
        </div>
</div>
</div>
<!-- FLOATING SIDEBAR TRIGGER (MOBILE) -->
<button class="lg:hidden fixed bottom-[calc(1.25rem+env(safe-area-inset-bottom))] right-4 z-40 neo-pill px-4 py-3 bg-brand-dark text-white border-2 border-brand-dark shadow-solid flex items-center gap-2 text-xs font-extrabold" type="button" aria-controls="catalog-sidebar" :aria-expanded="sidebarOpen" aria-label="Buka filter dan info katalog" @click="sidebarOpen = true">
<span class="material-symbols-outlined text-base">tune</span>
<span>Filter</span>
</button>
<!-- FLOATING WORKOUT/SIGNUP INSPIRED CALLOUT (From Image Component Center) -->
<section class="mt-14 w-full">
<div class="neo-card p-6 sm:p-8 bg-brand-green-light border-2 border-brand-dark shadow-solid relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
<div class="space-y-2 max-w-xl">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-brand-dark text-xs font-bold text-brand-dark">
<span class="material-symbols-outlined text-sm text-brand-green">volunteer_activism</span>
          Kontribusi Komunitas
        </div>
<h3 class="text-2xl font-extrabold font-display text-brand-dark tracking-tight">
          Punya Naskah Domain Publik atau Karya Berlisensi Terbuka?
        </h3>
<p class="text-xs sm:text-sm text-brand-muted font-medium leading-relaxed sm:leading-5">
          Kirim naskah beserta tautan sumber dan bukti lisensinya. Kurator akan memeriksa metadata, sumber, serta status hak cipta sebelum naskah dapat tayang di katalog.
        </p>
</div>
<div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full md:w-auto">
<RouterLink class="neo-pill w-full sm:w-auto px-6 py-3 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark border-2 border-brand-dark font-extrabold text-xs shadow-solid text-center" to="/unggah">
          Mulai Unggah Naskah
        </RouterLink>
<RouterLink class="neo-pill w-full sm:w-auto px-5 py-3 bg-white hover:bg-gray-50 text-brand-dark border-2 border-brand-dark font-bold text-xs text-center shadow-xs" to="/lisensi#panduan">
          Panduan Lisensi &amp; Hak Cipta
        </RouterLink>
</div>
</div>
</section>
</main>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BookCard from '../components/BookCard.vue'

// Halaman katalog: sidebar drawer mobile, data-driven list, dan kartu buku bersama.
const sidebarOpen = ref(false)

function closeSidebar() {
  sidebarOpen.value = false
}

function handleEscape(event) {
  if (event.key === 'Escape') closeSidebar()
}

watch(sidebarOpen, (open) => {
  document.body.style.overflow = open ? 'hidden' : ''
})

// --- Data-driven catalog ------------------------------------------------
const route = useRoute()
const router = useRouter()

const filters = reactive({ q: '', kategori: [], format: ['epub', 'pdf'], min_rating: 0, sort: '' })
const searchInput = ref('')
const categories = ref([])
const books = ref([])
const meta = ref({ current_page: 1, last_page: 1, per_page: 12, total: 0 })
const loading = ref(false)
const loadingMore = ref(false)
const error = ref(null)
const loadMoreError = ref(null)

const numberFormat = new Intl.NumberFormat('id-ID')

const ratingOptions = [
  { value: 4.5, label: '★ 4.5+' },
  { value: 4, label: '★ 4.0+' },
  { value: 0, label: 'Semua' },
]

// Hover variants for the quick-filter pills (mirrors the template's palette).
const pillHoverClasses = [
  'hover:bg-brand-coral-light',
  'hover:bg-brand-yellow-light',
  'hover:bg-brand-blue-light',
  'hover:bg-brand-green-light',
  'hover:bg-brand-peach',
  'hover:bg-brand-lavender',
]

const totalBooks = computed(() => categories.value.reduce((sum, cat) => sum + (cat.books_count || 0), 0))
const isDefaultFormat = computed(() => filters.format.join(',') === 'epub,pdf')

const activeTags = computed(() => {
  const tags = []

  for (const slug of filters.kategori) {
    const category = categories.value.find((cat) => cat.slug === slug)
    tags.push({
      key: 'kategori-' + slug,
      label: category ? category.name : slug,
      classes: 'bg-brand-coral-light',
      remove: () => toggleKategori(slug),
    })
  }

  if (!isDefaultFormat.value) {
    tags.push({
      key: 'format',
      label: 'Format ' + (filters.format.length ? filters.format.map((f) => f.toUpperCase()).join(' • ') : 'Semua'),
      classes: 'bg-brand-yellow-light',
      remove: () => {
        filters.format = ['epub', 'pdf']
      },
    })
  }

  if (filters.q) {
    tags.push({
      key: 'q',
      label: 'Pencarian: ' + filters.q,
      classes: 'bg-brand-green-light',
      remove: () => {
        filters.q = ''
        searchInput.value = ''
      },
    })
  }

  if (filters.min_rating > 0) {
    tags.push({
      key: 'min_rating',
      label: 'Nilai kurasi ' + filters.min_rating.toFixed(1) + '+',
      classes: 'bg-brand-blue-light',
      remove: () => {
        filters.min_rating = 0
      },
    })
  }

  return tags
})

const rangeLabel = computed(() => {
  if (!books.value.length) return null

  return '1–' + numberFormat.format(books.value.length)
})

const hasMore = computed(() => meta.value.current_page < meta.value.last_page)

// --- Query-string sync --------------------------------------------------
function queryValue(query, key) {
  const value = query[key]
  return Array.isArray(value) ? value.join(',') : (value ?? '')
}

function loadFromQuery(query) {
  const kategori = queryValue(query, 'kategori')

  filters.q = queryValue(query, 'q')
  filters.kategori = kategori ? kategori.split(',').filter(Boolean) : []
  filters.format = 'format' in query ? queryValue(query, 'format').split(',').filter(Boolean) : ['epub', 'pdf']
  filters.min_rating = Math.max(0, parseFloat(queryValue(query, 'min_rating')) || 0)
  filters.sort = queryValue(query, 'sort')
  searchInput.value = filters.q
}

function queryFromFilters() {
  const query = {}

  if (filters.q) query.q = filters.q
  if (filters.kategori.length) query.kategori = filters.kategori.join(',')
  if (!isDefaultFormat.value) query.format = filters.format.join(',')
  if (filters.min_rating > 0) query.min_rating = String(filters.min_rating)
  if (filters.sort) query.sort = filters.sort

  return query
}

function queryFromRoute(query) {
  const q = queryValue(query, 'q')
  const kategori = queryValue(query, 'kategori')
  const format = 'format' in query ? queryValue(query, 'format') : 'epub,pdf'
  const minRating = Math.max(0, parseFloat(queryValue(query, 'min_rating')) || 0)
  const sort = queryValue(query, 'sort')

  const normalized = {}
  if (q) normalized.q = q
  if (kategori) normalized.kategori = kategori
  if (format !== 'epub,pdf') normalized.format = format
  if (minRating > 0) normalized.min_rating = String(minRating)
  if (sort) normalized.sort = sort

  return normalized
}

function syncUrl() {
  router.replace({ query: queryFromFilters() })
}

// --- Data loading --------------------------------------------------------
let fetchSeq = 0

function bookParams(page) {
  const params = new URLSearchParams()
  if (filters.q) params.set('q', filters.q)
  if (filters.kategori.length) params.set('kategori', filters.kategori.join(','))
  // Format is always sent: an empty value means "no format filter".
  params.set('format', filters.format.join(','))
  if (filters.min_rating > 0) params.set('min_rating', String(filters.min_rating))
  if (filters.sort) params.set('sort', filters.sort)
  params.set('page', String(page))

  return params
}

async function fetchBooks() {
  const seq = ++fetchSeq
  loading.value = true
  error.value = null
  loadMoreError.value = null

  try {
    const response = await fetch('/api/buku?' + bookParams(1).toString())
    if (!response.ok) throw new Error('HTTP ' + response.status)

    const payload = await response.json()
    if (seq !== fetchSeq) return

    books.value = payload.data
    meta.value = payload.meta
  } catch {
    if (seq !== fetchSeq) return
    error.value = 'Gagal memuat data katalog. Silakan coba lagi.'
  } finally {
    if (seq === fetchSeq) loading.value = false
  }
}

async function loadMore() {
  if (loading.value || loadingMore.value || !hasMore.value) return

  const seq = fetchSeq
  loadingMore.value = true
  loadMoreError.value = null

  try {
    const response = await fetch('/api/buku?' + bookParams(meta.value.current_page + 1).toString())
    if (!response.ok) throw new Error('HTTP ' + response.status)

    const payload = await response.json()
    if (seq !== fetchSeq) return

    const existing = new Set(books.value.map((book) => book.id))
    books.value = [...books.value, ...payload.data.filter((book) => !existing.has(book.id))]
    meta.value = payload.meta
  } catch {
    if (seq !== fetchSeq) return
    loadMoreError.value = 'Gagal memuat buku berikutnya. Silakan coba lagi.'
  } finally {
    loadingMore.value = false
  }
}

async function fetchCategories() {
  try {
    const response = await fetch('/api/kategori')
    if (!response.ok) throw new Error('HTTP ' + response.status)

    const payload = await response.json()
    categories.value = payload.data
  } catch {
    categories.value = []
  }
}

// --- Filter actions ------------------------------------------------------
function submitSearch() {
  filters.q = searchInput.value.trim()
}

function selectAllKategori() {
  filters.kategori = []
}

function pillClick(category) {
  const isAlone = filters.kategori.length === 1 && filters.kategori[0] === category.slug
  filters.kategori = isAlone ? [] : [category.slug]
}

function toggleKategori(slug) {
  filters.kategori = filters.kategori.includes(slug)
    ? filters.kategori.filter((item) => item !== slug)
    : [...filters.kategori, slug]
}

function toggleFormat(value) {
  filters.format = filters.format.includes(value)
    ? filters.format.filter((item) => item !== value)
    : [...filters.format, value]
}

function onSortChange(event) {
  filters.sort = event.target.value
}

function setMinRating(value) {
  filters.min_rating = filters.min_rating === value ? 0 : value
}

function resetFilters() {
  filters.q = ''
  searchInput.value = ''
  filters.kategori = []
  filters.format = ['epub', 'pdf']
  filters.min_rating = 0
  filters.sort = ''
}

// --- Lifecycle -----------------------------------------------------------
let stopFiltersWatch = null
let stopRouteWatch = null

onMounted(() => {
  loadFromQuery(route.query)
  fetchCategories()
  fetchBooks()
  window.addEventListener('keydown', handleEscape)

  stopFiltersWatch = watch(
    filters,
    () => {
      syncUrl()
      fetchBooks()
    },
    { deep: true },
  )

  stopRouteWatch = watch(
    () => route.query,
    (query) => {
      // Ignore the query updates produced by syncUrl() itself.
      if (JSON.stringify(queryFromRoute(query)) === JSON.stringify(queryFromFilters())) return
      loadFromQuery(query)
    },
  )
})

onBeforeUnmount(() => {
  stopFiltersWatch?.()
  stopRouteWatch?.()
  window.removeEventListener('keydown', handleEscape)
  document.body.style.overflow = ''
})
</script>

