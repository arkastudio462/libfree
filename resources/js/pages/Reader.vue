<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: ReaderPage -->
<section class="space-y-6" data-purpose="reader-page">
<!-- Loading -->
<div v-if="loading" class="space-y-4">
<div class="neo-card p-5 sm:p-6 bg-white shadow-solid animate-pulse">
<div class="h-4 w-32 bg-brand-cream rounded-full mb-3"></div>
<div class="h-8 w-2/3 bg-brand-cream rounded-full mb-2"></div>
<div class="h-4 w-1/2 bg-brand-cream rounded-full"></div>
</div>
<div class="neo-card p-6 sm:p-10 bg-white shadow-solid animate-pulse space-y-3">
<div v-for="n in 8" :key="'line-' + n" class="h-3 bg-brand-cream rounded-full" :class="n % 3 === 0 ? 'w-4/5' : 'w-full'"></div>
</div>
</div>

<!-- Error -->
<div v-else-if="error" class="neo-card p-8 sm:p-12 bg-white border-2 border-brand-dark shadow-solid text-center space-y-4">
<div class="w-16 h-16 mx-auto rounded-full bg-brand-coral-light border-2 border-brand-dark flex items-center justify-center">
<span class="material-symbols-outlined text-3xl text-brand-dark">auto_stories</span>
</div>
<div class="space-y-1">
<h1 class="font-display font-extrabold text-2xl text-brand-dark">Naskah belum bisa dibuka</h1>
<p class="text-sm text-brand-muted font-medium max-w-md mx-auto">{{ error }}</p>
</div>
<div class="flex flex-col sm:flex-row justify-center gap-3">
<RouterLink class="neo-pill px-5 py-2.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-xs" to="/katalog">
            Jelajahi Katalog
          </RouterLink>
<RouterLink v-if="slug" class="neo-pill px-5 py-2.5 bg-white hover:bg-brand-cream text-brand-dark text-sm font-bold border-2 border-brand-dark shadow-xs" :to="'/buku/' + slug">
            Halaman Buku
          </RouterLink>
</div>
</div>

<template v-else>
<!-- Reader header -->
<div class="neo-card p-5 sm:p-7 bg-white shadow-solid relative overflow-hidden">
<div class="absolute -right-12 -top-12 w-40 h-40 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 relative z-10">
<div class="space-y-2 min-w-0">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
              Beranda
            </RouterLink>
<span>/</span>
<RouterLink class="hover:text-brand-dark transition-colors" to="/katalog">Katalog</RouterLink>
<span>/</span>
<RouterLink class="hover:text-brand-dark transition-colors truncate max-w-[12rem]" :to="'/buku/' + book.slug">{{ book.title }}</RouterLink>
<span>/</span>
<span class="px-2 py-0.5 rounded-full bg-brand-yellow/30 border border-brand-dark/20 text-brand-dark">Baca</span>
</nav>
<h1 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight truncate">
            {{ book.title }}
          </h1>
<p class="text-sm text-brand-muted font-medium">
            {{ book.author?.name || 'Anonim' }}
            <span class="mx-1.5 text-brand-dark/30">•</span>
            <template v-if="isManuscript">Naskah {{ manuscriptFormatLabel }}</template>
            <template v-else>{{ chapters.length }} bab</template>
            <span class="mx-1.5 text-brand-dark/30">•</span>
            {{ book.pages }} halaman
          </p>
</div>
<div class="flex items-center gap-2 shrink-0">
<button class="neo-pill px-4 py-2.5 text-xs font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1.5 disabled:opacity-60" :class="bookmarked ? 'bg-brand-coral-light hover:bg-brand-coral/30' : 'bg-brand-yellow hover:bg-[#ebb035]'" type="button" :disabled="bookmarkBusy" @click="toggleBookmark">
<span class="material-symbols-outlined text-base">{{ bookmarked ? 'bookmark' : 'bookmark_border' }}</span>
<span>{{ bookmarked ? 'Tersimpan' : 'Simpan' }}</span>
</button>
<RouterLink class="neo-pill px-4 py-2.5 text-xs font-bold bg-white hover:bg-brand-cream text-brand-dark border-2 border-brand-dark shadow-xs flex items-center gap-1.5" :to="'/buku/' + book.slug">
<span class="material-symbols-outlined text-base">info</span>
            Detail
          </RouterLink>
</div>
</div>
<p v-if="bookmarkError" class="text-[11px] font-bold text-brand-coral text-right mt-2 relative z-10" role="alert">{{ bookmarkError }}</p>
</div>

<!-- Empty -->
<div v-if="!readerAvailable" class="neo-card p-8 sm:p-12 bg-white border-2 border-brand-dark shadow-solid text-center space-y-4">
<span class="material-symbols-outlined text-4xl text-brand-muted">menu_book</span>
<h2 class="font-display font-extrabold text-xl text-brand-dark">Naskah digital belum tersedia</h2>
<p class="text-sm text-brand-muted font-medium max-w-md mx-auto">
            Buku ini ada di katalog, tetapi berkas bacaannya belum diunggah. Kamu masih bisa membaca sinopsis di halaman detail.
          </p>
<RouterLink class="neo-pill inline-flex px-5 py-2.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-xs" :to="'/buku/' + book.slug">
            Kembali ke Detail Buku
          </RouterLink>
</div>

<!-- Reader layout -->
<div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-7 items-start">
<!-- Chapter navigation -->
<aside class="lg:col-span-3 lg:sticky lg:top-24 space-y-3">
<div class="neo-card p-4 sm:p-5 bg-white shadow-solid space-y-4">
<template v-if="isManuscript">
<div class="flex items-center justify-between gap-2">
<span class="neo-pill bg-brand-coral-light px-3 py-1 text-[11px] font-bold text-brand-dark">Naskah Digital</span>
<span class="text-[11px] font-bold text-brand-muted">{{ manuscriptFormatLabel }}</span>
</div>

<div class="rounded-xl border-2 border-brand-dark/15 bg-brand-cream/60 p-3 space-y-1">
<p class="text-xs font-bold text-brand-dark break-words">{{ manuscript.name || 'Naskah ' + manuscriptFormatLabel }}</p>
<p class="text-[11px] font-semibold text-brand-muted">
                {{ formatSize(manuscript.size) }}
                <span v-if="isPdf && readerTotal"> · {{ readerTotal }} halaman</span>
              </p>
</div>

<p class="text-[11px] font-semibold text-brand-muted leading-relaxed">
              Halaman terakhir yang kamu buka tersimpan otomatis di perangkat ini.
            </p>

<button v-if="canReadText" class="neo-pill w-full px-3 py-2 text-[11px] font-bold bg-white hover:bg-brand-cream border-2 border-brand-dark text-brand-dark flex items-center justify-center gap-1" type="button" @click="switchMode('text')">
<span class="material-symbols-outlined text-sm">article</span>
              Baca versi teks
            </button>
</template>

<template v-else>
<div class="flex items-center justify-between gap-2">
<span class="neo-pill bg-brand-green-light px-3 py-1 text-[11px] font-bold text-brand-dark">Daftar Bab</span>
<span class="text-[11px] font-bold text-brand-muted">{{ chapterIndex + 1 }}/{{ chapters.length }}</span>
</div>

<!-- Mobile: select -->
<div class="lg:hidden">
<label class="text-[11px] font-bold text-brand-muted block mb-1.5" for="reader-chapter-select">Pilih bab</label>
<select id="reader-chapter-select" v-model.number="chapterIndex" class="w-full neo-pill px-3 py-2.5 text-sm font-bold bg-brand-cream border-2 border-brand-dark text-brand-dark" @change="saveProgress">
<option v-for="(chapter, i) in chapters" :key="'m-' + chapter.number" :value="i">{{ chapter.title }}</option>
</select>
</div>

<!-- Desktop: list -->
<ul class="hidden lg:block space-y-1.5 max-h-[60vh] overflow-y-auto pr-1">
<li v-for="(chapter, i) in chapters" :key="chapter.number">
<button class="w-full text-left rounded-xl px-3 py-2.5 text-xs font-bold border-2 transition-colors flex items-start gap-2" :class="i === chapterIndex ? 'bg-brand-yellow border-brand-dark shadow-xs' : 'bg-brand-cream/60 border-transparent hover:border-brand-dark/30 text-brand-dark/80'" type="button" @click="goToChapter(i)">
<span class="shrink-0 w-5 h-5 rounded-full border border-brand-dark/40 bg-white flex items-center justify-center text-[10px] font-extrabold">{{ chapter.number }}</span>
<span class="min-w-0">{{ chapter.title }}</span>
</button>
</li>
</ul>

<button v-if="canReadManuscript" class="neo-pill w-full px-3 py-2 text-[11px] font-bold bg-brand-yellow hover:bg-[#ebb035] border-2 border-brand-dark text-brand-dark flex items-center justify-center gap-1" type="button" @click="switchMode('manuscript')">
<span class="material-symbols-outlined text-sm">menu_book</span>
              Buka naskah {{ manuscriptFormatLabel }}
            </button>
</template>

<div class="pt-3 border-t-2 border-brand-dark/10 space-y-2 text-[11px] font-semibold text-brand-muted">
<div class="flex items-center justify-between gap-2">
<span>Kutipan</span>
<button class="text-brand-dark underline font-bold" type="button" @click="copyQuote">Salin</button>
</div>
<blockquote class="bg-brand-cream rounded-lg p-2.5 border border-brand-dark/15 text-brand-dark/80 leading-relaxed line-clamp-4">
            “{{ book.quote || book.synopsis }}”
          </blockquote>
<p v-if="copied" class="text-brand-green font-bold">Kutipan tersalin.</p>
</div>
</div>
</aside>

<!-- Reading pane -->
<section class="lg:col-span-9 space-y-4 min-w-0">
<!-- Toolbar -->
<div class="neo-card p-3 sm:p-4 bg-white shadow-solid flex flex-col sm:flex-row sm:items-center gap-3 lg:sticky lg:top-24 z-20">
<div class="flex items-center gap-2 min-w-0 flex-1">
<span class="material-symbols-outlined" :class="isManuscript ? 'text-brand-coral' : 'text-brand-green'">{{ isManuscript ? 'menu_book' : 'auto_stories' }}</span>
<div class="min-w-0 flex-1">
<div class="flex items-center justify-between gap-2 text-[10px] font-bold text-brand-muted mb-1">
<span class="truncate">{{ isManuscript ? readerStatusLabel : currentChapter?.title }}</span>
<span>{{ displayPercent }}%</span>
</div>
<div class="h-2 rounded-full bg-brand-dark/10 overflow-hidden" role="progressbar" :aria-valuenow="displayPercent" aria-valuemin="0" aria-valuemax="100">
<div class="h-full transition-all" :class="isManuscript ? 'bg-brand-coral' : 'bg-brand-green'" :style="{ width: displayPercent + '%' }"></div>
</div>
</div>
</div>
<div class="flex items-center justify-between sm:justify-end gap-2 shrink-0">
<template v-if="isManuscript">
<button class="neo-pill px-3 py-2 text-[11px] font-bold bg-white hover:bg-brand-cream border-2 border-brand-dark text-brand-dark flex items-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed" type="button" :disabled="!canFlipPrev" @click="prevReaderPage">
<span class="material-symbols-outlined text-base">arrow_back</span>
<span class="hidden sm:inline">Sebelumnya</span>
</button>
<button class="neo-pill px-3 py-2 text-[11px] font-extrabold bg-brand-yellow hover:bg-[#ebb035] border-2 border-brand-dark text-brand-dark shadow-xs flex items-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed" type="button" :disabled="!canFlipNext" @click="nextReaderPage">
<span class="hidden sm:inline">Berikutnya</span>
<span class="material-symbols-outlined text-base">arrow_forward</span>
</button>
</template>
<template v-else>
<div class="flex items-center border-2 border-brand-dark rounded-full bg-brand-cream overflow-hidden">
<button class="w-9 h-9 flex items-center justify-center hover:bg-brand-yellow-light disabled:opacity-40" type="button" aria-label="Perkecil teks" :disabled="fontScale <= 0.85" @click="changeFont(-0.1)">
<span class="material-symbols-outlined text-base">text_decrease</span>
</button>
<span class="text-[11px] font-extrabold text-brand-dark w-10 text-center">{{ Math.round(fontScale * 100) }}%</span>
<button class="w-9 h-9 flex items-center justify-center hover:bg-brand-yellow-light disabled:opacity-40" type="button" aria-label="Perbesar teks" :disabled="fontScale >= 1.5" @click="changeFont(0.1)">
<span class="material-symbols-outlined text-base">text_increase</span>
</button>
</div>
<button class="neo-pill px-3 py-2 text-[11px] font-bold bg-white hover:bg-brand-cream border-2 border-brand-dark" type="button" @click="scrollToTop">
<span class="material-symbols-outlined text-base align-middle">vertical_align_top</span>
</button>
</template>
</div>
</div>

<template v-if="!isManuscript">
<!-- Article -->
<article ref="articleEl" class="neo-card p-5 sm:p-8 lg:p-10 bg-white shadow-solid-lg">
<div class="flex items-center gap-2 text-[11px] font-bold text-brand-muted mb-4">
<span class="neo-pill px-2.5 py-0.5 bg-brand-yellow/40 border border-brand-dark/20 text-brand-dark">Bab {{ currentChapter?.number }}</span>
<span class="truncate">{{ chapterProgressLabel }}</span>
</div>
<h2 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight leading-tight mb-5">
            {{ currentChapter?.title }}
          </h2>
<div class="reader-body space-y-4 text-brand-dark/90" :style="{ fontSize: 16 * fontScale + 'px', lineHeight: 1.85 }">
<p v-for="(paragraph, i) in currentChapter?.paragraphs || []" :key="'p-' + chapterIndex + '-' + i" :class="i === 0 ? 'first-letter:font-display first-letter:font-black first-letter:text-4xl first-letter:float-left first-letter:mr-1.5 first-letter:leading-[0.9] first-letter:text-brand-dark' : ''">
              {{ paragraph }}
            </p>
</div>
<div class="mt-8 pt-5 border-t-2 border-brand-dark/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
<span class="text-[11px] font-bold text-brand-muted">{{ book.title }} — {{ book.author?.name }}</span>
<button class="neo-pill px-3 py-2 text-[11px] font-bold bg-brand-cream hover:bg-brand-yellow-light border-2 border-brand-dark text-brand-dark self-start sm:self-auto" type="button" @click="copyQuote">
<span class="material-symbols-outlined text-sm align-middle mr-1">content_copy</span>
            Salin kutipan
          </button>
</div>
</article>

<!-- Chapter navigation -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
<button class="neo-pill px-4 py-3 text-sm font-bold border-2 border-brand-dark bg-white hover:bg-brand-cream text-brand-dark flex items-center justify-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed" type="button" :disabled="chapterIndex === 0" @click="goToChapter(chapterIndex - 1)">
<span class="material-symbols-outlined text-base">arrow_back</span>
            Bab Sebelumnya
          </button>
<button v-if="chapterIndex < chapters.length - 1" class="neo-pill px-4 py-3 text-sm font-extrabold border-2 border-brand-dark bg-brand-yellow hover:bg-[#ebb035] text-brand-dark shadow-xs flex items-center justify-center gap-1.5" type="button" @click="goToChapter(chapterIndex + 1)">
            Bab Selanjutnya
            <span class="material-symbols-outlined text-base">arrow_forward</span>
          </button>
<RouterLink v-else class="neo-pill px-4 py-3 text-sm font-extrabold border-2 border-brand-dark bg-brand-green hover:bg-brand-green/80 text-brand-dark shadow-xs flex items-center justify-center gap-1.5" :to="'/buku/' + book.slug">
            Selesai — Beri Ulasan
            <span class="material-symbols-outlined text-base">rate_review</span>
          </RouterLink>
</div>
</template>

<template v-else>
<Suspense>
<PdfBookReader
  v-if="isPdf"
  ref="manuscriptRef"
  :url="manuscript.url"
  :start-page="savedPage"
  :title="book.title"
  @progress="onReaderProgress"
/>
<EpubBookReader
  v-else
  ref="manuscriptRef"
  :url="manuscript.url"
  :start-location="savedCfi"
  @progress="onReaderProgress"
  @location="onEpubLocation"
/>
<template #fallback>
<div class="neo-card p-6 sm:p-10 bg-white shadow-solid animate-pulse space-y-4">
<span class="block h-4 w-40 bg-brand-cream rounded-full"></span>
<span class="block h-[52vh] min-h-[380px] bg-brand-cream rounded-xl"></span>
</div>
</template>
</Suspense>

<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
<RouterLink class="neo-pill px-4 py-3 text-sm font-bold border-2 border-brand-dark bg-white hover:bg-brand-cream text-brand-dark flex items-center justify-center gap-1.5" to="/katalog">
<span class="material-symbols-outlined text-base">library_books</span>
            Kembali ke Katalog
          </RouterLink>
<RouterLink class="neo-pill px-4 py-3 text-sm font-extrabold border-2 border-brand-dark bg-brand-green hover:bg-brand-green/80 text-brand-dark shadow-xs flex items-center justify-center gap-1.5" :to="'/buku/' + book.slug">
            Selesai — Beri Ulasan
            <span class="material-symbols-outlined text-base">rate_review</span>
          </RouterLink>
</div>
</template>
</section>
</div>
</template>
</section>
<!-- END: ReaderPage -->
</main>
</template>

<script setup>
import { computed, defineAsyncComponent, onBeforeUnmount, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { apiFetch, errorMessage } from '../lib/api'
import { setSeo } from '../lib/seo'
import { auth } from '../stores/auth'

// Heavy readers are split into their own chunks so the rest of the SPA stays
// lightweight; they are only fetched when a manuscript actually exists.
const PdfBookReader = defineAsyncComponent(() => import('../components/PdfBookReader.vue'))
const EpubBookReader = defineAsyncComponent(() => import('../components/EpubBookReader.vue'))

const route = useRoute()
const router = useRouter()

const slug = computed(() => String(route.params.slug || ''))
const loading = ref(true)
const error = ref('')
const book = ref(null)
const chapters = ref([])
const chapterIndex = ref(0)
const fontScale = ref(1)
const copied = ref(false)
const articleEl = ref(null)

const readingMode = ref('manuscript')
const manuscriptRef = ref(null)
const savedPage = ref(0)
const savedCfi = ref('')
const readerIndex = ref(0)
const readerTotal = ref(0)
const readerPercent = ref(0)

const bookmarked = ref(false)
const bookmarkBusy = ref(false)
const bookmarkError = ref('')

const manuscript = computed(() => book.value?.manuscript || null)
const canReadManuscript = computed(() => Boolean(manuscript.value?.url))
const canReadText = computed(() => chapters.value.length > 0)
const readerAvailable = computed(() => canReadManuscript.value || canReadText.value)
const isManuscript = computed(() => readingMode.value === 'manuscript' && canReadManuscript.value)
const isPdf = computed(() => manuscript.value?.format === 'pdf')
const manuscriptFormatLabel = computed(() => (manuscript.value?.format || 'digital').toUpperCase())

const currentChapter = computed(() => chapters.value[chapterIndex.value] ?? null)
const progressPercent = computed(() => {
  if (!chapters.value.length) return 0

  return Math.round(((chapterIndex.value + 1) / chapters.value.length) * 100)
})
const displayPercent = computed(() => (isManuscript.value ? readerPercent.value : progressPercent.value))
const readerStatusLabel = computed(() => {
  if (!isManuscript.value) return ''

  if (isPdf.value) {
    return readerTotal.value
      ? `Halaman ${readerIndex.value + 1} dari ${readerTotal.value}`
      : 'Menyiapkan halaman…'
  }

  return `Posisi ${readerPercent.value}% naskah`
})
const canFlipPrev = computed(() => !isPdf.value || readerIndex.value > 0)
const canFlipNext = computed(() => !isPdf.value || !readerTotal.value || readerIndex.value < readerTotal.value - 1)
const chapterProgressLabel = computed(() => {
  if (!chapters.value.length) return ''

  const words = (currentChapter.value?.paragraphs || []).join(' ').split(/\s+/).filter(Boolean).length

  return `± ${Math.max(1, Math.round(words / 200))} menit baca`
})

function storageKey() {
  return 'libfree:reader:' + slug.value
}

function loadPreferences() {
  try {
    const savedFont = Number(localStorage.getItem('libfree:reader:font'))
    if (savedFont >= 0.85 && savedFont <= 1.5) fontScale.value = savedFont
  } catch {
    // Storage unavailable — keep defaults.
  }
}

function loadSavedProgress() {
  try {
    const saved = JSON.parse(localStorage.getItem(storageKey()) || '{}')
    const index = Number(saved.chapter)
    const page = Number(saved.page)

    if (Number.isInteger(index) && index >= 0 && index < chapters.value.length) {
      chapterIndex.value = index
    }

    if (Number.isInteger(page) && page >= 0) {
      savedPage.value = page
    }

    if (typeof saved.cfi === 'string') {
      savedCfi.value = saved.cfi
    }

    if (saved.mode === 'text' && canReadText.value) {
      readingMode.value = 'text'
    }
  } catch {
    // Ignore malformed storage.
  }
}

let saveTimer = null

function persistProgress() {
  if (saveTimer) {
    clearTimeout(saveTimer)
    saveTimer = null
  }

  try {
    localStorage.setItem(storageKey(), JSON.stringify({
      chapter: chapterIndex.value,
      page: readerIndex.value,
      percent: readerPercent.value,
      cfi: savedCfi.value,
      mode: readingMode.value,
      at: Date.now(),
    }))
  } catch {
    // Ignore unavailable storage.
  }
}

function saveProgress() {
  if (saveTimer) return

  saveTimer = setTimeout(persistProgress, 300)
}

function switchMode(mode) {
  if (mode === 'text' && !canReadText.value) return
  if (mode === 'manuscript' && !canReadManuscript.value) return
  if (readingMode.value === mode) return

  readingMode.value = mode
  persistProgress()
}

function onReaderProgress(payload = {}) {
  if (Number.isFinite(payload.index)) {
    readerIndex.value = payload.index
    savedPage.value = payload.index
    readerTotal.value = Number(payload.total) || readerTotal.value
    readerPercent.value = readerTotal.value
      ? Math.max(0, Math.min(100, Math.round(((readerIndex.value + 1) / readerTotal.value) * 100)))
      : 0
  } else if (Number.isFinite(payload.percent)) {
    readerPercent.value = Math.max(0, Math.min(100, Math.round(payload.percent)))
  }

  saveProgress()
}

function onEpubLocation(cfi) {
  savedCfi.value = cfi
  saveProgress()
}

function nextReaderPage() {
  manuscriptRef.value?.nextPage?.()
}

function prevReaderPage() {
  manuscriptRef.value?.prevPage?.()
}

function formatSize(bytes) {
  const size = Number(bytes)

  if (!Number.isFinite(size) || size <= 0) return 'Ukuran tidak diketahui'
  if (size < 1024 * 1024) return Math.max(1, Math.round(size / 1024)) + ' KB'

  return (size / (1024 * 1024)).toFixed(1).replace('.', ',') + ' MB'
}

function goToChapter(index) {
  if (index < 0 || index >= chapters.value.length) return

  chapterIndex.value = index
  saveProgress()
  scrollToTop()
}

function changeFont(delta) {
  fontScale.value = Math.min(1.5, Math.max(0.85, Math.round((fontScale.value + delta) * 100) / 100))

  try {
    localStorage.setItem('libfree:reader:font', String(fontScale.value))
  } catch {
    // Ignore unavailable storage.
  }
}

function scrollToTop() {
  articleEl.value?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

async function copyQuote() {
  if (!book.value) return

  const text = book.value.quote || book.value.synopsis

  try {
    await navigator.clipboard.writeText(text)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  } catch {
    // Clipboard unavailable (insecure context) — ignore silently.
  }
}

async function toggleBookmark() {
  if (!book.value || bookmarkBusy.value) return

  if (!auth.user) {
    await router.push({ name: 'masuk', query: { redirect: route.fullPath } })
    return
  }

  bookmarkBusy.value = true
  bookmarkError.value = ''

  try {
    const payload = await apiFetch('/api/buku/' + book.value.slug + '/bookmark', {
      method: bookmarked.value ? 'DELETE' : 'POST',
    })

    bookmarked.value = payload.data.bookmarked
  } catch (caught) {
    bookmarkError.value = errorMessage(caught)
  } finally {
    bookmarkBusy.value = false
  }
}

async function loadReader() {
  loading.value = true
  error.value = ''

  try {
    const payload = await apiFetch('/api/buku/' + slug.value + '/baca')
    book.value = payload.data.book
    chapters.value = payload.data.chapters || []
    bookmarked.value = Boolean(book.value.is_bookmarked)

    setSeo({
      title: 'Baca ' + book.value.title + ' — libfree',
      description: 'Baca ' + book.value.title + ' langsung di peramban melalui libfree.',
      canonical: window.location.origin + '/buku/' + book.value.slug,
      robots: 'noindex,follow',
      jsonLd: [],
    })

    loadSavedProgress()
  } catch (caught) {
    error.value = caught.status === 404
      ? 'Buku tidak ditemukan di katalog libfree.'
      : 'Gagal memuat naskah. Silakan coba lagi.'

    setSeo({
      title: 'Naskah tidak ditemukan — libfree',
      description: 'Naskah yang kamu cari tidak tersedia di libfree.',
      canonical: null,
      robots: 'noindex,nofollow',
      jsonLd: [],
    })
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadPreferences()
  loadReader()
})

onBeforeUnmount(() => {
  persistProgress()
})
</script>
