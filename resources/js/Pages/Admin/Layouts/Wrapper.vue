<template>
    <!--==================== HEADER ====================-->
    <Header />
    <!-- Nav Main -->
    <Sidebar />
    <!-- End Nav Main -->
    <main class="main">
        <!-- Side Nav Dummy-->
        <div class="main__sideNav"></div>
        <!-- End Side Nav -->
        <!-- Main Content -->
        <div class="main__content">
            <RouterView />
        </div>
    </main>
</template>

<script setup>
import { RouterView } from 'vue-router';
import Header from './Header.vue';
import Sidebar from './Sidebar.vue';


</script>
<style>
@import '../../../../css/admin/css/wrapper.css';
</style>
