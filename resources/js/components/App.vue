<template>
    <main>
        <Sidebar></Sidebar>
        <content class="m-2">
            <router-view></router-view>
        </content>
        <footer class="bg-dark m-2 rounded-2 text-light text-center">
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
