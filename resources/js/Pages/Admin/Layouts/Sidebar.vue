<template>
    <nav class="nav">
        <div class="nav_wrapper">
            <span class="nav_close">
                <i class="fas fa-window-close"></i>
            </span>
            <ul class="nav_list">
                <li class="nav_item" v-for="(item, index) in navLinks" :key="index">
                    <RouterLink class="nav_link" :to="`/admin${item.to}`">
                        <span class="nav_link--span--icon">
                            <i class="nav__link--icon" :class="item.icon"></i>
                        </span>
                        <span class="nav_link--span--navname">{{ item.name }}</span>
                    </RouterLink>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';

const navLinks = ref([
    { to: '/home', name: 'Home', icon: 'fas fa-home' },
    { to: '/about', name: 'About Us', icon: 'fas fa-home' },
    { to: '/services', name: 'Services', icon: 'fas fa-home' },
]);

</script>
