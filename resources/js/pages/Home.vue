<template>
    <div class="row flex-md-row flex-column-reverse">
        <Loader v-show="!loaded"></Loader>
        <div class="col-md-9">
            <div class="row">
                <form class="col d-flex" role="search">
                    <input
                        class="form-control me-2"
                        type="search"
                        :placeholder="$t('home.search')"
                        aria-label="Search"
                    />
                    <button class="btn btn-outline-success" type="submit">
                        {{ $t("home.search") }}
                    </button>
                </form>
            </div>
            <table class="table table-light">
                <thead>
                    <tr>
                        <th @click="changeField('title')" class="pointer">
                            {{ $t("home.target") }} <i v-show="field == 'title'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('cost')" class="pointer text-center">
                            {{ $t("home.cost") }} <i v-show="field == 'cost'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('gain')" class="pointer text-center d-none d-md-block">
                            {{ $t("home.gain") }} <i v-show="field == 'gain'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('priority')" class="pointer text-center">
                            {{ $t("home.priority") }} <i v-show="field == 'priority'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('deadline')" class="pointer text-center d-none d-md-block">
                            {{ $t("home.deadline") }} <i v-show="field == 'deadline'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                        <th @click="changeField('urgency')" class="pointer text-center">
                            {{ $t("home.urgency") }} <i v-show="field == 'urgency'" :class="['bi', order ? 'bi-caret-up-fill' : 'bi-caret-down-fill']"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-if="targets.length"
                        v-for="target in targets"
                        :key="target.id"
                    >
                        <th scope="row">
                            <!-- {{ target.title }} -->
                            <router-link
                            :to="'target/' + target.id"
                            class="text-black text-decoration-none"
                            exact
                            >
                                {{ target.title }}
                            </router-link>
                        </th>
                        <td class="text-center">{{ target.cost }}</td>
                        <td class="text-center d-none d-md-block">{{ target.gain }}</td>
                        <td class="text-center">{{ target.priority }}</td>
                        <td class="text-center d-none d-md-block">{{ target.deadline }}</td>
                        <td class="text-center">
                            {{ target.urgency }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="card mb-2">
                <div class="card-header pointer" @click="fetchTargets">{{ $t("home.categories") }}</div>
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
                                            :class="['btn list-group-btn d-flex align-items-center w-100', {'bg-primary text-light': selected_subcategory == subcategory.id}]"
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
import Loader from "../components/Loader.vue";
import Api from "../mixins/Api.vue";
import Utils from "../mixins/Utils.vue";

export default {
    data() {
        return {
            loaded: false,

            targets: [],
            categories: [],

            selected_subcategory: "",

            order: false,
            field: "title",
        };
    },

    created() {
        this.access_token = Cookie.get("access_token");
        this.fetchTargets();
        this.fetchCategories();
    },

    mounted() {
        this.loaded = true;
    },

    methods: {
        fetchTargets() {
            this.loaded = false;
            let params = "";
            this.selected_subcategory = '';

            this.get(`target/${params}`, (data) => {
                this.loaded = true;
                if (!data.error) {
                    data.map(target => {
                        target.urgency = this.getUrgency(target);
                    })

                    this.targets = data;

                    this.targets.sort(
                        this.compareValues(this.field, this.order)
                    );
                } else {
                    alert(data.error);
                }
            }, null, true)
        },

        reloadTargets(categotyid) {
            this.loaded = false;
            this.selected_subcategory = categotyid;

            this.get(`category/${categotyid}/targets`,(data) => {
                this.loaded = true;

                this.targets = data;
                this.targets.sort(
                    this.compareValues(this.field, this.order)
                );
            }, null, true)
        },

        fetchCategories() {
            this.loaded = false;
            this.get('category/categories', data => {
                this.categories = data;
                this.loaded = true;
            }, null, true)

        },

        changeField(field) {
            this.field == field ?
                this.order = !this.order :
                this.field = field;
            this.targets.sort(
                this.compareValues(this.field, this.order)
            );
        }
    },

    components: {Loader},

    mixins: [Api, Utils]
};

</script>
