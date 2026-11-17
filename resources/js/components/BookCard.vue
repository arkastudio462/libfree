<template>
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all cursor-pointer" role="link" tabindex="0" :aria-label="'Lihat detail ' + book.title" @click="openDetail" @keydown.enter.self="openDetail" @keydown.space.self.prevent="openDetail">
<div>
<!-- Book Cover Art Container -->
<div class="relative w-full h-40 sm:h-48 rounded-2xl border-2 border-brand-dark overflow-hidden flex items-center justify-center mb-4 p-4" :class="book.card?.cover_class">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] font-extrabold border border-brand-dark" :class="book.card?.badge_class">{{ book.card?.badge }}</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">{{ book.card?.formats_label }}</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full border border-brand-dark flex items-center justify-center transition-colors z-10 disabled:opacity-60" :class="bookmarked ? 'bg-brand-yellow hover:bg-[#ebb035]' : 'bg-white hover:bg-brand-coral-light'" type="button" :title="bookmarked ? 'Hapus dari Rak Baca' : 'Simpan ke Rak'" :aria-pressed="bookmarked" :disabled="busy" @click.stop="toggleBookmark">
<span class="material-symbols-outlined text-sm">{{ bookmarked ? 'bookmark' : 'bookmark_border' }}</span>
</button>
<!-- Book Spine Simulation -->
<div class="w-24 h-32 sm:w-28 sm:h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform transition-transform" :class="book.card?.spine_rotate">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold block uppercase" :class="book.card?.spine_kicker_class">{{ book.card?.spine_kicker }}</span>
<h4 class="font-display font-extrabold text-brand-dark leading-tight mt-0.5" :class="book.card?.spine_title_class">{{ book.card?.spine_title }}</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">{{ book.card?.spine_author }}</div>
</div>
</div>
<!-- Meta info -->
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between gap-2 text-xs font-bold">
<span class="font-bold min-w-0 truncate" :class="book.card?.author_label_class">{{ book.card?.author_label }}</span>
<span class="flex items-center gap-1 shrink-0 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20" title="Nilai kurasi" aria-label="Nilai kurasi">
<span class="text-xs">★</span> {{ book.rating }}
              </span>
</div>
<h3 class="font-display font-extrabold text-base sm:text-lg text-brand-dark group-hover:text-brand-green transition-colors">
              {{ book.title }}
            </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
              {{ book.synopsis }}
            </p>
<p v-if="error" class="text-[10px] font-bold text-brand-coral" role="alert">{{ error }}</p>
</div>
</div>
<!-- Footer details & Action -->
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted truncate">{{ book.pages }} Halaman</span>
<div class="flex items-center gap-1.5 shrink-0">
<button v-if="removable" class="neo-pill px-2.5 py-1.5 text-xs bg-white hover:bg-brand-coral-light text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center" type="button" :disabled="busy" title="Hapus dari Rak Baca" @click.stop="removeFromShelf">
<span class="material-symbols-outlined text-sm">bookmark_remove</span>
</button>
<RouterLink class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" :to="'/buku/' + book.slug" @click.stop>
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</RouterLink>
</div>
</div>
</article>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { apiFetch, errorMessage } from '../lib/api'
import { auth } from '../stores/auth'

const props = defineProps({
  book: { type: Object, required: true },
  removable: { type: Boolean, default: false },
})

const emit = defineEmits(['removed'])

const router = useRouter()
const bookmarked = ref(Boolean(props.book.is_bookmarked))
const busy = ref(false)
const error = ref('')

function openDetail() {
  router.push('/buku/' + props.book.slug)
}

async function toggleBookmark() {
  error.value = ''

  if (!auth.user) {
    await router.push({ name: 'masuk', query: { redirect: `/buku/${props.book.slug}` } })
    return
  }

  if (busy.value) return
  busy.value = true

  try {
    const payload = await apiFetch(`/api/buku/${props.book.slug}/bookmark`, {
      method: bookmarked.value ? 'DELETE' : 'POST',
    })

    bookmarked.value = payload.data.bookmarked

    if (!bookmarked.value && props.removable) emit('removed', props.book.id)
  } catch (caught) {
    error.value = errorMessage(caught)
  } finally {
    busy.value = false
  }
}

async function removeFromShelf() {
  if (bookmarked.value) {
    await toggleBookmark()
    return
  }

  emit('removed', props.book.id)
}
</script>
