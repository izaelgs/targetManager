<template>
    <div class="">
        <div class="row">
            <div v-if="categories" v-for="categoriy in categories" class="col-md-4 mb-2 mb-md-3">
                <div :class="['card', {'bg-secondary': !categoriy.is_father}]">
                    <div
                        class="p-1 d-flex justify-content-between align-items-center"
                    >
                        <h3 class="">
                            <i class="fs-1 bi bi-bookmark-fill"></i>
                            {{ categoriy.title }}
                        </h3>
                        <button class="btn">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <span v-if="categoriy.category" class="badge bg-primary">
                            {{
                                categoriy.category.title
                            }}
                        </span>
                        <span v-if="categoriy.categories" v-for="subcategory in categoriy.categories" class="badge bg-primary">
                            {{
                                subcategory.title
                            }}
                        </span>
                        <p class="card-text">{{ categoriy.description }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
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
        </div>
    </div>
</template>

<script>
import Cookie from "js-cookie";

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
        submit() {
            const payload = {
                title: this.title,
                description: this.description,
                categoryid: this.categoryid,
            };

            console.log(payload);

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
                    if (!data.error) {
                        this.appendToast(data.message,"success")
                        .then((element) => {
                            const toast = new bootstrap.Toast(element);
                            toast.show();
                        });

                        this.fetchData();
                    } else {
                        console.log(data.error);
                        this.appendToast(data.error,"danger")
                        .then((element) => {
                            const toast = new bootstrap.Toast(element);
                            toast.show();
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
                        this.categories = data;
                    } else {
                        alert(data.error);
                    }
                });
        },

        appendToast(mensagem, status) {
            return new Promise((resolve, reject) => {
                let toast = document.createElement("div");

                toast.classList.add("toast");
                toast.id = "liveToast";
                toast.role = "alert";
                toast.setAttribute("aria-live", "assertive");
                toast.setAttribute("aria-atomic", "true");

                toast.innerHTML = `
                <div class="toast-header  bg-${status} text-light">
                    <strong class="me-auto">TargetManager</strong>
                    <small>Agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${mensagem}
                </div>`;

                document.querySelector(".toast-container").append(toast);

                resolve(toast);
            });
        },
    },
};
</script>
