<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: RegisterPage -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center" data-purpose="register-page">
<!-- Left: copy -->
<div class="lg:col-span-5 space-y-5">
<div class="inline-flex items-center gap-2 neo-pill bg-brand-green-light px-4 py-1.5 text-xs sm:text-sm text-brand-dark">
<span class="w-2.5 h-2.5 rounded-full bg-brand-green border border-brand-dark animate-pulse"></span>
<span class="font-bold">Gratis, tanpa kartu kredit</span>
</div>
<h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-brand-dark tracking-tight leading-tight">
          Satu akun untuk <span class="relative inline-block">koleksi naskah klasik<span class="absolute left-0 bottom-1 w-full h-3 bg-brand-yellow/60 -z-10 rounded"></span></span> terbuka.
        </h1>
<p class="text-sm sm:text-base text-brand-muted max-w-xl leading-relaxed font-medium">
          Daftar dalam hitungan detik. Tidak ada langganan, tidak ada kuota baca, dan seluruh koleksi tetap dapat diakses tanpa akun.
        </p>
<div class="grid grid-cols-2 gap-3 pt-1 max-w-md">
<div class="neo-card-sm p-3 bg-brand-yellow-light border-2 border-brand-dark">
<span class="material-symbols-outlined text-brand-dark">menu_book</span>
<p class="text-xs font-bold text-brand-dark mt-1">Koleksi terkurasi</p>
</div>
<div class="neo-card-sm p-3 bg-brand-blue-light border-2 border-brand-dark">
<span class="material-symbols-outlined text-brand-dark">download</span>
<p class="text-xs font-bold text-brand-dark mt-1">EPUB &amp; PDF</p>
</div>
<div class="neo-card-sm p-3 bg-brand-coral-light border-2 border-brand-dark">
<span class="material-symbols-outlined text-brand-dark">bookmark</span>
<p class="text-xs font-bold text-brand-dark mt-1">Rak baca pribadi</p>
</div>
<div class="neo-card-sm p-3 bg-brand-green-light border-2 border-brand-dark">
<span class="material-symbols-outlined text-brand-dark">public</span>
<p class="text-xs font-bold text-brand-dark mt-1">Domain publik</p>
</div>
</div>
</div>

<!-- Right: form -->
<div class="lg:col-span-7 flex justify-center lg:justify-end">
<div class="neo-card w-full max-w-md p-6 sm:p-8 bg-white shadow-solid-lg space-y-5">
<div class="flex items-center justify-between gap-3">
<div>
<span class="neo-pill bg-brand-green-light px-3 py-0.5 text-[11px] font-bold text-brand-dark">Pembaca Baru</span>
<h2 class="font-display font-extrabold text-2xl text-brand-dark mt-2">Buat akun libfree</h2>
</div>
<div class="w-11 h-11 rounded-full bg-brand-green border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-brand-dark">person_add</span>
</div>
</div>

<p v-if="errorMessage" class="rounded-xl bg-brand-coral-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="alert">
          {{ errorMessage }}
        </p>

<form class="space-y-4" @submit.prevent="submit">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="register-name">Nama lengkap</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">badge</span>
<input id="register-name" v-model="form.name" autocomplete="name" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="Nama yang tampil di ulasan" required maxlength="255" type="text">
</div>
</div>

<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="register-email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">mail</span>
<input id="register-email" v-model="form.email" autocomplete="email" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="nama@email.com" required type="email">
</div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="register-password">Kata sandi</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">lock</span>
<input id="register-password" v-model="form.password" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="Min. 8 karakter" required minlength="8">
</div>
<p class="text-[11px] font-semibold text-brand-muted">Minimal 8 karakter, kombinasikan huruf dan angka.</p>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="register-password-confirmation">Ulangi sandi</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">lock_reset</span>
<input id="register-password-confirmation" v-model="form.password_confirmation" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="Ulangi sandi" required minlength="8">
</div>
</div>
</div>

<label class="flex items-start gap-2.5 text-xs font-semibold text-brand-dark cursor-pointer leading-relaxed">
<input v-model="agreed" class="mt-0.5 w-4 h-4 rounded border-2 border-brand-dark text-brand-green focus:ring-0 shrink-0" type="checkbox">
<span>
            Saya menyetujui
            <RouterLink class="font-bold underline hover:text-brand-green" to="/syarat-ketentuan">Syarat &amp; Ketentuan</RouterLink>
            serta
            <RouterLink class="font-bold underline hover:text-brand-green" to="/kebijakan-privasi">Kebijakan Privasi</RouterLink>.
          </span>
</label>

<button class="neo-pill w-full py-3.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-solid disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2" type="submit" :disabled="auth.pending">
<span v-if="auth.pending" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
<span>{{ auth.pending ? 'Membuat akun…' : 'Daftar Gratis Sekarang' }}</span>
</button>
</form>

<p class="text-xs font-bold text-brand-dark text-center">
          Sudah punya akun?
          <RouterLink class="underline hover:text-brand-green" to="/masuk">Masuk di sini</RouterLink>
        </p>
</div>
</div>
</section>
<!-- END: RegisterPage -->
</main>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { errorMessage as messageFor } from '../lib/api'
import { auth, currentUser, fetchUser, register } from '../stores/auth'

const route = useRoute()
const router = useRouter()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})
const showPassword = ref(false)
const agreed = ref(false)
const errorMessage = ref('')

const redirect = computed(() => {
  const value = route.query.redirect

  return typeof value === 'string' && value.startsWith('/') && !value.startsWith('//') ? value : '/'
})

async function submit() {
  errorMessage.value = ''

  if (!agreed.value) {
    errorMessage.value = 'Centang persetujuan Syarat & Ketentuan untuk melanjutkan.'
    return
  }

  try {
    await register({ ...form })
    await router.replace(redirect.value)
  } catch (error) {
    errorMessage.value = messageFor(error)
  }
}

onMounted(async () => {
  await fetchUser()
  if (currentUser.value) await router.replace(redirect.value)
})
</script>
