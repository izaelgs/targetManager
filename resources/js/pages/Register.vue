<template>
    <div class="h-100 d-flex align-items-center">
        <div class="container rounded-3 border p-4">
            <Loader v-show="!loaded"></Loader>
            <div class="row">
                <div class="col-md-6">
                    <form @submit.stop.prevent="submit" class="row g-3">
                        <h1 class="h3 mb-3 fw-normal">{{ $t("register.create_account") }}</h1>
                        <em class="text-secondary">{{ $t("register.description") }}</em>

                        <div class="col-12">
                            <label for="name">{{ $t("register.name") }}</label>
                            <input v-model="name" type="text" class="form-control" name="name">
                        </div>
                        <div class="col-12">
                            <label for="email">E-mail</label>
                            <input v-model="email" type="email" class="form-control" name="email"
                                placeholder="name@example.com">
                        </div>
                        <div class="col-md-6">
                            <label for="password">{{ $t("register.password") }}</label>
                            <input v-model="password" type="password" class="form-control" name="password">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation">{{ $t("register.password_confirmation") }}</label>
                            <input v-model="password_confirmation" type="password" class="form-control" name="password">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">{{ $t("register.register") }}</button>
                        </div>
                        <div class="h-100 text-center">
                            {{ $t("register.have_account") }}
                            <router-link
                                :to="{ name: 'login' }"
                            >
                                <em>{{ $t("login.signin") }}</em>
                            </router-link>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-user-plus text-primary icon-lg"></i>
                    <em class="text-secondary py-2">{{ $t("register.large") }}</em>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Cookie from 'js-cookie';
import AppendToast from '../mixins/appendToast.vue';
import Api from '../mixins/Api.vue';
import Loader from '../components/Loader.vue';

export default {
    data() {
        return {

            loaded: false,

            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        }
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
            $('.is-invalid').removeClass('is-invalid');

            const payload = {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }

            this.post('user', payload, data => {
                this.loaded = true;
                this.$router.push('/login')
            }, err => {
                this.loaded = true;
                if(err.errors) {
                    Object.entries(err.errors).forEach(field => {
                        $(`[name="${field[0]}"`).addClass('is-invalid');
                    })
                }
            })
        },
    },

    components: {Loader},

    mixins: [Api]
}

</script>
