<template>
    <div class="">
        <div class="row">
            <div class="col-md-12 mb-2 mb-md-3">
                <form @submit.stop.prevent="submit" class="card">
                    <div class="card-body">
                        <div
                            class="d-flex justify-content-between align-items-center"
                        >
                            <i class="fs-1 bi bi-bookmark-plus-fill"></i>
                            <input
                                v-model="title"
                                type="text"
                                class="form-control"
                            />
                        </div>

                        <select v-model="categoryid" class="form-control">
                            <option value="">Seleciona Uma Categoria</option>
                            <option
                                v-if="father_categories"
                                v-for="category in father_categories"
                                :value="category.id"
                                :key="category.id"
                            >
                                {{ category.title }}
                            </option>
                        </select>

                        <textarea
                            v-model="description"
                            class="form-control my-2"
                        ></textarea>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                Adicionar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div v-if="editableCategories" v-for="category in editableCategories" class="col-md-4 mb-2 mb-md-3">
                <form
                    @submit.stop.prevent="submitEdit(category)"
                    :class="['card', {'bg-secondary': !category.is_father}]"
                >
                    <div
                        class="p-1 d-flex justify-content-between align-items-center"
                    >
                        <h3 v-show="!category.edit" class="">
                            <i class="fs-1 bi bi-bookmark-fill"></i>
                            {{ category.title }}
                        </h3>
                        <input
                            v-show="category.edit"
                            v-model="category.title"
                            type="text" name="title"
                            class="form-control mt-3 ms-3"
                        >
                        <button @click.prevent="edit(category)" class="btn">
                            <i :class="['bi', category.edit ? 'bi-x-lg' : 'bi-pencil']"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <span v-show="!category.edit" v-if="category.category" class="badge bg-primary">
                            {{
                                category.category.title
                            }}
                        </span>
                        <span v-if="category.categories" v-for="subcategory in category.categories" class="badge bg-primary">
                            {{
                                subcategory.title
                            }}
                        </span>

                        <select v-show="category.edit" v-model="category.categoryid" class="form-control mb-2">
                            <option value="">Seleciona Uma Categoria</option>
                            <option
                                v-if="father_categories"
                                v-for="category in father_categories"
                                :value="category.id"
                                :key="category.id"
                            >
                                {{ category.title }}
                            </option>
                        </select>

                        <p v-show="!category.edit" class="card-text">{{ category.description }}</p>
                        <textarea
                            v-show="category.edit"
                            v-model="category.description"
                            name="description"
                            class="form-control mb-2" cols="30" rows="5"
                        ></textarea>

                        <div
                            v-if="category.edit"
                            class="d-grid gap-2"
                        >
                            <button class="btn btn-primary" type="submit">
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Cookie from "js-cookie";
import AppendToast from "../mixins/appendToast.vue";

export default {
    data() {
        return {
            categories: [],

            title: "",
            description: "",
            categoryid: "",

            access_token: "",
        };
    },

    computed: {

        editableCategories() {
            return this.categories;
        },

        father_categories() {
            return this.categories.filter(category => category.is_father);
        }
    },

    created() {
        this.access_token = Cookie.get("access_token");
        this.fetchData();
    },

    mounted() {
        const toastTrigger = document.getElementById("liveToastBtn");
        const toastLiveExample = document.getElementById("teste");
        if (toastTrigger) {
            toastTrigger.addEventListener("click", () => {
                const toast = new bootstrap.Toast(toastLiveExample);

                toast.show();
            });
        }
    },

    methods: {

        edit(category) {
            category.edit = !category.edit;
        },

        submitEdit(category) {

            const payload = {
                title: category.title,
                description: category.description,
                categoryid: category.categoryid,
            };

            fetch("http://127.0.0.1:8000/api/category/" + category.id, {
                method: "PUT",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
                body: JSON.stringify(payload),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.error) {
                        this.appendToast(data.message,"success")
                        .then((element) => {
                            const toast = new bootstrap.Toast(element);
                            toast.show();
                        });

                        this.fetchData();
                    } else {
                        this.appendToast(data.error,"danger")
                        .then((element) => {
                            const toast = new bootstrap.Toast(element);
                            toast.show();
                        });
                    }
                });
        },

        submit() {
            const payload = {
                title: this.title,
                description: this.description,
                categoryid: this.categoryid,
            };

            fetch("http://127.0.0.1:8000/api/category", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
                body: JSON.stringify(payload),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.errors) {
                        this.showToast(data.message,"success");
                        this.fetchData();
                    } else {
                        Object.entries(data.errors).forEach(error => {
                            this.showToast(error, "danger", 'exclamation-triangle-fill');
                        });
                    }
                });
        },

        fetchData() {
            fetch("http://localhost:8000/api/category/", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    this.title = this.description = this.categoryid = "";

                    if (!data.error) {
                        this.categories = data.map(category => {
                            category.edit = false;
                            return category;
                        });
                    } else {
                        this.showToast(data.error ?? 'Erro desconhecido', 'danger', 'exclamation-octagon-fill');
                    }
                });
        },
    },

    mixins: [AppendToast],
};
</script>
