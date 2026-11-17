<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: AdminContentPage -->
<section class="space-y-6" data-purpose="admin-content-page">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </RouterLink>
<span>/</span>
<span class="px-2 py-0.5 rounded-full bg-brand-green-light border border-brand-dark/20 text-brand-dark">Audit Konten</span>
<RouterLink class="ml-auto hover:text-brand-dark transition-colors" to="/admin/unggahan">Moderasi Unggahan →</RouterLink>
</nav>

<div class="neo-card p-5 sm:p-8 bg-white shadow-solid relative overflow-hidden">
<div class="absolute -right-14 -top-14 w-44 h-44 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
<div class="space-y-2 max-w-2xl">
<span class="neo-pill bg-brand-green-light px-3 py-1 text-xs font-bold text-brand-dark">Panel Kurator</span>
<h1 class="font-display font-extrabold text-2xl sm:text-4xl text-brand-dark tracking-tight">Audit Konten & Lisensi</h1>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Hanya buku dengan lisensi terverifikasi dan URL sumber yang boleh tampil di katalog publik. Perubahan langsung tercatat sebagai hasil audit.
          </p>
</div>
<div class="w-16 h-16 rounded-full bg-brand-green-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-3xl text-brand-dark">verified_user</span>
</div>
</div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
<div v-for="stat in statsCards" :key="stat.key" class="neo-card p-4 bg-white border-2 border-brand-dark shadow-xs">
<div class="flex items-center justify-between gap-2">
<span class="text-[11px] font-bold text-brand-muted">{{ stat.label }}</span>
<span class="w-6 h-6 rounded-full border border-brand-dark flex items-center justify-center shrink-0" :class="stat.class">
<span class="material-symbols-outlined text-sm text-brand-dark">{{ stat.icon }}</span>
</span>
</div>
<p class="font-display font-extrabold text-2xl text-brand-dark mt-1">{{ meta[stat.key] ?? 0 }}</p>
</div>
</div>

<!-- Filters + search -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
<div class="flex flex-wrap items-center gap-2">
<button v-for="tab in tabs" :key="tab.value" class="neo-pill px-3.5 py-2 text-xs font-bold border-2 border-brand-dark transition-colors" :class="activeTab === tab.value ? 'bg-brand-dark text-white shadow-xs' : 'bg-white hover:bg-brand-cream text-brand-dark'" type="button" @click="setTab(tab.value)">
            {{ tab.label }}
            <span class="ml-1 text-[10px] font-extrabold opacity-80">{{ meta[tab.key] ?? 0 }}</span>
          </button>
</div>
<div class="flex items-center gap-2">
<label class="relative flex-1 sm:w-64">
<span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-base text-brand-muted">search</span>
<input v-model="search" class="w-full rounded-full border-2 border-brand-dark bg-white pl-9 pr-3 py-2 text-xs font-semibold text-brand-dark focus:ring-0 focus:bg-brand-cream placeholder-brand-muted" placeholder="Cari judul atau penulis…" type="search" @keyup.enter="applySearch"/>
</label>
<button class="neo-pill px-4 py-2 text-xs font-bold bg-white hover:bg-brand-cream text-brand-dark border-2 border-brand-dark shadow-xs flex items-center gap-1.5" type="button" :disabled="loading" @click="fetchBooks">
<span class="material-symbols-outlined text-base" :class="loading ? 'animate-spin' : ''">refresh</span>
            Segarkan
          </button>
</div>
</div>

<p v-if="actionError" class="rounded-xl bg-brand-coral-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="alert">{{ actionError }}</p>
<p v-if="actionSuccess" class="rounded-xl bg-brand-green-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="status">{{ actionSuccess }}</p>

<!-- Loading -->
<div v-if="loading" class="space-y-3">
<div v-for="n in 3" :key="'sk-' + n" class="neo-card p-5 bg-white border-2 border-brand-dark shadow-solid animate-pulse space-y-3">
<div class="h-5 w-1/3 bg-brand-cream rounded-full"></div>
<div class="h-3 w-2/3 bg-brand-cream rounded-full"></div>
<div class="h-3 w-1/2 bg-brand-cream rounded-full"></div>
</div>
</div>

<!-- Error -->
<div v-else-if="error" class="neo-card p-8 bg-white border-2 border-brand-dark shadow-solid text-center space-y-3">
<span class="material-symbols-outlined text-4xl text-brand-coral">error</span>
<p class="text-sm font-bold text-brand-dark">{{ error }}</p>
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-bold border-2 border-brand-dark" type="button" @click="fetchBooks">Coba Lagi</button>
</div>

<!-- Empty -->
<div v-else-if="!books.length" class="neo-card p-8 sm:p-12 bg-white border-2 border-brand-dark shadow-solid text-center space-y-3">
<div class="w-16 h-16 mx-auto rounded-full bg-brand-green-light border-2 border-brand-dark flex items-center justify-center">
<span class="material-symbols-outlined text-3xl text-brand-dark">library_books</span>
</div>
<h2 class="font-display font-extrabold text-xl text-brand-dark">Tidak ada buku di filter ini</h2>
<p class="text-sm text-brand-muted font-medium max-w-md mx-auto">Coba pilih filter lain atau ubah kata kunci pencarian.</p>
</div>

<!-- Audit list -->
<div v-else class="space-y-4">
<article v-for="book in books" :key="book.id" class="neo-card p-4 sm:p-5 bg-white border-2 border-brand-dark shadow-solid space-y-4">
<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
<div class="min-w-0 space-y-1">
<div class="flex flex-wrap items-center gap-2">
<span class="neo-pill px-2.5 py-1 text-[10px] font-extrabold border border-brand-dark" :class="book.is_published ? 'bg-brand-green-light text-brand-dark' : 'bg-brand-yellow-light text-brand-dark'">
              {{ book.is_published ? 'Tayang' : 'Draf' }}
            </span>
<span class="neo-pill px-2.5 py-1 text-[10px] font-bold bg-brand-cream text-brand-dark border border-brand-dark/20">{{ licenseLabel(book.license_status) }}</span>
<span v-if="!book.source_url" class="neo-pill px-2.5 py-1 text-[10px] font-bold bg-brand-coral-light text-brand-dark border border-brand-dark/20">Sumber belum diisi</span>
</div>
<h2 class="font-display font-extrabold text-lg text-brand-dark">{{ book.title }}</h2>
<p class="text-xs font-semibold text-brand-muted">{{ book.author || 'Penulis tidak diketahui' }}<span v-if="book.year"> • {{ book.year }}</span><span v-if="book.category"> • {{ book.category }}</span></p>
</div>
<div class="text-[11px] font-semibold text-brand-muted text-left sm:text-right shrink-0">
<p>Audit terakhir: {{ formatDate(book.audited_at) }}</p>
<p v-if="book.published_at">Tayang sejak: {{ formatDate(book.published_at) }}</p>
<RouterLink v-if="book.is_published" class="underline decoration-brand-yellow decoration-2 font-bold text-brand-dark" :to="'/buku/' + book.slug">Lihat halaman publik →</RouterLink>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
<label class="space-y-1 block">
<span class="text-[11px] font-bold text-brand-dark">Status lisensi</span>
<select v-model="drafts[book.id].license_status" class="w-full rounded-xl px-3 py-2 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark">
<option v-for="option in licenseOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
</select>
</label>
<label class="space-y-1 block">
<span class="text-[11px] font-bold text-brand-dark">Nama lisensi (tampil di publik)</span>
<input v-model="drafts[book.id].license_name" class="w-full rounded-xl px-3 py-2 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" maxlength="255" placeholder="Domain Publik" type="text"/>
</label>
<label class="space-y-1 block md:col-span-2">
<span class="text-[11px] font-bold text-brand-dark">URL sumber naskah (wajib sebelum tayang)</span>
<input v-model="drafts[book.id].source_url" class="w-full rounded-xl px-3 py-2 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" maxlength="2048" placeholder="https://…" type="url"/>
</label>
<label class="space-y-1 block md:col-span-2">
<span class="text-[11px] font-bold text-brand-dark">URL rujukan lisensi</span>
<input v-model="drafts[book.id].license_url" class="w-full rounded-xl px-3 py-2 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" maxlength="2048" placeholder="https://…" type="url"/>
</label>
<label class="space-y-1 block md:col-span-2">
<span class="text-[11px] font-bold text-brand-dark">Catatan audit</span>
<textarea v-model="drafts[book.id].audit_notes" class="w-full rounded-xl px-3 py-2 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted resize-y" maxlength="5000" placeholder="Dasar domain publik, riwayat penulis, atau alasan pembatasan…" rows="2"></textarea>
</label>
</div>

<div class="rounded-xl bg-brand-cream border-2 border-brand-dark/15 p-3 space-y-2">
<div class="flex flex-wrap items-center justify-between gap-2">
<span class="text-[11px] font-bold text-brand-dark">Berkas naskah</span>
<span class="text-[11px] font-semibold text-brand-muted">
            {{ book.has_manuscript ? (book.manuscript_name + ' • ' + (book.manuscript_format || '').toUpperCase()) : 'Belum ada berkas' }}
          </span>
<span v-if="book.has_manuscript" class="text-[11px] font-semibold text-brand-muted">{{ book.download_count }} unduhan</span>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
<label class="text-[11px] font-bold text-brand-dark">Unggah / ganti naskah (PDF/EPUB)
<input class="mt-1 block w-full text-[11px] font-semibold text-brand-dark file:mr-2 file:rounded-full file:border-2 file:border-brand-dark file:bg-brand-yellow file:px-2.5 file:py-1 file:text-[10px] file:font-bold file:text-brand-dark" accept=".pdf,.epub,application/pdf,application/epub+zip" type="file" @change="pendingUploads[book.id] = { ...(pendingUploads[book.id] || {}), manuscript: $event.target.files[0] || null }"/>
</label>
<label class="text-[11px] font-bold text-brand-dark">Unggah / ganti sampul (JPG/PNG/WebP)
<input class="mt-1 block w-full text-[11px] font-semibold text-brand-dark file:mr-2 file:rounded-full file:border-2 file:border-brand-dark file:bg-brand-blue-light file:px-2.5 file:py-1 file:text-[10px] file:font-bold file:text-brand-dark" accept="image/jpeg,image/png,image/webp" type="file" @change="pendingUploads[book.id] = { ...(pendingUploads[book.id] || {}), cover: $event.target.files[0] || null }"/>
</label>
</div>
<div class="flex items-center justify-between gap-2">
<a v-if="book.has_cover" class="text-[11px] font-bold text-brand-dark underline" :href="book.cover_url" target="_blank">Lihat sampul saat ini</a>
<span v-else class="text-[11px] font-semibold text-brand-muted">Sampul belum diunggah.</span>
<button class="neo-pill px-3.5 py-1.5 text-[11px] font-extrabold bg-brand-blue-light hover:bg-brand-blue/20 text-brand-dark border-2 border-brand-dark shadow-xs disabled:opacity-60" type="button" :disabled="uploadingId === book.id" @click="uploadFiles(book)">
            {{ uploadingId === book.id ? 'Mengunggah…' : 'Unggah Berkas' }}
          </button>
</div>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t-2 border-brand-dark/10">
<label class="flex items-center gap-2 text-xs font-bold text-brand-dark cursor-pointer select-none">
<input v-model="drafts[book.id].is_published" class="w-4 h-4 rounded border-2 border-brand-dark text-brand-green focus:ring-0" type="checkbox"/>
          Tayangkan di katalog publik
        </label>
<button class="neo-pill px-5 py-2.5 text-xs font-extrabold bg-brand-green hover:bg-brand-green/80 text-brand-dark border-2 border-brand-dark shadow-xs flex items-center justify-center gap-1.5 disabled:opacity-60" type="button" :disabled="busyId === book.id" @click="save(book)">
<span class="material-symbols-outlined text-base" :class="busyId === book.id ? 'animate-spin' : ''">{{ busyId === book.id ? 'progress_activity' : 'save' }}</span>
            Simpan Audit
          </button>
</div>
</article>
</div>

<!-- Pagination -->
<div v-if="meta.last_page > 1" class="flex items-center justify-center gap-2 pt-2">
<button class="neo-pill px-3 py-2 text-xs font-bold bg-white border-2 border-brand-dark disabled:opacity-40" type="button" :disabled="meta.current_page <= 1" @click="goToPage(meta.current_page - 1)">← Sebelumnya</button>
<span class="text-xs font-bold text-brand-muted">Halaman {{ meta.current_page }} / {{ meta.last_page }}</span>
<button class="neo-pill px-3 py-2 text-xs font-bold bg-white border-2 border-brand-dark disabled:opacity-40" type="button" :disabled="meta.current_page >= meta.last_page" @click="goToPage(meta.current_page + 1)">Berikutnya →</button>
</div>
</section>
<!-- END: AdminContentPage -->
</main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { apiFetch, errorMessage } from '../lib/api'

const tabs = [
  { value: 'all', label: 'Semua', key: 'total' },
  { value: 'published', label: 'Tayang', key: 'published' },
  { value: 'draft', label: 'Draf', key: 'draft' },
  { value: 'unverified', label: 'Belum Verifikasi', key: 'unverified' },
]

const licenseOptions = [
  { value: 'public_domain', label: 'Domain publik' },
  { value: 'cc', label: 'Creative Commons' },
  { value: 'permission', label: 'Izin pemegang hak' },
  { value: 'unverified', label: 'Belum diverifikasi' },
  { value: 'restricted', label: 'Dibatasi / tidak boleh tayang' },
]

const licenses = Object.fromEntries(licenseOptions.map((option) => [option.value, option.label]))

const activeTab = ref('all')
const search = ref('')
const books = ref([])
const drafts = reactive({})
const meta = ref({ total: 0, published: 0, draft: 0, unverified: 0, current_page: 1, last_page: 1 })
const loading = ref(true)
const error = ref('')
const actionError = ref('')
const actionSuccess = ref('')
const busyId = ref(null)
const uploadingId = ref(null)
const pendingUploads = {}
const page = ref(1)

const statsCards = [
  { key: 'published', label: 'Tayang', icon: 'visibility', class: 'bg-brand-green-light' },
  { key: 'draft', label: 'Draf', icon: 'edit_note', class: 'bg-brand-yellow-light' },
  { key: 'unverified', label: 'Belum Verifikasi', icon: 'help', class: 'bg-brand-coral-light' },
  { key: 'total', label: 'Total', icon: 'library_books', class: 'bg-brand-blue-light' },
]

function licenseLabel(value) {
  return licenses[value] || value
}

function formatDate(value) {
  if (!value) return '—'

  return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(value))
}

function hydrate(rows) {
  rows.forEach((row) => {
    drafts[row.id] = {
      license_status: row.license_status || 'unverified',
      license_name: row.license_name || '',
      source_url: row.source_url || '',
      license_url: row.license_url || '',
      audit_notes: row.audit_notes || '',
      is_published: !!row.is_published,
    }
  })
}

async function fetchBooks() {
  loading.value = true
  error.value = ''
  actionError.value = ''

  try {
    const params = new URLSearchParams({ page: String(page.value) })

    if (activeTab.value !== 'all') params.set('status', activeTab.value)
    if (search.value.trim()) params.set('q', search.value.trim())

    const payload = await apiFetch('/api/admin/konten?' + params.toString())
    books.value = payload.data
    meta.value = payload.meta
    hydrate(payload.data)
  } catch (caught) {
    error.value = errorMessage(caught)
  } finally {
    loading.value = false
  }
}

function setTab(value) {
  activeTab.value = value
  page.value = 1
  fetchBooks()
}

function applySearch() {
  page.value = 1
  fetchBooks()
}

function goToPage(target) {
  page.value = target
  fetchBooks()
}

async function save(book) {
  actionError.value = ''
  actionSuccess.value = ''
  busyId.value = book.id

  try {
    const draft = drafts[book.id]
    const payload = await apiFetch('/api/admin/konten/' + book.id, {
      method: 'PATCH',
      body: JSON.stringify({
        license_status: draft.license_status,
        license_name: draft.license_name.trim() || null,
        source_url: draft.source_url.trim() || null,
        license_url: draft.license_url.trim() || null,
        audit_notes: draft.audit_notes.trim() || null,
        is_published: draft.is_published,
      }),
    })

    const updated = payload.data
    const index = books.value.findIndex((item) => item.id === updated.id)

    if (index !== -1) books.value[index] = updated
    hydrate([updated])

    actionSuccess.value = `Audit “${updated.title}” disimpan. Status: ${updated.is_published ? 'tayang' : 'draf'}.`
    await fetchBooks()
  } catch (caught) {
    actionError.value = errorMessage(caught)
  } finally {
    busyId.value = null
  }
}

async function uploadFiles(book) {
  actionError.value = ''
  actionSuccess.value = ''
  const pending = pendingUploads[book.id] || {}

  if (!pending.manuscript && !pending.cover) {
    actionError.value = 'Pilih berkas naskah atau sampul terlebih dahulu.'
    return
  }

  uploadingId.value = book.id

  try {
    const body = new FormData()
    if (pending.manuscript) body.append('manuscript', pending.manuscript)
    if (pending.cover) body.append('cover', pending.cover)

    const payload = await apiFetch('/api/admin/konten/' + book.id + '/berkas', {
      method: 'POST',
      body,
    })

    const updated = payload.data
    const index = books.value.findIndex((item) => item.id === updated.id)
    if (index !== -1) books.value[index] = updated
    hydrate([updated])
    delete pendingUploads[book.id]

    actionSuccess.value = `Berkas “${updated.title}” diperbarui.`
  } catch (caught) {
    actionError.value = errorMessage(caught)
  } finally {
    uploadingId.value = null
  }
}

onMounted(fetchBooks)
</script>
