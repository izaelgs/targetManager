<template>
    <nav class="bg-primary m-2 rounded-2 p-2">
            <router-link :to="{ name: 'home' }" class="nav-link fw-bolder fs-2 text-info" exact>TargetManager
            </router-link>
            <ul class="nav flex-column">
                <li>
                    <router-link :to="{ name: 'new-target' }" class="nav-link d-flex align-items-center">
                        <i class="bi-plus-circle me-2" style="font-size: 1.5rem; color: cornflowerblue;"></i> Adicionar
                        Objetivo
                    </router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'categories' }" class="nav-link d-flex align-items-center">
                        <i class="bi-tags me-2" style="font-size: 1.5rem; color: cornflowerblue;"></i> Categorias
                    </router-link>
                </li>
            </ul>
            <ul v-if="targets.length" v-for="target in targets" class="nav flex-column" :key="target.id">
                <router-link :to="{ path: '/target/' + target.id }" exact
                    class="nav-link d-flex align-items-center text-white">
                    <i class="bi-bullseye me-2" style="font-size: 1.5rem; color: cornflowerblue;"></i> {{ target.title
                    }}
                </router-link>
            </ul>
        </nav>
</template>

<script>

import Cookie from 'js-cookie';

export default {
    data() {
        return {
            targets: []
        }
    },
    created() {
        const token = Cookie.get('access_token');
        fetch('http://localhost:8000/api/target/', {
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
                    this.targets = data;
                } else {
                    alert(data.error);
                }
            })
    },
}
</script>
