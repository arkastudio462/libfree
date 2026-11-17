<template>
  <div class="space-y-3">
    <div class="epub-stage neo-card relative bg-white border-2 border-brand-dark shadow-solid-lg overflow-hidden h-[72vh] min-h-[420px] lg:h-[78vh] lg:max-h-[880px]">
      <BookView
        ref="viewEl"
        :url="url"
        :location="initialLocation"
        @update:location="onRelocate"
      >
        <template #loadingView>
          <div class="flex flex-col items-center justify-center gap-3 text-center px-6">
            <span class="material-symbols-outlined text-3xl text-brand-dark animate-spin">progress_activity</span>
            <p class="text-sm font-bold text-brand-dark">Menyiapkan naskah EPUB…</p>
            <p class="text-[11px] font-semibold text-brand-muted">Tata letak halaman sedang dihitung.</p>
          </div>
        </template>
        <template #errorView>
          <div class="flex flex-col items-center justify-center gap-2 text-center px-6">
            <span class="material-symbols-outlined text-3xl text-brand-coral">error</span>
            <p class="text-sm font-bold text-brand-dark">Naskah EPUB gagal dibuka.</p>
            <p class="text-[11px] font-semibold text-brand-muted max-w-sm">
              Berkas mungkin rusak atau memakai format yang belum didukung.
            </p>
          </div>
        </template>
      </BookView>
    </div>

    <p class="text-center text-[11px] font-semibold text-brand-muted">
      Geser, gulir, atau gunakan tombol panah untuk berpindah halaman. Klik ikon daftar isi di kiri atas untuk melompat antar bab.
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { BookView } from 'vue-book-reader'
import 'vue-book-reader/dist/index.css'

const props = defineProps({
  url: { type: String, required: true },
  startLocation: { type: String, default: '' },
})

const emit = defineEmits(['progress', 'location'])

const viewEl = ref(null)

// Capture the resume point once: later relocate events flow back to the parent
// for persistence, but must not be fed into the view again.
const initialLocation = ref(props.startLocation || undefined)

function onRelocate(detail) {
  const fraction = Number(detail?.fraction)

  if (Number.isFinite(fraction)) {
    emit('progress', { percent: fraction * 100 })
  } else {
    const current = Number(detail?.location?.current)
    const total = Number(detail?.location?.total)

    if (Number.isFinite(current) && Number.isFinite(total) && total > 0) {
      emit('progress', { percent: (current / total) * 100 })
    }
  }

  if (typeof detail?.cfi === 'string' && detail.cfi) {
    emit('location', detail.cfi)
  }
}

function nextPage() {
  viewEl.value?.nextPage?.()
}

function prevPage() {
  viewEl.value?.prevPage?.()
}

defineExpose({ nextPage, prevPage })
</script>

<style scoped>
/* The library reserves 50px gutters; the neo-brutalist stage already provides
   its own frame, so let foliate fill it. */
.epub-stage :deep(.reader) {
  inset: 0.75rem;
}
</style>
