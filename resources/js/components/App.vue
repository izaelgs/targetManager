<template>
    <main>
        <Sidebar></Sidebar>
        <content class="m-2 bg-light rounded pb-0 p-md-4 pb-md-0 mt-5 my-md-2 d-flex flex-column justify-content-between">
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
import Api from '../mixins/Api.vue';

export default {

    computed: {
        token() {
            return this.$store.state.token;
        }
    },

    mounted() {
        this.$watch(
            () => this.token,
            (toParams, previousParams) => {

                if(!this.token) return;

                this.get('target/', data => {
                    if (!data.error) {
                        this.$store.state.targets = data;
                    } else {
                        alert(data.error);
                    }
                }, null, true);

            }
        );
    },
    components: { Login, Sidebar, Footer },

    mixins: [Api]
}
</script>
