<template>
    <AmbientBackground />
    <MainNavbar />
    <RouterView />
    <MainFooter />
    <MobileFloatingNav />
</template>

<script setup>
import AmbientBackground from './components/AmbientBackground.vue'
import MainFooter from './components/MainFooter.vue'
import MainNavbar from './components/MainNavbar.vue'
import MobileFloatingNav from './components/MobileFloatingNav.vue'
</script>
