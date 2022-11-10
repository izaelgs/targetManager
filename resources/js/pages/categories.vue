<template>
    <div class="container">
        <div class="row">
            <div
                v-for="categoriy in categories"
                class="col-md-4"
            >
                <div class="card">
                    <div class="p-1 d-flex justify-content-between align-items-center">
                        <h3 class="">
                            <i class="fs-1 bi bi-bookmark-fill"></i>
                            {{ categoriy.title }}
                        </h3>
                        <button class="btn">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <span class="badge bg-secondary">{{ categoriy.category.title }}</span>
                        <p class="card-text">{{ categoriy.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Cookie from 'js-cookie';
const token = Cookie.get('access_token');

export default {
    data() {
        return {
            categories: [],
        }
    },

    methods: {

    },

    created() {
        fetch('http://localhost:8000/api/category/', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
            }
        })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    this.categories = data;
                } else {
                    alert(data.error);
                }
            })
    }
}
</script>
