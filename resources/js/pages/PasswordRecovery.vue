<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
<!-- Left: copy -->
<div class="lg:col-span-5 space-y-5">
<div class="inline-flex items-center gap-2 neo-pill bg-brand-yellow-light px-4 py-1.5 text-xs sm:text-sm text-brand-dark">
<span class="w-2.5 h-2.5 rounded-full bg-brand-green border border-brand-dark animate-pulse"></span>
<span class="font-bold">{{ isReset ? 'Langkah terakhir' : 'Pemulihan akun' }}</span>
</div>
<h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-brand-dark tracking-tight leading-tight">
          {{ isReset ? 'Buat' : 'Lupa' }} <span class="relative inline-block">{{ isReset ? 'sandi baru' : 'kata sandi' }}<span class="absolute left-0 bottom-1 w-full h-3 bg-brand-yellow/60 -z-10 rounded"></span></span>.
        </h1>
<p class="text-sm sm:text-base text-brand-muted max-w-xl leading-relaxed font-medium">
          {{ isReset
            ? 'Pilih kata sandi baru untuk akunmu. Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.'
            : 'Masukkan email yang terdaftar. Kami akan mengirim tautan untuk mengatur ulang kata sandi akunmu.' }}
        </p>
<ul class="space-y-3 pt-1 text-sm font-semibold text-brand-dark">
<li class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-brand-green-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-base">lock_reset</span>
</span>
            Tautan berlaku terbatas dan hanya bisa dipakai sekali
          </li>
<li class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-brand-blue-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-base">mark_email_read</span>
</span>
            Periksa juga folder spam bila email belum masuk
          </li>
</ul>
</div>

<!-- Right: form -->
<div class="lg:col-span-7 flex justify-center lg:justify-end">
<div class="neo-card w-full max-w-md p-6 sm:p-8 bg-white shadow-solid-lg space-y-5">
<div class="flex items-center justify-between gap-3">
<div>
<span class="neo-pill bg-brand-yellow px-3 py-0.5 text-[11px] font-bold text-brand-dark">Akun Pembaca</span>
<h2 class="font-display font-extrabold text-2xl text-brand-dark mt-2">{{ isReset ? 'Atur ulang kata sandi' : 'Minta tautan atur ulang' }}</h2>
</div>
<div class="w-11 h-11 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-brand-dark">{{ isReset ? 'key' : 'forward_to_inbox' }}</span>
</div>
</div>

<p v-if="error" class="rounded-xl bg-brand-coral-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="alert">
          {{ error }}
        </p>
<p v-if="success" class="rounded-xl bg-brand-green-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="status">
          {{ success }}
        </p>

<form class="space-y-4" @submit.prevent="submit">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="recovery-email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">mail</span>
<input id="recovery-email" v-model="form.email" :readonly="isReset" autocomplete="email" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted read-only:opacity-70" placeholder="nama@email.com" required type="email">
</div>
</div>

<template v-if="isReset">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="recovery-password">Kata sandi baru</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">lock</span>
<input id="recovery-password" v-model="form.password" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" class="w-full neo-pill pl-11 pr-12 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="Min. 8 karakter" required minlength="8">
<button class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full hover:bg-brand-yellow-light flex items-center justify-center text-brand-muted" type="button" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" @click="showPassword = !showPassword">
<span class="material-symbols-outlined text-lg">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
</button>
</div>
<p class="text-[11px] font-semibold text-brand-muted">Minimal 8 karakter, kombinasikan huruf dan angka.</p>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="recovery-password-confirmation">Ulangi sandi baru</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">lock_reset</span>
<input id="recovery-password-confirmation" v-model="form.password_confirmation" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="Ulangi sandi" required minlength="8">
</div>
</div>
</template>

<button class="neo-pill w-full py-3.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-solid disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2" type="submit" :disabled="pending">
<span v-if="pending" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
<span>{{ pending ? 'Memproses…' : (isReset ? 'Simpan Kata Sandi Baru' : 'Kirim Tautan Atur Ulang') }}</span>
</button>
</form>

<div v-if="success" class="text-center">
<RouterLink class="neo-pill inline-flex px-4 py-2 text-xs font-extrabold bg-brand-green text-brand-dark border-2 border-brand-dark shadow-solid" to="/masuk">
          Masuk ke Akun
        </RouterLink>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1 text-xs font-bold">
<RouterLink class="text-brand-dark underline hover:text-brand-green" to="/masuk">← Kembali ke halaman masuk</RouterLink>
<RouterLink v-if="!isReset" class="text-brand-dark underline hover:text-brand-green" to="/daftar">Belum punya akun? Daftar</RouterLink>
</div>
</div>
</div>
</section>
</main>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import { apiFetch, errorMessage } from '../lib/api'

const props = defineProps({
  mode: {
    type: String,
    default: 'forgot',
  },
})

const route = useRoute()

const isReset = computed(() => props.mode === 'reset')
const token = computed(() => (typeof route.query.token === 'string' ? route.query.token : ''))

const form = reactive({
  email: typeof route.query.email === 'string' ? route.query.email : '',
  password: '',
  password_confirmation: '',
})

const showPassword = ref(false)
const pending = ref(false)
const error = ref('')
const success = ref('')

async function submit() {
  error.value = ''
  success.value = ''

  if (isReset.value && !token.value) {
    error.value = 'Tautan atur ulang tidak lengkap. Minta tautan baru dari halaman lupa kata sandi.'
    return
  }

  pending.value = true

  try {
    const payload = isReset.value
      ? await apiFetch('/api/atur-ulang-sandi', {
        method: 'POST',
        body: JSON.stringify({
          token: token.value,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation,
        }),
      })
      : await apiFetch('/api/lupa-sandi', {
        method: 'POST',
        body: JSON.stringify({ email: form.email }),
      })

    success.value = payload.message || 'Permintaan diproses. Periksa emailmu.'

    if (isReset.value) {
      form.password = ''
      form.password_confirmation = ''
    }
  } catch (caught) {
    error.value = errorMessage(caught)
  } finally {
    pending.value = false
  }
}
</script>
