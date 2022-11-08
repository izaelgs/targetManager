<template>
    <div class="h-100 d-flex justify-content-center align-items-center">
        <form @submit.stop.prevent="submit" class="form-signin">
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

            <div class="form-floating mt-2">
                <input v-model="email" type="email" class="form-control" id="floatingInput"
                    placeholder="name@example.com">
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating mt-2">
                <input v-model="password" type="password" class="form-control" id="floatingPassword"
                    placeholder="Password">
                <label for="floatingPassword">Senha</label>
            </div>
            <button class="w-100 btn btn-lg btn-primary mt-2" type="submit">Entrar</button>
        </form>
    </div>
</template>

<script>

import Cookie from 'js-cookie';

export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },

    created() {
        Cookie.remove('access_token')
    },

    methods: {
        submit() {

            const payload = {
                email: this.email,
                password: this.password,
            }

            fetch('http://127.0.0.1:8000/api/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then (data => {
                if(!data.error) {
                    Cookie.set('access_token', data.access_token);
                    this.$router.push('/');
                } else {
                    console.log(data.error);
                }
            })
        },
    }
}

</script>
