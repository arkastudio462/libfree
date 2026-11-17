<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: AdminSubmissionsPage -->
<section class="space-y-6" data-purpose="admin-submissions-page">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </RouterLink>
<span>/</span>
<span class="px-2 py-0.5 rounded-full bg-brand-blue-light border border-brand-dark/20 text-brand-dark">Moderasi Unggahan</span>
</nav>

<div class="neo-card p-5 sm:p-8 bg-white shadow-solid relative overflow-hidden">
<div class="absolute -right-14 -top-14 w-44 h-44 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
<div class="space-y-2 max-w-2xl">
<span class="neo-pill bg-brand-blue-light px-3 py-1 text-xs font-bold text-brand-dark">Panel Kurator</span>
<h1 class="font-display font-extrabold text-2xl sm:text-4xl text-brand-dark tracking-tight">Moderasi Unggahan</h1>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Periksa status lisensi dan kelayakan naskah sebelum dipublikasikan ke katalog. Setiap keputusan tercatat atas namamu.
          </p>
</div>
<div class="w-16 h-16 rounded-full bg-brand-blue-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-3xl text-brand-dark">fact_check</span>
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

<!-- Filters + refresh -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
<div class="flex flex-wrap items-center gap-2">
<button v-for="tab in tabs" :key="tab.value" class="neo-pill px-3.5 py-2 text-xs font-bold border-2 border-brand-dark transition-colors" :class="activeTab === tab.value ? 'bg-brand-dark text-white shadow-xs' : 'bg-white hover:bg-brand-cream text-brand-dark'" type="button" @click="setTab(tab.value)">
            {{ tab.label }}
            <span class="ml-1 text-[10px] font-extrabold opacity-80">{{ tab.value === 'all' ? meta.total ?? 0 : meta[tab.value] ?? 0 }}</span>
          </button>
</div>
<button class="neo-pill px-4 py-2 text-xs font-bold bg-white hover:bg-brand-cream text-brand-dark border-2 border-brand-dark shadow-xs self-start sm:self-auto flex items-center gap-1.5" type="button" :disabled="loading" @click="fetchQueue">
<span class="material-symbols-outlined text-base" :class="loading ? 'animate-spin' : ''">refresh</span>
            Segarkan
          </button>
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
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-bold border-2 border-brand-dark" type="button" @click="fetchQueue">Coba Lagi</button>
</div>

<!-- Empty -->
<div v-else-if="!submissions.length" class="neo-card p-8 sm:p-12 bg-white border-2 border-brand-dark shadow-solid text-center space-y-3">
<div class="w-16 h-16 mx-auto rounded-full bg-brand-green-light border-2 border-brand-dark flex items-center justify-center">
<span class="material-symbols-outlined text-3xl text-brand-dark">inbox</span>
</div>
<h2 class="font-display font-extrabold text-xl text-brand-dark">Tidak ada unggahan di filter ini</h2>
<p class="text-sm text-brand-muted font-medium max-w-md mx-auto">Antrean sudah bersih. Coba pilih filter lain atau segarkan halaman.</p>
</div>

<!-- Queue -->
<div v-else class="space-y-4">
<article v-for="item in submissions" :key="item.id" class="neo-card p-4 sm:p-5 bg-white border-2 border-brand-dark shadow-solid space-y-3">
<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
<div class="min-w-0 space-y-1">
<div class="flex flex-wrap items-center gap-2">
<span class="neo-pill px-2.5 py-1 text-[10px] font-extrabold border border-brand-dark" :class="statusMeta(item.status).class">{{ statusMeta(item.status).label }}</span>
<span class="neo-pill px-2.5 py-1 text-[10px] font-bold bg-brand-cream text-brand-dark border border-brand-dark/20">{{ licenseLabel(item.license_type) }}</span>
</div>
<h2 class="font-display font-extrabold text-lg text-brand-dark">{{ item.title }}</h2>
<p class="text-xs font-semibold text-brand-muted">{{ item.author_name }}<span v-if="item.year"> • {{ item.year }}</span></p>
</div>
<div class="text-[11px] font-semibold text-brand-muted text-left sm:text-right shrink-0">
<p class="text-brand-dark font-bold truncate">{{ item.submitter?.name || 'Pembaca' }}</p>
<p class="truncate">{{ item.submitter?.email }}</p>
<p>{{ formatDate(item.created_at) }}</p>
</div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] font-semibold">
<a class="rounded-lg bg-brand-cream border border-brand-dark/15 px-2.5 py-2 text-brand-dark underline truncate" :href="item.source_url" rel="noopener noreferrer" target="_blank">Sumber: {{ item.source_url }}</a>
<p v-if="item.category" class="rounded-lg bg-brand-cream border border-brand-dark/15 px-2.5 py-2 text-brand-dark/80">Kategori: {{ item.category.name }}</p>
<p v-if="item.notes" class="rounded-lg bg-brand-cream border border-brand-dark/15 px-2.5 py-2 text-brand-dark/80 leading-relaxed">Catatan pengunggah: {{ item.notes }}</p>
<a v-if="item.has_manuscript" class="rounded-lg bg-brand-blue-light border border-brand-dark/15 px-2.5 py-2 text-brand-dark underline truncate" :href="'/api/unggahan/' + item.id + '/berkas/naskah'">Berkas: {{ item.manuscript_name }}</a>
<a v-if="item.has_cover" class="rounded-lg bg-brand-green-light border border-brand-dark/15 px-2.5 py-2 text-brand-dark underline" :href="'/api/unggahan/' + item.id + '/berkas/sampul'" target="_blank">Lihat sampul</a>
</div>

<p v-if="item.book" class="text-[11px] font-semibold text-brand-dark/80 bg-brand-blue-light/60 rounded-lg px-2.5 py-2 border border-brand-dark/15 leading-relaxed">
<span class="font-extrabold">Draf katalog:</span> {{ item.book.title }} — {{ item.book.is_published ? 'sudah tayang' : 'belum tayang, menunggu audit lisensi' }}.
</p>

<p v-if="item.reviewer_note" class="text-[11px] font-semibold text-brand-dark/80 bg-brand-green-light/60 rounded-lg px-2.5 py-2 border border-brand-dark/15 leading-relaxed">
<span class="font-extrabold">Catatan kurator:</span> {{ item.reviewer_note }}
</p>

<div v-if="item.status === 'pending'" class="pt-3 border-t-2 border-brand-dark/10 space-y-2">
<label class="text-[11px] font-bold text-brand-dark block" :for="'note-' + item.id">Catatan kurator (opsional, wajib untuk penolakan)</label>
<textarea :id="'note-' + item.id" v-model="notes[item.id]" class="w-full rounded-xl px-3 py-2 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted resize-y" maxlength="2000" placeholder="Alasan penolakan atau catatan persetujuan…" rows="2"></textarea>
<div class="flex flex-col sm:flex-row gap-2">
<button class="neo-pill px-4 py-2.5 text-xs font-extrabold bg-brand-green hover:bg-brand-green/80 text-brand-dark border-2 border-brand-dark shadow-xs flex items-center justify-center gap-1.5 disabled:opacity-60" type="button" :disabled="busyId === item.id" @click="review(item, 'approved')">
<span class="material-symbols-outlined text-base">check_circle</span>
              Setujui
            </button>
<button class="neo-pill px-4 py-2.5 text-xs font-extrabold bg-brand-coral hover:bg-[#f3b8b8] text-brand-dark border-2 border-brand-dark shadow-xs flex items-center justify-center gap-1.5 disabled:opacity-60" type="button" :disabled="busyId === item.id" @click="review(item, 'rejected')">
<span class="material-symbols-outlined text-base">cancel</span>
              Tolak
            </button>
</div>
</div>
<div v-else class="pt-2 border-t border-brand-dark/10 text-[11px] font-semibold text-brand-muted">
          Diputuskan {{ formatDate(item.reviewed_at) }}
        </div>
</article>
</div>
</section>
<!-- END: AdminSubmissionsPage -->
</main>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { apiFetch, errorMessage } from '../lib/api'

const props = defineProps({
  status: { type: String, default: 'all' },
})

const tabs = [
  { value: 'all', label: 'Semua' },
  { value: 'pending', label: 'Menunggu' },
  { value: 'approved', label: 'Disetujui' },
  { value: 'rejected', label: 'Ditolak' },
]

const licenses = {
  'domain-public': 'Domain publik',
  'cc-by': 'CC BY',
  'cc-by-sa': 'CC BY-SA',
  'cc-by-nc': 'CC BY-NC',
  'cc-by-nd': 'CC BY-ND',
  other: 'Lisensi lain',
}

const statusMap = {
  pending: { label: 'Menunggu', class: 'bg-brand-yellow-light text-brand-dark' },
  approved: { label: 'Disetujui', class: 'bg-brand-green-light text-brand-dark' },
  rejected: { label: 'Ditolak', class: 'bg-brand-coral-light text-brand-dark' },
}

const activeTab = ref(props.status)
const submissions = ref([])
const meta = ref({ pending: 0, approved: 0, rejected: 0, total: 0 })
const loading = ref(true)
const error = ref('')
const actionError = ref('')
const actionSuccess = ref('')
const busyId = ref(null)
const notes = reactive({})

const statsCards = computed(() => [
  { key: 'pending', label: 'Menunggu', icon: 'hourglass_top', class: 'bg-brand-yellow-light' },
  { key: 'approved', label: 'Disetujui', icon: 'check_circle', class: 'bg-brand-green-light' },
  { key: 'rejected', label: 'Ditolak', icon: 'cancel', class: 'bg-brand-coral-light' },
  { key: 'total', label: 'Total', icon: 'inbox', class: 'bg-brand-blue-light' },
])

function statusMeta(status) {
  return statusMap[status] || statusMap.pending
}

function licenseLabel(value) {
  return licenses[value] || value
}

function formatDate(value) {
  if (!value) return '—'

  return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(value))
}

async function fetchQueue() {
  loading.value = true
  error.value = ''

  try {
    const query = activeTab.value === 'all' ? '' : '?status=' + activeTab.value
    const payload = await apiFetch('/api/admin/unggahan' + query)
    submissions.value = payload.data
    meta.value = payload.meta
  } catch (caught) {
    error.value = errorMessage(caught)
  } finally {
    loading.value = false
  }
}

function setTab(value) {
  activeTab.value = value
  fetchQueue()
}

async function review(item, status) {
  actionError.value = ''
  actionSuccess.value = ''
  const note = (notes[item.id] || '').trim()

  if (status === 'rejected' && !note) {
    actionError.value = 'Tulis alasan penolakan di catatan kurator.'
    return
  }

  busyId.value = item.id

  try {
    const payload = await apiFetch('/api/admin/unggahan/' + item.id, {
      method: 'PATCH',
      body: JSON.stringify({ status, reviewer_note: note || null }),
    })

    actionSuccess.value = `“${payload.data.title}” ${status === 'approved' ? 'disetujui dan masuk draf katalog (belum tayang sampai audit lisensi selesai)' : 'ditolak'}.`
    delete notes[item.id]
    await fetchQueue()
  } catch (caught) {
    actionError.value = errorMessage(caught)
  } finally {
    busyId.value = null
  }
}

onMounted(fetchQueue)
</script>
