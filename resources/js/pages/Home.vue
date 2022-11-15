<template>
    <div class="row flex-md-row flex-column-reverse">
        <div class="col-md-9">
            <div class="row">
                <form class="col d-flex" role="search">
                    <input
                        class="form-control me-2"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                    />
                    <button class="btn btn-outline-success" type="submit">
                        Search
                    </button>
                </form>
            </div>
            <table class="table table-light">
                <thead>
                    <tr>
                        <th @click="changeField('title')" class="pointer">
                            Objetivo <i v-show="field == 'title'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('cost')" class="pointer text-center">
                            Custo <i v-show="field == 'cost'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('gain')" class="pointer text-center d-none d-md-block">
                            Ganho <i v-show="field == 'gain'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('priority')" class="pointer text-center">
                            Prioridade <i v-show="field == 'priority'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('deadline')" class="pointer text-center d-none d-md-block">
                            Prazo <i v-show="field == 'deadline'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('sugested_priority')" class="pointer text-center">
                            Urgência <i v-show="field == 'sugested_priority'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
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
                        <td class="text-center d-none d-md-block">{{ target.gain }}</td>
                        <td class="text-center">{{ target.priority }}</td>
                        <td class="text-center d-none d-md-block">{{ target.deadline }}</td>
                        <td class="text-center">
                            {{ target.sugested_priority }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="card mb-2">
                <div class="card-header pointer" @click="fetchTargets">Categoria</div>
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
                                @click="reloadTargets(category.id)"
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
                                            @click.prevent="
                                                reloadTargets(subcategory.id)
                                            "
                                            href=""
                                            :class="['btn d-flex align-items-center w-100', {'bg-primary text-light': selected_subcategory == subcategory.id}]"
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

            selected_subcategory: "",

            order: false,
            field: "title",

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
            this.selected_subcategory = '';

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
                        this.targets.sort(
                            compareValues(this.field, this.order)
                        );
                    } else {
                        alert(data.error);
                    }
                });
        },

        reloadTargets(categotyid) {
            this.selected_subcategory = categotyid;

            fetch(`http://localhost:8000/api/category/${categotyid}/targets`, {
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
                        this.targets.sort(
                            compareValues(this.field, this.order)
                        );
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

        changeField(field) {
            this.field == field ?
                this.order = !this.order :
                this.field = field;
            this.targets.sort(
                compareValues(this.field, this.order)
            );
        }
    },
};

function compareValues(key, order) {
    return function innerSort(a, b) {
        if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
            // property doesn't exist on either object
            return 0;
        }

        const varA = typeof a[key] === "string" ? a[key].toUpperCase() : a[key];
        const varB = typeof b[key] === "string" ? b[key].toUpperCase() : b[key];

        let comparison = 0;
        if (varA > varB) {
            comparison = 1;
        } else if (varA < varB) {
            comparison = -1;
        }
        return order ? comparison * -1 : comparison;
    };
}
</script>
