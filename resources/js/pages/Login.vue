<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 pt-4 lg:pt-8">
<!-- BEGIN: LoginPage -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center" data-purpose="login-page">
<!-- Left: copy -->
<div class="lg:col-span-5 space-y-5">
<div class="inline-flex items-center gap-2 neo-pill bg-brand-yellow-light px-4 py-1.5 text-xs sm:text-sm text-brand-dark">
<span class="w-2.5 h-2.5 rounded-full bg-brand-green border border-brand-dark animate-pulse"></span>
<span class="font-bold">Ruang baca pribadimu menunggu</span>
</div>
<h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-brand-dark tracking-tight leading-tight">
          Masuk, lanjutkan <span class="relative inline-block">bacaanmu<span class="absolute left-0 bottom-1 w-full h-3 bg-brand-yellow/60 -z-10 rounded"></span></span>.
        </h1>
<p class="text-sm sm:text-base text-brand-muted max-w-xl leading-relaxed font-medium">
          Simpan buku ke rak baca, tulis ulasan, dan lanjutkan membaca dari perangkat mana pun. Semua tetap gratis dan terbuka.
        </p>
<ul class="space-y-3 pt-1 text-sm font-semibold text-brand-dark">
<li class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-brand-green-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-base">bookmark</span>
</span>
            Rak baca tersimpan lintas perangkat
          </li>
<li class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-brand-blue-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-base">history</span>
</span>
            Lanjutkan bacaan terakhir kapan saja
          </li>
<li class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-brand-coral-light border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-base">reviews</span>
</span>
            Ulasan dan catatan komunitas
          </li>
</ul>
</div>

<!-- Right: form -->
<div class="lg:col-span-7 flex justify-center lg:justify-end">
<div class="neo-card w-full max-w-md p-6 sm:p-8 bg-white shadow-solid-lg space-y-5">
<div class="flex items-center justify-between gap-3">
<div>
<span class="neo-pill bg-brand-yellow px-3 py-0.5 text-[11px] font-bold text-brand-dark">Akun Pembaca</span>
<h2 class="font-display font-extrabold text-2xl text-brand-dark mt-2">Masuk ke libfree</h2>
</div>
<div class="w-11 h-11 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-brand-dark">login</span>
</div>
</div>

<p v-if="errorMessage" class="rounded-xl bg-brand-coral-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="alert">
          {{ errorMessage }}
        </p>

<form class="space-y-4" @submit.prevent="submit">
<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="login-email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">mail</span>
<input id="login-email" v-model="form.email" autocomplete="email" class="w-full neo-pill pl-11 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="nama@email.com" required type="email">
</div>
</div>

<div class="space-y-1.5">
<label class="text-xs font-bold text-brand-dark block" for="login-password">Kata sandi</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-muted text-lg pointer-events-none">lock</span>
<input id="login-password" v-model="form.password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" class="w-full neo-pill pl-11 pr-12 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:bg-white text-brand-dark placeholder-brand-muted" placeholder="Minimal 8 karakter" required minlength="8">
<button class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full hover:bg-brand-yellow-light flex items-center justify-center text-brand-muted" type="button" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" @click="showPassword = !showPassword">
<span class="material-symbols-outlined text-lg">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
</button>
</div>
</div>
<div class="flex justify-end">
<RouterLink class="text-[11px] font-bold text-brand-dark underline hover:text-brand-green" to="/lupa-sandi">Lupa kata sandi?</RouterLink>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
<label class="flex items-center gap-2 text-xs font-semibold text-brand-dark cursor-pointer">
<input v-model="form.remember" class="w-4 h-4 rounded border-2 border-brand-dark text-brand-green focus:ring-0" type="checkbox">
            Ingat saya di perangkat ini
          </label>
<RouterLink class="text-xs font-bold text-brand-dark underline hover:text-brand-green" to="/daftar">
            Belum punya akun? Daftar
          </RouterLink>
</div>

<button class="neo-pill w-full py-3.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm font-extrabold border-2 border-brand-dark shadow-solid disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2" type="submit" :disabled="auth.pending">
<span v-if="auth.pending" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
<span>{{ auth.pending ? 'Memeriksa akun…' : 'Masuk dan Mulai Membaca' }}</span>
</button>
</form>

<p class="text-[11px] text-brand-muted font-medium text-center leading-relaxed pt-1">
          Dengan masuk, kamu menyetujui
          <RouterLink class="font-bold text-brand-dark underline" to="/syarat-ketentuan">Syarat &amp; Ketentuan</RouterLink>
          dan
          <RouterLink class="font-bold text-brand-dark underline" to="/kebijakan-privasi">Kebijakan Privasi</RouterLink>.
        </p>
</div>
</div>
</section>
<!-- END: LoginPage -->
</main>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { errorMessage as messageFor } from '../lib/api'
import { auth, currentUser, fetchUser, login } from '../stores/auth'

const route = useRoute()
const router = useRouter()

const form = reactive({ email: '', password: '', remember: false })
const showPassword = ref(false)
const errorMessage = ref('')

const redirect = computed(() => {
  const value = route.query.redirect

  return typeof value === 'string' && value.startsWith('/') && !value.startsWith('//') ? value : '/'
})

async function submit() {
  errorMessage.value = ''

  try {
    await login({ ...form })
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
