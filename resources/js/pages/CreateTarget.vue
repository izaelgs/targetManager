<template>
    <div>
        <Loader v-show="!loaded"></Loader>
        <section class="rounded">
            <h2>{{ $t("createTarget.createTarget") }}</h2>
            <p>
                {{ $t("createTarget.subtitle") }}
            </p>

            <form @submit.stop.prevent="submit" class="row g-3">
                <div class="col-12">
                    <label for="title" class="form-label">{{ $t("createTarget.title") }}</label>
                    <input
                        type="name"
                        class="form-control"
                        id="title"
                        v-model="title"
                    />
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">{{ $t("createTarget.description") }}</label>
                    <textarea
                        class="form-control"
                        id="description"
                        v-model="description"
                        rows="3"
                    ></textarea>
                </div>
                <div class="col-md-6">
                    <label for="categories" class="form-label">
                        {{ $t("createTarget.category") }}:
                        <span
                            v-if="selected_categories"
                            v-for="category in selected_categories"
                            class="badge bg-secondary mx-1"
                        >
                            {{ category.title }}
                            <i
                                @click="removeCategory(category, categories, selected_categories)"
                                class="bi bi-x-lg pointer"
                            ></i>
                        </span>
                    </label>
                    <select
                        id="categories"
                        @change="addCategory(categories , selected_categories, selected_category)"
                        v-model="selected_category"
                        class="form-select"
                    >
                        <option value="" hidden>{{ $t("createTarget.select_category") }}</option>
                        <option
                            v-if="categories"
                            v-for="category in categories"
                            :value="category.id"
                            :key="category.id"
                            :disabled="category.disabled"
                        >
                            {{ category.title }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="categories" class="form-label">
                        {{ $t("createTarget.sub_category") }}:
                        <span
                            v-for="category in selected_subcategories"
                            class="badge bg-secondary mx-1"
                        >
                            {{ category.title }}
                            <i
                                @click="removeCategory(category, subcategories, selected_subcategories)"
                                class="bi bi-x-lg pointer"
                            ></i>
                        </span>
                    </label>
                    <select
                        id="categories"
                        @change="addCategory(subcategories , selected_subcategories, selected_subcategory)"
                        v-model="selected_subcategory"
                        class="form-select"
                    >
                        <option value="" hidden>{{ $t("createTarget.select_category") }}</option>
                        <option
                            v-for="category in subcategories"
                            :value="category.id"
                            :key="category.id"
                            :disabled="category.disabled"
                        >
                            {{ category.title }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="deadline" class="form-label">{{ $t("home.deadline") }}</label>
                    <input
                        type="date"
                        class="form-control"
                        id="deadline"
                        v-model="deadline"
                    />
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">{{ $t("home.priority") }}</label>
                    <input
                        type="number"
                        class="form-control"
                        id="priority"
                        v-model="priority"
                        min="1"
                        max="10"
                    />
                </div>
                <div class="col-md-6">
                    <label for="cost" class="form-label">{{ $t("home.cost") }}</label>
                    <input
                        type="number"
                        class="form-control"
                        id="cost"
                        v-model="cost"
                        min="1"
                        max="10"
                    />
                </div>
                <div class="col-md-6">
                    <label for="gain" class="form-label">{{ $t("home.gain") }}</label>
                    <input
                        type="number"
                        class="form-control"
                        id="gain"
                        v-model="gain"
                        min="1"
                        max="10"
                    />
                </div>
                <div class="col-12 d-grid gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        {{ $t("categories.create") }}
                    </button>
                </div>
            </form>
        </section>
    </div>
</template>

<script>

import Cookie from "js-cookie";
import AppendToast from "../mixins/appendToast.vue";
import Loader from "../components/Loader.vue";
import Api from "../mixins/Api.vue";

export default {
    data() {
        return {
            loaded: false,

            categories: [],
            subcategories: [],

            selected_categories: [],
            selected_category: "",

            selected_subcategories: [],
            selected_subcategory: "",

            title: "",
            deadline: "",
            description: "",
            cost: 1,
            gain: 1,
            priority: 1,
        };
    },

    created() {
        this.access_token = Cookie.get("access_token");
        this.fetchCategories();

    },

    methods: {
        submit() {
            this.loaded = false;
            this.selected_categories = this.selected_categories.concat(this.selected_subcategories);

            const payload = {
                title: this.title,
                deadline: this.deadline,
                description: this.description,
                cost: this.cost,
                gain: this.gain,
                priority: this.priority,
                categories: this.selected_categories.map((category) => {
                    return category.id;
                }),
            };

            this.post('target', payload, (data) => {
                this.loaded = true;

                this.$store.state.targets.push(data.data);

                this.selected_categories = this.selected_subcategories = [];
                this.title = this.deadline = this.description = this.cost = this.gain = this.priority = "";

            }, error => {
                this.loaded = true;
            });
        },

        addCategory(categories , selected_categories, selected_category) {
            let category = categories.find(
                (c) => c.id == selected_category
            );
            selected_categories.push(category);

            if(category.is_father) this.subcategories = this.subcategories.concat(category.categories)

            categories.find(
                (c) => c.id == selected_category
            ).disabled = "disabled";
        },

        removeCategory(category, categories, selected_categories) {
            const index = selected_categories.indexOf(category);
            if (index > -1) { // only splice array when item is found
                selected_categories.splice(index, 1); // 2nd parameter means remove one item only
            }

            if(category.is_father) { // add subcategories on subcategories input
                this.subcategories = this.subcategories.filter(subcategory => !category.categories.includes(subcategory));
                this.selected_subcategories = this.selected_subcategories.filter(subcategory => !category.categories.includes(subcategory))
                category.categories.forEach(subcategory => {
                    subcategory.disabled = null;
                });
            }

            categories.find(
                (c) => c.id == category.id
            ).disabled = null;
        },

        fetchCategories() {
            this.get('category/categories', (data) => {
                this.loaded = true;
                if (!data.error) {
                    this.categories = data;
                } else {
                    alert(data.error);
                }
            }, null, true)
        }
    },

    components: {Loader},

    mixins: [AppendToast, Api]
};

</script>
