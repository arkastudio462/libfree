<template>
    <AmbientBackground />
    <MainNavbar />
    <RouterView />
    <MainFooter />
</template>

<script setup>
import { onMounted } from 'vue'
import AmbientBackground from './components/AmbientBackground.vue'
import MainFooter from './components/MainFooter.vue'
import MainNavbar from './components/MainNavbar.vue'
import { fetchUser } from './stores/auth'

onMounted(() => fetchUser())
</script>
