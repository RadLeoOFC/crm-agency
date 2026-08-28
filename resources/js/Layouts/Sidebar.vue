<script setup>
import { ref } from "vue";
const isOpen = ref(false);
</script>

<template>
    <button @click="isOpen = !isOpen">Toggle Sidebar</button>

    <div :class="['sidebar', { 'is-active': isOpen }]">
        <div>
            <a href="dashboard">Dashboard</a>
            <a href="platforms">Platforms</a>
        </div>
    </div>
</template>

<style scoped>
.sidebar {
    position: fixed;
    left: -250px;
    width: 250px;
    height: 100%;
    transition: 0.3s;
}
.sidebar.is-active {
    left: 0;
}
</style>
