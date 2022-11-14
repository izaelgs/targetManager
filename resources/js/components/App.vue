<template>
    <main>
        <Sidebar></Sidebar>
        <content class="m-2 bg-light rounded p-md-4 mt-5 mt-md-0">
            <router-view></router-view>
        </content>
        <footer class="bg-dark m-2 rounded-2 text-light d-flex align-items-center justify-content-center">
            @Todos os direitos violados
        </footer>
        <!-- <Login></Login> -->
    </main>
</template>

<script>

import Login from '../pages/Login.vue';
import Sidebar from './Sidebar.vue';

export default {
    mounted() {
    },
    components: { Login, Sidebar }
}
</script>
