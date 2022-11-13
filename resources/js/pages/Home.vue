<template>
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <ul class="col-12 col-lg-6 nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Ordenar por:
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Crescente</a
                                >
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Decrescente</a
                                >
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Classificar por por:
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Nome</a
                                >
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Custo</a
                                >
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Ganho</a
                                >
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Prioridade</a
                                >
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    >Prazo</a
                                >
                            </li>
                        </ul>
                    </li>
                </ul>
                <form class="col d-flex" role="search">
                    <input
                        class="form-control me-2"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                    />
                    <button
                        class="btn btn-outline-success"
                        type="submit"
                    >
                        Search
                    </button>
                </form>
            </div>
            <table class="table table-light">
                <thead>
                    <tr>
                        <th>Objetivo</th>
                        <th class="text-center">Custo</th>
                        <th class="text-center">Ganho</th>
                        <th class="text-center">Prioridade</th>
                        <th class="text-center">Prazo</th>
                        <th class="text-center">Urgência</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-if="targets.length"
                        v-for="target in targets"
                        :key="target.id"
                    >
                        <th scope="row">{{ target.title }}</th>
                        <td class="text-center">{{ target.cost }}</td>
                        <td class="text-center">{{ target.gain }}</td>
                        <td class="text-center">{{ target.priority }}</td>
                        <td class="text-center">{{ target.deadline }}</td>
                        <td class="text-center">
                            {{ target.sugested_priority }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Categoria</div>
                <div class="accordion accordion-flush" id="accordionCategories">
                    <div
                        class="accordion-item"
                        v-for="category in categories"
                        :key="category.id"
                    >
                        <h2
                            class="accordion-header"
                            :id="'flush-heading' + category.id"
                        >
                            <button
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                :data-bs-target="'#flush-' + category.id"
                                aria-expanded="false"
                                :aria-controls="'flush-' + category.id"
                            >
                                <i class="bi bi-bookmark-fill"></i>
                                {{ category.title }}
                            </button>
                        </h2>
                        <div
                            :id="'flush-' + category.id"
                            class="accordion-collapse collapse"
                            :aria-labelledby="'flush-heading' + category.id"
                            data-bs-parent="#accordionCategories"
                        >
                            <div class="accordion-body p-0">
                                <ul class="p-2" v-if="category.categories">
                                    <li
                                        v-if="category.categories"
                                        v-for="subcategory in category.categories"
                                        :key="subcategory.id"
                                        class="list-group-item"
                                    >
                                        <a
                                            href=""
                                            class="btn d-flex align-items-center w-100"
                                        >
                                            <i class="bi bi-bookmark"></i>
                                            {{ subcategory.title }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Cookie from "js-cookie";

export default {
    data() {
        return {
            targets: [],
            categories: [],
            access_token: "",
        };
    },

    created() {
        this.access_token = Cookie.get("access_token");
        this.fetchTargets();
        this.fetchCategories();
    },

    methods: {
        fetchTargets() {
            let params = "";

            fetch(`http://localhost:8000/api/target/${params}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.error) {
                        this.targets = data;
                    } else {
                        alert(data.error);
                    }
                });
        },

        fetchCategories() {
            fetch("http://localhost:8000/api/category/categories", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.error) {
                        this.categories = data;
                    } else {
                        alert(data.error);
                    }
                });
        },
    },
};
</script>
