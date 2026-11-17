import { nextTick } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import AdminContent from './pages/AdminContent.vue'
import AdminSubmissions from './pages/AdminSubmissions.vue'
import BookDetail from './pages/BookDetail.vue'
import Home from './pages/Home.vue'
import Katalog from './pages/Katalog.vue'
import Legal from './pages/Legal.vue'
import Login from './pages/Login.vue'
import NotFound from './pages/NotFound.vue'
import PasswordRecovery from './pages/PasswordRecovery.vue'
import Rak from './pages/Rak.vue'
import Reader from './pages/Reader.vue'
import Register from './pages/Register.vue'
import Upload from './pages/Upload.vue'
import { setSeo } from './lib/seo'
import { auth, fetchUser } from './stores/auth'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: Home },
        { path: '/katalog', name: 'katalog', component: Katalog },
        { path: '/buku/:slug', name: 'buku.show', component: BookDetail },
        { path: '/baca/:slug', name: 'baca.show', component: Reader },
        { path: '/masuk', name: 'masuk', component: Login },
        { path: '/lupa-sandi', name: 'lupa-sandi', component: PasswordRecovery, props: { mode: 'forgot' } },
        { path: '/atur-ulang-sandi', name: 'atur-ulang-sandi', component: PasswordRecovery, props: { mode: 'reset' } },
        { path: '/daftar', name: 'daftar', component: Register },
        { path: '/rak', name: 'rak', component: Rak, meta: { requiresAuth: true } },
        { path: '/kebijakan-privasi', name: 'legal.privacy', component: Legal, props: { doc: 'privacy' } },
        { path: '/syarat-ketentuan', name: 'legal.terms', component: Legal, props: { doc: 'terms' } },
        { path: '/lisensi', name: 'legal.license', component: Legal, props: { doc: 'license' } },
        { path: '/unggah', name: 'unggah', component: Upload, meta: { requiresAuth: true } },
        { path: '/admin/unggahan', name: 'admin.unggahan', component: AdminSubmissions, meta: { requiresAuth: true, requiresAdmin: true } },
        { path: '/admin/konten', name: 'admin.konten', component: AdminContent, meta: { requiresAuth: true, requiresAdmin: true } },
        { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound },
    ],
    async scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            await nextTick()
            return { el: to.hash, behavior: 'smooth' }
        }

        return savedPosition || { top: 0 }
    },
})

router.beforeEach(async (to) => {
    if (!to.meta.requiresAuth) return true

    await fetchUser()

    if (!auth.user) {
        return { name: 'masuk', query: { redirect: to.fullPath } }
    }

    if (to.meta.requiresAdmin && !auth.user.is_admin) {
        return { name: 'home' }
    }

    return true
})

// Static metadata for routes whose content does not depend on a fetch.
// Book/reader pages update their own tags once the API payload arrives.
const STATIC_SEO = {
    home: {
        title: 'libfree — Baca Buku Klasik Gratis & Legal',
        description: 'Jelajahi koleksi buku domain publik dan berlisensi terbuka: sastra klasik, sejarah, sains, dan cerita rakyat nusantara. Gratis, tanpa iklan, dan telah melewati audit lisensi.',
    },
    katalog: {
        title: 'Katalog Buku — libfree',
        description: 'Cari buku berdasarkan judul, penulis, kategori, format, dan nilai kurasi. Semua judul yang tayang sudah melewati pemeriksaan status lisensi.',
    },
    'legal.privacy': {
        title: 'Kebijakan Privasi — libfree',
        description: 'Bagaimana libfree mengumpulkan, memakai, dan melindungi data pembaca, termasuk cookie sesi dan surel akun.',
    },
    'legal.terms': {
        title: 'Syarat & Ketentuan — libfree',
        description: 'Aturan penggunaan layanan libfree, hak dan kewajiban pembaca, serta ketentuan konten dan akun.',
    },
    'legal.license': {
        title: 'Lisensi & Hak Cipta — libfree',
        description: 'Panduan lisensi libfree: domain publik, Creative Commons, verifikasi sumber, dan prosedur pengajuan takedown.',
    },
    masuk: {
        title: 'Masuk — libfree',
        description: 'Masuk ke akun libfree untuk menyimpan buku ke rak baca dan menulis ulasan.',
        robots: 'noindex,nofollow',
    },
    daftar: {
        title: 'Daftar Akun — libfree',
        description: 'Buat akun pembaca libfree gratis untuk menyimpan buku dan berbagi ulasan.',
        robots: 'noindex,nofollow',
    },
    'lupa-sandi': {
        title: 'Lupa Kata Sandi — libfree',
        description: 'Minta tautan atur ulang kata sandi untuk akun libfree-mu.',
        robots: 'noindex,nofollow',
    },
    'atur-ulang-sandi': {
        title: 'Atur Ulang Kata Sandi — libfree',
        description: 'Buat kata sandi baru untuk akun libfree-mu.',
        robots: 'noindex,nofollow',
    },
    rak: {
        title: 'Rak Baca — libfree',
        description: 'Rak baca pribadi berisi judul yang kamu simpan di libfree.',
        robots: 'noindex,nofollow',
    },
    unggah: {
        title: 'Unggah Naskah — libfree',
        description: 'Kirim naskah domain publik atau berlisensi terbuka untuk ditinjau kurator libfree.',
        robots: 'noindex,nofollow',
    },
    'admin.unggahan': {
        title: 'Tinjau Unggahan — libfree',
        description: 'Panel kurator untuk meninjau naskah yang dikirim pembaca.',
        robots: 'noindex,nofollow',
    },
    'admin.konten': {
        title: 'Audit Konten — libfree',
        description: 'Panel kurator untuk memeriksa status lisensi dan publikasi koleksi.',
        robots: 'noindex,nofollow',
    },
    'not-found': {
        title: 'Halaman Tidak Ditemukan — libfree',
        description: 'Halaman atau naskah yang kamu cari tidak tersedia di libfree.',
        robots: 'noindex,nofollow',
        canonical: false,
    },
}

router.afterEach((to) => {
    if (to.name === 'buku.show' || to.name === 'baca.show') {
        setSeo({
            title: 'Memuat buku — libfree',
            description: 'Memuat detail buku dari katalog libfree.',
            canonical: to.name === 'buku.show' ? window.location.origin + to.path : null,
            robots: to.name === 'baca.show' ? 'noindex,follow' : 'index,follow',
            jsonLd: [],
        })
        return
    }

    const meta = STATIC_SEO[to.name] ?? STATIC_SEO['not-found']

    setSeo({
        ...meta,
        // Canonical URLs deliberately drop query strings and hashes.
        canonical: meta.canonical === false ? null : window.location.origin + to.path,
    })
})

export default router
