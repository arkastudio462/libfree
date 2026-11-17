<template>
  <div class="space-y-3">
    <div
      class="relative neo-card bg-brand-cream border-2 border-brand-dark shadow-solid-lg overflow-hidden px-2 py-4 sm:px-6 sm:py-7 min-h-[420px] sm:min-h-[520px]"
      :aria-busy="loading"
    >
      <div class="mx-auto w-full" style="max-width: 1040px">
        <div ref="bookEl" class="pdf-flip"></div>
      </div>

      <div v-if="loading" class="absolute inset-0 bg-white/85 backdrop-blur-[1px] flex flex-col items-center justify-center gap-3 text-center px-6">
        <span class="material-symbols-outlined text-3xl text-brand-dark animate-spin">progress_activity</span>
        <p class="text-sm font-bold text-brand-dark">Menyiapkan halaman naskah…</p>
        <p class="text-[11px] font-semibold text-brand-muted">Halaman pertama sedang dirender.</p>
      </div>

      <div v-else-if="failure" class="absolute inset-0 bg-white flex flex-col items-center justify-center gap-2 text-center px-6">
        <span class="material-symbols-outlined text-3xl text-brand-coral">error</span>
        <p class="text-sm font-bold text-brand-dark">Naskah PDF gagal dibuka.</p>
        <p class="text-[11px] font-semibold text-brand-muted max-w-sm">{{ failure }}</p>
      </div>
    </div>

    <p class="text-center text-[11px] font-semibold text-brand-muted">
      Tarik sudut halaman, klik tepi halaman, atau gunakan tombol panah untuk membalik.
    </p>
  </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { GlobalWorkerOptions, getDocument } from 'pdfjs-dist'
import pdfWorkerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url'
import { PageFlip } from 'page-flip/dist/js/page-flip.module.js'

GlobalWorkerOptions.workerSrc = pdfWorkerUrl

const props = defineProps({
  url: { type: String, required: true },
  startPage: { type: Number, default: 0 },
  title: { type: String, default: '' },
})

const emit = defineEmits(['progress'])

const bookEl = ref(null)
const loading = ref(true)
const failure = ref('')

let pdf = null
let pageFlip = null
let cancelled = false
let totalPages = 0
let currentIndex = 0

const rendered = new Map()
const rendering = new Map()
const failed = new Set()
const pageImages = []

const RENDER_WIDTH = 1100

function emitProgress(index) {
  if (!totalPages) return

  currentIndex = Math.max(0, Math.min(index, totalPages - 1))
  emit('progress', { index: currentIndex, total: totalPages })
}

async function renderPage(index) {
  if (!pdf || cancelled || index < 0 || index >= totalPages || rendered.has(index) || failed.has(index)) return

  if (rendering.has(index)) return rendering.get(index)

  const task = (async () => {
    const page = await pdf.getPage(index + 1)

    if (cancelled) return

    const base = page.getViewport({ scale: 1 })
    const scale = Math.max(1, RENDER_WIDTH / base.width)
    const viewport = page.getViewport({ scale })
    const canvas = document.createElement('canvas')

    canvas.width = Math.floor(viewport.width)
    canvas.height = Math.floor(viewport.height)

    try {
      await page.render({ canvas, viewport }).promise
    } finally {
      page.cleanup()
    }

    if (cancelled) return

    const image = pageImages[index]

    if (image) {
      image.src = canvas.toDataURL('image/jpeg', 0.86)
      image.closest('.pdf-page')?.classList.add('is-ready')
    }

    rendered.set(index, true)
    canvas.width = 0
    canvas.height = 0
  })()
    .catch((error) => {
      failed.add(index)

      if (!cancelled) console.warn('PDF page render failed', index, error)
    })
    .finally(() => {
      rendering.delete(index)
    })

  rendering.set(index, task)

  return task
}

/**
 * Pages are rendered lazily around the reader's position. Only a small window
 * is kept in memory (and evicted once it grows) so large PDFs do not exhaust
 * mobile memory with hundreds of full-page images.
 */
const RENDER_RADIUS = 3
const RENDER_CACHE_LIMIT = 30
let renderQueue = Promise.resolve()

function scheduleRender(index) {
  renderQueue = renderQueue.then(() => renderPage(index)).catch(() => {})
}

function renderNearby(center, radius = RENDER_RADIUS) {
  if (!totalPages) return

  const order = []

  for (let distance = 0; distance <= radius; distance += 1) {
    const forward = center + distance
    const backward = center - distance

    if (forward >= 0 && forward < totalPages) order.push(forward)
    if (distance > 0 && backward >= 0 && backward < totalPages) order.push(backward)
  }

  order.forEach(scheduleRender)
}

function evictFarPages(center) {
  if (rendered.size <= RENDER_CACHE_LIMIT) return

  for (const index of [...rendered.keys()]) {
    if (Math.abs(index - center) <= RENDER_RADIUS + 4) continue
    if (rendered.size <= RENDER_CACHE_LIMIT) break

    const image = pageImages[index]

    if (image) {
      image.removeAttribute('src')
      image.closest('.pdf-page')?.classList.remove('is-ready')
    }

    rendered.delete(index)
  }
}

function initFlip(firstPageRatio, startIndex) {
  pageFlip = new PageFlip(bookEl.value, {
    width: 420,
    height: 420 / firstPageRatio,
    size: 'stretch',
    minWidth: 240,
    maxWidth: 520,
    minHeight: 320,
    maxHeight: 900,
    showCover: true,
    usePortrait: true,
    mobileScrollSupport: false,
    drawShadow: true,
    flippingTime: 650,
    maxShadowOpacity: 0.35,
    startPage: startIndex,
  })

  pageFlip.loadFromHTML(Array.from(bookEl.value.querySelectorAll('.pdf-page')))

  pageFlip.on('flip', (event) => {
    const index = Number(event.data)

    if (!Number.isFinite(index)) return

    emitProgress(index)
    renderNearby(index)
    evictFarPages(index)
  })

  emitProgress(startIndex)
}

async function load() {
  loading.value = true
  failure.value = ''

  try {
    pdf = await getDocument({ url: props.url, isEvalSupported: false }).promise

    if (cancelled) return

    totalPages = pdf.numPages

    const firstPage = await pdf.getPage(1)
    const firstViewport = firstPage.getViewport({ scale: 1 })
    const ratio = firstViewport.width / firstViewport.height || 0.7

    firstPage.cleanup()

    if (cancelled) return

    for (let index = 0; index < totalPages; index += 1) {
      const page = document.createElement('div')
      page.className = 'pdf-page'
      page.dataset.page = String(index + 1)

      const image = document.createElement('img')
      image.alt = props.title
        ? `${props.title} — halaman ${index + 1}`
        : `Halaman ${index + 1}`
      image.decoding = 'async'
      image.draggable = false

      page.appendChild(image)
      bookEl.value.appendChild(page)
      pageImages.push(image)
    }

    const startIndex = Math.max(0, Math.min(props.startPage || 0, totalPages - 1))

    await renderPage(startIndex)

    if (cancelled) return

    initFlip(ratio, startIndex)
    loading.value = false

    renderNearby(startIndex)
  } catch (error) {
    if (cancelled) return

    failure.value = error?.message || 'Berkas naskah tidak dapat dimuat.'
    loading.value = false
  }
}

function nextPage() {
  pageFlip?.flipNext()
}

function prevPage() {
  pageFlip?.flipPrev()
}

function goToPage(index) {
  if (!pageFlip) return

  pageFlip.turnToPage(Math.max(0, Math.min(Number(index) || 0, totalPages - 1)))
}

onMounted(load)

onBeforeUnmount(() => {
  cancelled = true

  try {
    pageFlip?.destroy()
  } catch {
    // The flip container may already be detached; nothing left to clean up.
  }

  pageFlip = null

  try {
    pdf?.destroy()
  } catch {
    // Ignore teardown races with in-flight render tasks.
  }

  pdf = null
  rendered.clear()
  rendering.clear()
  failed.clear()
  pageImages.length = 0
})

defineExpose({ nextPage, prevPage, goToPage })
</script>

<style scoped>
.pdf-flip :deep(.pdf-page) {
  background: #ffffff;
  overflow: hidden;
}

.pdf-flip :deep(.pdf-page img) {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: contain;
  background: #ffffff;
  opacity: 0;
  transition: opacity 180ms ease;
}

.pdf-flip :deep(.pdf-page.is-ready img) {
  opacity: 1;
}
</style>
