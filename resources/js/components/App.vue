<template>
    <main>
        <Sidebar></Sidebar>
        <content class="m-2 bg-light rounded p-md-4 mt-5 mt-md-0 d-flex flex-column justify-content-between">
            <router-view></router-view>
            <hr>
            <Footer></Footer>
        </content>
    </main>
</template>

<script>

import Login from '../pages/Login.vue';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

export default {
    mounted() {
    },
    components: { Login, Sidebar, Footer }
}
</script>
