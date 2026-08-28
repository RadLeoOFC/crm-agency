<script setup>
import { ref } from "vue";
const isOpen = ref(false);
import Sidebar from "./Sidebar.vue";
import Footer from "./Footer.vue";
</script>

<template>
    <div>
        <header style="display: flex; background: green; padding: 10px">
            <nav style="align-items: center">
                <div style="display: flex; gap: 10px">
                    <a href="/" style="color: white">Home</a>
                    <a href="/testpage" style="color: white">Test page</a>
                </div>
            </nav>
        </header>
        <Sidebar />
        <main class="p-4">
            <slot />
        </main>
        <Footer />
    </div>
</template>
