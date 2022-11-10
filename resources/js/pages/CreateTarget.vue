<template>
    <div>
        <section class="inscricao mb-2 rounded" id="estudante">
            <h2>Adicionar Objetivo</h2>
            <p>Um objetivo pode fazer parte de uma séria de categorias e conter diversas metas</p>

            <form @submit.stop.prevent="submit" class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Titulo</label>
                    <input type="name" class="form-control" id="title" v-model="title" placeholder="insira um titulo">
                </div>
                <div class="col-md-6">
                    <label for="categories" class="form-label">
                        Categorias:
                        <span
                            v-if="selected_categories" v-for="category in selected_categories"
                            class="badge bg-secondary"
                        >
                            {{ category.title }}
                        </span>
                    </label>
                    <select id="categories" @change="addCategory" v-model="selected_category" class="form-select">
                        <option value="" hidden>Seleciona Uma Categoria</option>
                        <option v-if="categories" v-for="category in categories"
                            :value="category.id"
                            :key="category.id"
                            :disabled="category.disabled">
                            {{ category.title }}
                        </option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Descricao</label>
                    <textarea class="form-control" id="description" v-model="description" rows="3"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="deadline" class="form-label">Prazo</label>
                    <input type="date" class="form-control" id="deadline" v-model="deadline">
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">Prioridade</label>
                    <input type="number" class="form-control" id="priority" v-model="priority" min="0" max="10">
                </div>
                <div class="col-md-6">
                    <label for="cost" class="form-label">Custo</label>
                    <input type="number" class="form-control" id="cost" v-model="cost" min="0" max="10">
                </div>
                <div class="col-md-6">
                    <label for="gain" class="form-label">Ganho</label>
                    <input type="number" class="form-control" id="gain" v-model="gain" min="0" max="10">
                </div>
                <div class="col-12 d-grid gap-2">
                    <button type="submit" class="btn btn-secondary">Cadastrar</button>
                </div>
            </form>
        </section>

    </div>
</template>

<script>

import Cookie from 'js-cookie';

export default {
    data() {
        return {
            categories: [],
            selected_categories: [],
            selected_category: '',
            title: '',
            deadline: '',
            description: '',
            cost: 0,
            gain: 0,
            priority: 0,
        }
    },

    created() {
        const token = Cookie.get('access_token');

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
    },

    methods: {
        submit() {
            const payload = encodeUrl({
                title: this.title,
                deadline: this.deadline,
                description: this.description,
                cost: this.cost,
                gain: this.gain,
                priority: this.priority,
                'categories[]': this.selected_categories.map(category => { return category.id }),
            });

            fetch('http://127.0.0.1:8000/api/target', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'Bearer ' + token,
                },
                body: payload
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        alert('coisado kkk');
                    } else {
                        console.log(data.error);
                    }
                })
        },

        addCategory() {

            let category = this.categories.find(c => c.id == this.selected_category);
            this.selected_categories.push(category);

            this.categories.find(c => c.id == this.selected_category).disabled = 'disabled';
        }
    },
}

function encodeUrl(payload) {
    var formBody = [];
    for (var property in payload) {
        if (Array.isArray(payload[property])) {
            for (var item in payload[property]) {
                var encodedKey = encodeURIComponent(property);
                var encodedValue = encodeURIComponent(payload[property][item]);
                formBody.push(encodedKey + "=" + encodedValue);
            }
        } else {
            var encodedKey = encodeURIComponent(property);
            var encodedValue = encodeURIComponent(payload[property]);
            formBody.push(encodedKey + "=" + encodedValue);
        }
    }
    formBody = formBody.join("&");

    return formBody;
}
</script>
