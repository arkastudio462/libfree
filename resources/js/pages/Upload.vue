<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: UploadPage -->
<section class="space-y-6" data-purpose="upload-page">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted flex-wrap">
<RouterLink class="hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </RouterLink>
<span>/</span>
<span class="px-2 py-0.5 rounded-full bg-brand-yellow/30 border border-brand-dark/20 text-brand-dark">Unggah Naskah</span>
</nav>

<!-- Header -->
<div class="neo-card p-5 sm:p-8 bg-white shadow-solid relative overflow-hidden">
<div class="absolute -right-14 -top-14 w-44 h-44 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
<div class="space-y-2 max-w-2xl">
<span class="neo-pill bg-brand-yellow-light px-3 py-1 text-xs font-bold text-brand-dark">Gerakan Buku Terbuka</span>
<h1 class="font-display font-extrabold text-2xl sm:text-4xl text-brand-dark tracking-tight">Unggah Naskah Domain Publik</h1>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Kirim usulan naskah untuk diverifikasi kurator. Kami hanya menerima karya domain publik atau berlisensi Creative Commons yang boleh dibagikan ulang.
          </p>
</div>
<div class="w-16 h-16 rounded-full bg-brand-green-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-3xl text-brand-dark">upload_file</span>
</div>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
<!-- Form -->
<div class="lg:col-span-7 neo-card p-5 sm:p-7 bg-white shadow-solid space-y-5">
<div class="flex items-center justify-between gap-3">
<h2 class="font-display font-extrabold text-xl text-brand-dark">Formulir Unggahan</h2>
<span class="neo-pill bg-brand-cream px-3 py-1 text-[11px] font-bold text-brand-dark">Wajib diperiksa kurator</span>
</div>

<p v-if="successMessage" class="rounded-xl bg-brand-green-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="status">
          {{ successMessage }}
        </p>
<p v-if="formError" class="rounded-xl bg-brand-coral-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="alert">
          {{ formError }}
        </p>

<form class="space-y-4" @submit.prevent="submit">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-title">Judul naskah</label>
<input id="upload-title" v-model="form.title" class="w-full neo-pill px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" maxlength="255" placeholder="Contoh: Hikayat Hang Tuah" required type="text">
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-author">Nama penulis</label>
<input id="upload-author" v-model="form.author_name" class="w-full neo-pill px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" maxlength="255" placeholder="Nama sesuai sampul" required type="text">
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-year">Tahun terbit <span class="text-brand-muted font-semibold">(opsional)</span></label>
<input id="upload-year" v-model.number="form.year" class="w-full neo-pill px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" min="1000" :max="currentYear + 1" placeholder="1922" type="number">
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-license">Status lisensi</label>
<select id="upload-license" v-model="form.license_type" class="w-full neo-pill px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark" required>
<option disabled value="">Pilih lisensi…</option>
<option v-for="license in licenses" :key="license.value" :value="license.value">{{ license.label }}</option>
</select>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-category">Kategori</label>
<select id="upload-category" v-model="form.category" class="w-full neo-pill px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark" required>
<option disabled value="">Pilih kategori…</option>
<option v-for="category in categories" :key="category.slug" :value="category.slug">{{ category.name }}</option>
</select>
</div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-manuscript">Berkas naskah <span class="text-brand-coral">*</span></label>
<input id="upload-manuscript" ref="manuscriptInput" accept=".pdf,.epub,application/pdf,application/epub+zip" class="w-full rounded-xl px-3 py-2.5 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 file:mr-3 file:rounded-full file:border-2 file:border-brand-dark file:bg-brand-yellow file:px-3 file:py-1 file:text-[11px] file:font-bold file:text-brand-dark" required type="file" @change="manuscriptFile = $event.target.files[0] || null">
<p class="text-[11px] font-semibold text-brand-muted">PDF atau EPUB, maksimal 50 MB. Berkas disimpan privat sampai disetujui.</p>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-cover">Sampul <span class="text-brand-muted font-semibold">(opsional)</span></label>
<input id="upload-cover" ref="coverInput" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl px-3 py-2.5 text-xs font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 file:mr-3 file:rounded-full file:border-2 file:border-brand-dark file:bg-brand-blue-light file:px-3 file:py-1 file:text-[11px] file:font-bold file:text-brand-dark" type="file" @change="coverFile = $event.target.files[0] || null">
<p class="text-[11px] font-semibold text-brand-muted">JPG, PNG, atau WebP, maksimal 4 MB.</p>
</div>
</div>

<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-source">Tautan sumber naskah</label>
<input id="upload-source" v-model="form.source_url" class="w-full neo-pill px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" maxlength="2048" placeholder="https://arsip.example.org/naskah" required type="url">
<p class="text-[11px] font-semibold text-brand-muted">Tautan harus bisa diakses publik dan menunjukkan status lisensi karya.</p>
</div>

<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="upload-notes">Catatan untuk kurator <span class="text-brand-muted font-semibold">(opsional)</span></label>
<textarea id="upload-notes" v-model="form.notes" class="w-full rounded-2xl px-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted resize-y" maxlength="2000" placeholder="Edisi yang dipakai, kondisi pindai, atau informasi tambahan…" rows="4"></textarea>
</div>

<label class="flex items-start gap-2.5 text-xs font-semibold text-brand-dark cursor-pointer leading-relaxed">
<input v-model="agreed" class="mt-0.5 w-4 h-4 rounded border-2 border-brand-dark text-brand-green focus:ring-0 shrink-0" type="checkbox">
<span>
            Saya menyatakan naskah ini domain publik atau berlisensi terbuka, dan saya telah membaca
            <RouterLink class="font-bold underline hover:text-brand-green" to="/lisensi#panduan">Panduan Hak Cipta</RouterLink>.
          </span>
</label>

<button class="neo-pill w-full py-3.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-solid disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2" type="submit" :disabled="submitting">
<span v-if="submitting" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
<span>{{ submitting ? 'Mengirim usulan…' : 'Kirim untuk Diverifikasi' }}</span>
</button>
</form>
</div>

<!-- Guidelines -->
<div class="lg:col-span-5 space-y-4">
<div class="neo-card p-5 bg-brand-green-light border-2 border-brand-dark shadow-xs space-y-3">
<div class="flex items-center gap-2">
<span class="w-8 h-8 rounded-full bg-white border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-base text-brand-dark">verified_user</span>
</span>
<h2 class="font-display font-extrabold text-base text-brand-dark">Syarat naskah</h2>
</div>
<ul class="space-y-2 text-xs font-semibold text-brand-dark/85 leading-relaxed">
<li v-for="rule in rules" :key="rule" class="flex items-start gap-2">
<span class="material-symbols-outlined text-brand-green text-sm shrink-0 mt-0.5">check_circle</span>
<span>{{ rule }}</span>
</li>
</ul>
</div>

<div class="neo-card p-5 bg-white border-2 border-brand-dark shadow-xs space-y-3">
<h2 class="font-display font-extrabold text-base text-brand-dark">Alur moderasi</h2>
<ol class="space-y-2.5">
<li v-for="(step, i) in steps" :key="step.title" class="flex items-start gap-2.5">
<span class="w-6 h-6 shrink-0 rounded-full bg-brand-cream border-2 border-brand-dark flex items-center justify-center text-[10px] font-extrabold text-brand-dark">{{ i + 1 }}</span>
<div class="min-w-0">
<p class="text-xs font-extrabold text-brand-dark">{{ step.title }}</p>
<p class="text-[11px] font-medium text-brand-muted leading-relaxed">{{ step.desc }}</p>
</div>
</li>
</ol>
</div>

<div class="neo-card p-4 bg-brand-blue-light border-2 border-brand-dark shadow-xs text-xs font-semibold text-brand-dark leading-relaxed">
          Punya pertanyaan sebelum mengunggah? Baca
<a class="font-extrabold underline" href="/lisensi#panduan">panduan hak cipta</a>
          atau kirim email ke
<a class="font-extrabold underline" href="mailto:halo@libfree.id">halo@libfree.id</a>.
        </div>
</div>
</div>

<!-- Submission history -->
<section class="space-y-4">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
<div>
<h2 class="font-display font-extrabold text-xl text-brand-dark">Riwayat Unggahanmu</h2>
<p class="text-xs font-semibold text-brand-muted">Pantau status verifikasi setiap naskah yang kamu kirim.</p>
</div>
<button class="neo-pill px-4 py-2 text-xs font-bold bg-white hover:bg-brand-cream text-brand-dark border-2 border-brand-dark shadow-xs self-start sm:self-auto flex items-center gap-1.5" type="button" :disabled="loading" @click="fetchSubmissions">
<span class="material-symbols-outlined text-base" :class="loading ? 'animate-spin' : ''">refresh</span>
            Segarkan
          </button>
</div>

<div v-if="loading" class="neo-card p-5 bg-white border-2 border-brand-dark shadow-solid animate-pulse space-y-3">
<div class="h-4 w-1/3 bg-brand-cream rounded-full"></div>
<div class="h-3 w-2/3 bg-brand-cream rounded-full"></div>
<div class="h-3 w-1/2 bg-brand-cream rounded-full"></div>
</div>

<div v-else-if="listError" class="neo-card p-5 bg-white border-2 border-brand-dark shadow-solid flex flex-col sm:flex-row sm:items-center justify-between gap-3">
<p class="text-sm font-bold text-brand-dark">{{ listError }}</p>
<button class="neo-pill px-4 py-2 text-xs font-bold bg-brand-yellow hover:bg-[#ebb035] text-brand-dark border-2 border-brand-dark" type="button" @click="fetchSubmissions">Coba Lagi</button>
</div>

<div v-else-if="!submissions.length" class="neo-card p-8 bg-white border-2 border-brand-dark shadow-solid text-center space-y-2">
<span class="material-symbols-outlined text-3xl text-brand-muted">inbox</span>
<p class="text-sm font-bold text-brand-dark">Belum ada usulan naskah.</p>
<p class="text-xs font-medium text-brand-muted">Naskah pertama yang kamu kirim akan muncul di sini.</p>
</div>

<div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
<article v-for="item in submissions" :key="item.id" class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid space-y-2">
<div class="flex items-start justify-between gap-2">
<div class="min-w-0">
<h3 class="font-display font-extrabold text-base text-brand-dark truncate">{{ item.title }}</h3>
<p class="text-[11px] font-semibold text-brand-muted truncate">{{ item.author_name }}<span v-if="item.year"> • {{ item.year }}</span></p>
</div>
<span class="neo-pill px-2.5 py-1 text-[10px] font-extrabold border border-brand-dark shrink-0" :class="statusMeta(item.status).class">{{ statusMeta(item.status).label }}</span>
</div>
<p class="text-[11px] font-semibold text-brand-muted">{{ licenseLabel(item.license_type) }}<span v-if="item.category"> • {{ item.category.name }}</span></p>
<div v-if="item.has_manuscript" class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
<a class="underline decoration-brand-yellow decoration-2 text-brand-dark" :href="'/api/unggahan/' + item.id + '/berkas/naskah'">Unduh berkas: {{ item.manuscript_name }}</a>
<a v-if="item.has_cover" class="underline decoration-brand-blue decoration-2 text-brand-dark" :href="'/api/unggahan/' + item.id + '/berkas/sampul'" target="_blank">Lihat sampul</a>
</div>
<p v-if="item.reviewer_note" class="text-[11px] font-semibold text-brand-dark/80 bg-brand-cream rounded-lg px-2.5 py-2 border border-brand-dark/15 leading-relaxed">
<span class="font-extrabold">Catatan kurator:</span> {{ item.reviewer_note }}
</p>
<p v-if="item.book" class="text-[11px] font-semibold text-brand-dark bg-brand-green-light rounded-lg px-2.5 py-2 border border-brand-dark/15 leading-relaxed">
<span class="font-extrabold">Draf katalog:</span> {{ item.book.title }} — {{ item.book.is_published ? 'sudah tayang' : 'menunggu audit lisensi' }}.
</p>
<div class="flex items-center justify-between gap-2 pt-2 border-t border-brand-dark/10">
<a class="text-[11px] font-bold text-brand-dark underline truncate" :href="item.source_url" rel="noopener noreferrer" target="_blank">Lihat sumber</a>
<span class="text-[10px] font-bold text-brand-muted shrink-0">{{ formatDate(item.created_at) }}</span>
</div>
</article>
</div>
</section>
</section>
<!-- END: UploadPage -->
</main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { apiFetch, errorMessage } from '../lib/api'

const licenses = [
  { value: 'domain-public', label: 'Domain publik' },
  { value: 'cc-by', label: 'Creative Commons BY' },
  { value: 'cc-by-sa', label: 'Creative Commons BY-SA' },
  { value: 'cc-by-nc', label: 'Creative Commons BY-NC' },
  { value: 'cc-by-nd', label: 'Creative Commons BY-ND' },
  { value: 'other', label: 'Lisensi terbuka lainnya' },
]

const rules = [
  'Karya sudah berada di domain publik atau berlisensi Creative Commons.',
  'Bukan salinan edisi berhak cipta milik penerbit lain.',
  'Tautan sumber dapat diakses publik dan mencantumkan lisensi.',
  'Naskah tidak mengandung data pribadi atau konten sensitif.',
]

const steps = [
  { title: 'Kirim formulir', desc: 'Isi metadata dan tautan sumber naskah.' },
  { title: 'Verifikasi kurator', desc: 'Kami memeriksa status lisensi dan kualitas berkas.' },
  { title: 'Publikasi', desc: 'Naskah disiapkan untuk pembaca bila lolos verifikasi.' },
]

const currentYear = new Date().getFullYear()
const form = reactive({
  title: '',
  author_name: '',
  year: null,
  category: '',
  source_url: '',
  license_type: '',
  notes: '',
})
const categories = ref([])
const manuscriptFile = ref(null)
const coverFile = ref(null)
const manuscriptInput = ref(null)
const coverInput = ref(null)
const agreed = ref(false)
const submitting = ref(false)
const formError = ref('')
const successMessage = ref('')
const submissions = ref([])
const loading = ref(true)
const listError = ref('')

const statusMap = {
  pending: { label: 'Menunggu', class: 'bg-brand-yellow-light text-brand-dark' },
  approved: { label: 'Disetujui', class: 'bg-brand-green-light text-brand-dark' },
  rejected: { label: 'Ditolak', class: 'bg-brand-coral-light text-brand-dark' },
}

function statusMeta(status) {
  return statusMap[status] || statusMap.pending
}

function licenseLabel(value) {
  return licenses.find((license) => license.value === value)?.label || value
}

function formatDate(value) {
  if (!value) return ''

  return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(value))
}

async function fetchSubmissions() {
  loading.value = true
  listError.value = ''

  try {
    const payload = await apiFetch('/api/unggahan')
    submissions.value = payload.data
  } catch (caught) {
    listError.value = errorMessage(caught)
  } finally {
    loading.value = false
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

async function submit() {
  formError.value = ''
  successMessage.value = ''

  if (!agreed.value) {
    formError.value = 'Centang pernyataan lisensi sebelum mengirim naskah.'
    return
  }

  if (!manuscriptFile.value) {
    formError.value = 'Pilih berkas naskah PDF atau EPUB terlebih dahulu.'
    return
  }

  submitting.value = true

  try {
    const body = new FormData()
    body.append('title', form.title)
    body.append('author_name', form.author_name)
    body.append('category', form.category)
    body.append('source_url', form.source_url)
    body.append('license_type', form.license_type)
    body.append('notes', form.notes || '')
    if (form.year !== '' && form.year !== null) body.append('year', String(form.year))
    body.append('manuscript', manuscriptFile.value)
    if (coverFile.value) body.append('cover', coverFile.value)

    await apiFetch('/api/unggahan', {
      method: 'POST',
      body,
    })

    successMessage.value = 'Usulan naskah terkirim. Kurator akan meninjau berkas dan status lisensinya.'
    Object.assign(form, { title: '', author_name: '', year: null, category: '', source_url: '', license_type: '', notes: '' })
    manuscriptFile.value = null
    coverFile.value = null
    if (manuscriptInput.value) manuscriptInput.value.value = ''
    if (coverInput.value) coverInput.value.value = ''
    agreed.value = false
    await fetchSubmissions()
  } catch (caught) {
    formError.value = errorMessage(caught)
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchCategories()
  fetchSubmissions()
})
</script>
