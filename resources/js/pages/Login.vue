<template>
    <div class="h-100 d-flex justify-content-center align-items-center">
        <Loader v-show="!loaded"></Loader>
        <form @submit.stop.prevent="submit" class="form-signin text-center p-4 border rounded">
            <h1 class="h3 mb-3 fw-normal">{{ $t("login.psignin") }}</h1>

            <div class="form-floating mt-2">
                <input v-model="email" type="email" class="form-control" id="floatingInput"
                    placeholder="name@example.com" required>
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating mt-2">
                <input v-model="password" type="password" class="form-control" id="floatingPassword"
                    placeholder="Password" required>
                <label for="floatingPassword">{{ $t("login.password") }}</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary my-2" type="submit">{{ $t("login.signin") }}</button>

            <div class="h-100 text-center">
                {{ $t("login.dont_have_account") }}
                <router-link
                    :to="{ name: 'register' }"
                >
                    <em>{{ $t("login.signup") }}</em>
                </router-link>
            </div>
        </form>
    </div>
</template>

<script>

import Cookie from 'js-cookie';
import AppendToast from '../mixins/appendToast.vue';
import Loader from '../components/Loader.vue';
import Api from '../mixins/Api.vue';

export default {
    data() {
        return {

            loaded: false,

            email: '',
            password: '',
        }
    },

    computed: {
        token() {
            return this.$store.state.token;
        },
    },

    created() {
        Cookie.remove('access_token')
    },

    mounted() {
        this.loaded = true;
    },

    methods: {
        submit() {
            this.loaded = false;
            const payload = {
                email: this.email,
                password: this.password,
            }

            this.post('login', payload, data => {
                this.loaded = true;
                if(!data.error) {
                    Cookie.set('access_token', data.access_token);

                    this.$store.state.token = data.access_token;

                    this.$router.push('/');
                } else {
                    this.showToast(data.error == 'Unauthorized' ? 'E-mail ou Senha incorretos' : 'Erro desconhecido', 'danger');
                }
            }, err => {
                this.loaded = true;
            }, true)
        },
    },

    components: {Loader},

    mixins: [AppendToast, Api]
}

</script>
