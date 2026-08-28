<script setup>
import Layout from "../Layouts/Layout.vue";
</script>

<template>
    <Layout>
        <h1 style="font-size: 45px; margin-bottom: 30px">
            Welcome to the advertising space management system!
        </h1>
        <p style="margin-bottom: 70px">
            Submit advertising placement requests and we will place your ads on
            our platforms.
        </p>
        <div class="button-container">
            <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-4 px-8 rounded-full m-5 shadow-lg"
            >
                <a href="{{ url('/dashboard') }}" class="btn btn-secondary mb-3"
                    >Dashboard</a
                >
            </button>
            <button
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-full m-5 shadow-lg"
            >
                <a href="{{ url('/register') }}" class="btn btn-secondary mb-3"
                    >Register</a
                >
            </button>
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full m-5 shadow-lg"
            >
                <a href="{{ url('/login') }}" class="btn btn-secondary mb-3"
                    >Login</a
                >
            </button>
        </div>
    </Layout>
</template>
