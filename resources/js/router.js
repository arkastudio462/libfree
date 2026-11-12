import { nextTick } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import BookDetail from './pages/BookDetail.vue'
import Home from './pages/Home.vue'
import Katalog from './pages/Katalog.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: Home },
        { path: '/katalog', name: 'katalog', component: Katalog },
        { path: '/buku/:slug', name: 'buku.show', component: BookDetail },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
    async scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            await nextTick()
            return { el: to.hash, behavior: 'smooth' }
        }

        return savedPosition || { top: 0 }
    },
})

export default router
