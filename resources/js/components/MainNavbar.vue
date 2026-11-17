<template>
<!-- BEGIN: MainNavbar -->
<!-- Sticky navigation bar with rounded container -->
<header class="sticky top-2 sm:top-4 z-50 px-3 sm:px-6 lg:px-8 mb-6 sm:mb-8 max-w-8xl mx-auto" data-purpose="site-header">
<div class="relative neo-card py-2.5 sm:py-3.5 px-3.5 sm:px-5 md:px-7 flex items-center justify-between gap-3 shadow-solid transition-all bg-white/95 backdrop-blur-md">
<!-- Brand Logo -->
<RouterLink aria-label="libfree Beranda" class="flex items-center gap-2 sm:gap-2.5 group shrink-0" to="/">
<div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center transition-transform group-hover:rotate-6">
<svg class="w-5 h-5 text-brand-dark" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<span class="font-display font-extrabold text-xl sm:text-2xl tracking-tight text-brand-dark">lib<span class="text-brand-green">free</span></span>
</RouterLink>
<!-- Desktop Navigation Menu -->
<nav aria-label="Menu Utama" class="hidden lg:flex items-center gap-7 text-sm font-bold text-brand-dark">
<RouterLink v-for="link in navLinks" :key="link.to" class="hover:text-brand-green transition-colors" :to="link.to">{{ link.label }}</RouterLink>
</nav>
<!-- Auth and Quick Actions -->
<div class="flex items-center gap-2 sm:gap-3 shrink-0">
<div v-if="auth.ready" class="hidden sm:flex items-center gap-2.5 sm:gap-3">
<template v-if="currentUser">
<RouterLink class="neo-pill px-3.5 sm:px-4 py-2 text-xs sm:text-sm bg-brand-cream hover:bg-brand-yellow-light text-brand-dark" to="/rak">
            Rak Baca
          </RouterLink>
<RouterLink v-if="currentUser.is_admin" class="neo-pill px-3 py-2 text-xs bg-brand-green-light hover:bg-brand-green/20 text-brand-dark" to="/admin/konten" title="Audit Konten">
            Konten
          </RouterLink>
<RouterLink v-if="currentUser.is_admin" class="neo-pill px-3 py-2 text-xs bg-brand-blue-light hover:bg-brand-blue/20 text-brand-dark" to="/admin/unggahan" title="Moderasi Unggahan">
            Unggahan
          </RouterLink>
<div class="flex items-center gap-1.5">
<span class="hidden md:flex items-center gap-1.5 rounded-full border-2 border-brand-dark bg-white px-1.5 py-1">
<span class="w-6 h-6 rounded-full bg-brand-green border border-brand-dark flex items-center justify-center text-[10px] font-extrabold text-brand-dark">{{ currentUser.initials }}</span>
<span class="text-xs font-bold text-brand-dark max-w-[7rem] truncate">{{ currentUser.name }}</span>
</span>
<button class="w-9 h-9 rounded-full border-2 border-brand-dark bg-white hover:bg-brand-coral-light flex items-center justify-center text-brand-dark" type="button" aria-label="Keluar dari akun" title="Keluar" @click="handleLogout">
<span class="material-symbols-outlined text-lg">logout</span>
</button>
</div>
</template>
<template v-else>
<RouterLink class="neo-pill px-4 py-2 text-xs sm:text-sm bg-white hover:bg-gray-50 text-brand-dark" to="/masuk">
          Masuk
        </RouterLink>
<RouterLink class="neo-pill px-4 sm:px-5 py-2 text-xs sm:text-sm bg-brand-yellow hover:bg-[#ebae34] text-brand-dark" to="/daftar">
          Daftar Gratis
        </RouterLink>
</template>
</div>
<!-- Mobile menu toggle -->
<button class="lg:hidden w-10 h-10 rounded-full border-2 border-brand-dark bg-brand-cream hover:bg-brand-yellow-light flex items-center justify-center text-brand-dark transition-colors" type="button" :aria-expanded="mobileOpen" aria-controls="mobile-menu" :aria-label="mobileOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'" @click="mobileOpen = !mobileOpen">
<span class="material-symbols-outlined text-xl">{{ mobileOpen ? 'close' : 'menu' }}</span>
</button>
</div>
<!-- Mobile Navigation Panel -->
<Transition name="menu">
<div v-if="mobileOpen" id="mobile-menu" class="lg:hidden absolute left-0 right-0 top-[calc(100%+0.65rem)] neo-card bg-white shadow-solid-lg p-3.5 sm:p-4 max-h-[calc(100vh-7rem)] overflow-y-auto">
<nav aria-label="Menu Mobile" class="grid grid-cols-2 gap-2 text-sm font-bold text-brand-dark">
<RouterLink v-for="link in navLinks" :key="'m-' + link.to" class="flex items-center justify-between gap-2 rounded-2xl border-2 border-brand-dark/10 bg-brand-cream px-3.5 py-2.5 hover:bg-brand-yellow-light transition-colors" :to="link.to" @click="mobileOpen = false">
<span>{{ link.label }}</span>
<span class="material-symbols-outlined text-base text-brand-muted">arrow_outward</span>
</RouterLink>
</nav>
<div v-if="currentUser" class="mt-3 pt-3 border-t-2 border-brand-dark/10 space-y-2">
<div class="flex items-center gap-2.5 rounded-2xl bg-brand-cream border-2 border-brand-dark/10 px-3 py-2.5 min-w-0">
<span class="w-8 h-8 shrink-0 rounded-full bg-brand-green border-2 border-brand-dark flex items-center justify-center text-[11px] font-extrabold text-brand-dark">{{ currentUser.initials }}</span>
<div class="min-w-0">
<p class="text-sm font-bold text-brand-dark truncate">{{ currentUser.name }}</p>
<p class="text-[11px] font-semibold text-brand-muted truncate">{{ currentUser.email }}</p>
</div>
</div>
<div class="grid grid-cols-2 gap-2">
<RouterLink class="neo-pill w-full px-4 py-2.5 text-sm bg-brand-cream hover:bg-brand-yellow-light text-brand-dark" to="/rak" @click="mobileOpen = false">
              Rak Baca
            </RouterLink>
<button class="neo-pill w-full px-4 py-2.5 text-sm bg-white hover:bg-brand-coral-light text-brand-dark" type="button" @click="handleLogout">
              Keluar
            </button>
</div>
<RouterLink v-if="currentUser.is_admin" class="neo-pill w-full px-4 py-2.5 text-sm bg-brand-green-light hover:bg-brand-green/20 text-brand-dark" to="/admin/konten" @click="mobileOpen = false">
            Audit Konten
          </RouterLink>
<RouterLink v-if="currentUser.is_admin" class="neo-pill w-full px-4 py-2.5 text-sm bg-brand-blue-light hover:bg-brand-blue/20 text-brand-dark" to="/admin/unggahan" @click="mobileOpen = false">
            Moderasi Unggahan
          </RouterLink>
</div>
<div v-else class="mt-3 pt-3 border-t-2 border-brand-dark/10 grid grid-cols-2 gap-2">
<RouterLink class="neo-pill w-full px-4 py-2.5 text-sm bg-white hover:bg-gray-50 text-brand-dark" to="/masuk" @click="mobileOpen = false">
            Masuk
          </RouterLink>
<RouterLink class="neo-pill w-full px-4 py-2.5 text-sm bg-brand-yellow hover:bg-[#ebae34] text-brand-dark" to="/daftar" @click="mobileOpen = false">
            Daftar Gratis
          </RouterLink>
</div>
</div>
</Transition>
</div>
</header>
<!-- END: MainNavbar -->
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { auth, currentUser, logout } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const mobileOpen = ref(false)

const navLinks = [
  { label: 'Beranda', to: '/' },
  { label: 'Jelajahi', to: '/katalog' },
  { label: 'Kategori', to: '/#kategori' },
  { label: 'Penulis', to: '/#peringkat' },
  { label: 'Koleksi', to: '/#koleksi-khusus' },
  { label: 'Tentang', to: '/#tentang' },
]

// Tutup panel saat berpindah halaman/anchor atau menekan Escape.
watch(() => route.fullPath, () => {
  mobileOpen.value = false
})

function onKeydown(event) {
  if (event.key === 'Escape') mobileOpen.value = false
}

async function handleLogout() {
  mobileOpen.value = false

  try {
    await logout()
  } finally {
    if (route.meta.requiresAuth) await router.push('/')
  }
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown))
</script>

<style scoped>
.menu-enter-active,
.menu-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}

.menu-enter-from,
.menu-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
