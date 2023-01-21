<template>
    <div class="text-center">
        <Loader v-show="!loaded"></Loader>
        <div class="p-1 rounded position-absolute top-0 end-0 mx-4 bg-light opacity-75" style="z-index: 10;">
            <button @click="editFields()" class="btn px-2">
                <i :class="['bi', edit ? 'bi-x-lg' :  'bi-pencil']"></i>
            </button>
            <button @click="remove()" class="btn px-2 text-danger"><i class="bi bi-trash"></i></button>
        </div>
        <!-- Editar Informações-->
        <form @submit.stop.prevent="submitTarget" v-show="edit" class="row g-3 text-start">
                <div class="col-12">
                    <input
                        type="name"
                        class="form-control"
                        id="title"
                        v-model="target.title"
                        placeholder="insira um titulo"
                    />
                </div>
                <div class="col-12">
                    <textarea
                        class="form-control"
                        id="description"
                        v-model="target.description"
                        rows="3"
                    ></textarea>
                </div>

                <!-- Categorias -->
                <div class="col-md-6">
                    <label for="categories" class="form-label">
                        Categoria:
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
                        <option value="" hidden>Seleciona Uma Categoria</option>
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
                <!-- Subcategorias -->
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
                        <option value="" hidden>Seleciona Uma Categoria</option>
                        <option
                            v-if="subcategories"
                            v-for="category in subcategories"
                            :value="category.id"
                            :disabled="category.disabled"
                        >
                            {{ category.title }}
                        </option>
                    </select>
                </div>

                <div class="col-6">
                    <label for="deadline" class="form-label">Prazo</label>
                    <input
                        type="date"
                        class="form-control"
                        id="deadline"
                        v-model="target.deadline"
                    />
                </div>
                <div class="col-6">
                    <label for="priority" class="form-label">Prioridade</label>
                    <input
                        type="number"
                        class="form-control"
                        id="priority"
                        v-model="target.priority"
                        min="0"
                        max="10"
                    />
                </div>
                <div class="col-6">
                    <label for="cost" class="form-label">Custo</label>
                    <input
                        type="number"
                        class="form-control"
                        id="cost"
                        v-model="target.cost"
                        min="0"
                        max="10"
                    />
                </div>
                <div class="col-6">
                    <label for="gain" class="form-label">Ganho</label>
                    <input
                        type="number"
                        class="form-control"
                        id="gain"
                        v-model="target.gain"
                        min="0"
                        max="10"
                    />
                </div>
                <div class="col-12 d-grid gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        Salvar Alterações
                    </button>
                </div>
        </form>

        <!-- Informações -->
        <div v-show="!edit">
            <h2>{{ target.title }}</h2>
            <div>
                <span
                    v-for="category in target.categories"
                    class="badge bg-primary"
                >
                    {{ category.title }}
                </span>
            </div>
            <!-- Atributos -->
            <div>
                <span class="badge text-bg-secondary">
                    {{ $t("home.cost") }}: {{ target.cost }}
                </span>
                <span class="badge text-bg-secondary">
                    {{ $t("home.gain") }}: {{ target.gain }}
                </span>
                <span class="badge text-bg-secondary">
                    {{ $t("home.priority") }}: {{ target.priority }}
                </span>
                <span class="badge text-bg-secondary">
                    {{ $t("home.deadline") }}: {{ target.deadline }}
                </span>
                <span class="badge text-bg-secondary">
                    {{ $t("home.urgency") }}: {{ target.urgency }}
                </span>
                <span class="badge text-bg-secondary">
                    Status: {{ target.status }}
                </span>
            </div>
            <p>{{ target.description }}</p>
        </div>

        <!-- Etapas-->
        <div class="list-group text-start">
            <div>
                {{ $t("stage.completed") }}: {{ stagesPercentual.text }}
                <div class="progress" role="progressbar" aria-label="Progresso" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" :style="`width: ${stagesPercentual.number}%`"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mt-2">
                    <label for="orderby">{{ $t("filter.orderby") }}:</label>
                    <select @change="changeField()" v-model="field" id="orderby" class="form-select" aria-label="orderby">
                        <option value="created_at">{{ $t("filter.created") }}</option>
                        <option value="complexity">{{ $t("stage.complexity") }}</option>
                        <option value="tolerance">{{ $t("stage.tolerance") }}</option>
                        <option value="deadline">{{ $t("stage.deadline") }}</option>
                    </select>
                </div>
                <div class="col-6 mt-2">
                    <label for="order">{{ $t("filter.order") }}:</label>
                    <select @change="changeField()" v-model="order" id="order" class="form-select" aria-label="order">
                        <option value="false">{{ $t("filter.ascending") }}</option>
                        <option value="true">{{ $t("filter.descending") }}</option>
                    </select>
                </div>
            </div>
            <hr>
            <form
                v-for="stage in editableStages"
                @submit.stop.prevent="submitEdit(stage)"
                class="list-group-item card bg-secondary text-light my-2 d-flex flex-row align-items-center"
            >
                <input
                    v-model="stage.status"
                    @change="checkStage(stage)"
                    class="form-check-input me-1 pointer"
                    type="checkbox"
                />
                <div class="vr me-2"></div>
                <div class="w-100">
                    <div class="d-flex w-100 justify-content-between">
                        <input
                            v-show="stage.edit"
                            v-model="stage.title"
                            type="text"
                            name="title"
                            class="form-control mt-3"
                        />
                        <h5 v-show="!stage.edit" class="mb-1">
                            {{ stage.title }}
                            <button
                                @click.prevent="edit_stage(stage)"
                                class="btn text-light px-2"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button
                                @click.prevent="delete_stage(stage)"
                                class="btn text-light px-2"
                            >
                                <i class="bi bi-trash"></i>
                            </button>
                        </h5>
                        <small v-show="!stage.edit">{{ stage.deadline }}</small>
                        <button
                            v-show="stage.edit"
                            @click.prevent="edit_stage(stage)"
                            class="btn text-light px-2 mt-3"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div v-show="stage.edit" class="row">
                        <div class="col-12">
                            <textarea
                                v-show="stage.edit"
                                v-model="stage.description"
                                name="description"
                                class="form-control my-2"
                                cols="30"
                                rows="5"
                            ></textarea>
                        </div>
                        <div class="col col-md-4">
                            <label for="deadline" class="mt-1">{{ $t("stage.deadline") }}:</label>
                            <input
                                v-model="stage.deadline"
                                type="date"
                                class="form-control mb-1"
                                id="deadline"
                            />
                        </div>
                        <div class="col-6 col-md-4">
                            <label for="complexity" class="mt-1">{{ $t("stage.complexity") }}:</label>
                            <input
                                v-model="stage.complexity"
                                type="number"
                                class="form-control mb-1"
                                id="complexity"
                            />
                        </div>
                        <div class="col-6 col-md-4">
                            <label for="tolerance" class="mt-1">{{ $t("stage.tolerance") }}:</label>
                            <input
                                v-model="stage.tolerance"
                                type="number"
                                class="form-control mb-1"
                                id="tolerance"
                            />
                        </div>
                        <div class="d-grid gap-2 mt-2">
                            <button class="btn btn-primary" type="submit">
                                {{ $t("forms.save") }}
                            </button>
                        </div>
                    </div>
                    <span v-show="!stage.edit" class="badge text-bg-primary">
                        {{ $t("stage.complexity") }}: {{ stage.complexity }}
                    </span>
                    <span v-show="!stage.edit" class="badge text-bg-primary">
                        {{ $t("stage.tolerance") }}: {{ stage.tolerance }}
                    </span>
                    <br />
                    <small v-show="!stage.edit">{{ stage.description }}</small>
                </div>
            </form>

            <!-- Adicionar Etapa -->
            <form
                @submit.stop.prevent="submit"
                class="list-group-item card bg-secondary text-light my-2 p-3"
            >
                <div class="w-100">
                    <h2 class="accordion-header" id="headingOne">
                        <button
                            class="accordion-button"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#new-stage"
                            aria-expanded="true"
                            aria-controls="new-stage"
                        >
                            <i class="bi bi-journal-plus fs-5 me-2"></i> {{ $t("stage.add_step") }}
                        </button>
                    </h2>
                    <div class="row collapse" id="new-stage">
                        <div class="col-12">
                            <label for="title" class="mt-1">{{ $t("stage.title") }}:</label>
                            <input
                                v-model="title"
                                type="text"
                                id="title"
                                class="form-control"
                            />
                        </div>
                        <div class="col-12">
                            <label for="description" class="mt-1">{{ $t("stage.description") }}:</label>
                            <textarea
                                v-model="description"
                                id="description"
                                class="form-control my-2"
                                cols="30"
                                rows="5"
                            >
                            </textarea>
                        </div>
                        <div class="col col-md-4">
                            <label for="deadline" class="mt-1">{{ $t("stage.deadline") }}:</label>
                            <input
                                v-model="deadline"
                                type="date"
                                class="form-control mb-1"
                                id="deadline"
                            />
                        </div>
                        <div class="col-6 col-md-4">
                            <label for="complexity" class="mt-1">{{ $t("stage.complexity") }}:</label>
                            <input
                                v-model="complexity"
                                type="number"
                                class="form-control mb-1"
                                id="complexity"
                            />
                        </div>
                        <div class="col-6 col-md-4">
                            <label for="tolerance" class="mt-1">{{ $t("stage.tolerance") }}:</label>
                            <input
                                v-model="tolerance"
                                type="number"
                                class="form-control mb-1"
                                id="tolerance"
                            />
                        </div>
                        <div class="d-grid gap-2 mt-2">
                            <button class="btn btn-primary" type="submit">
                                {{ $t("forms.save") }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Cookie from "js-cookie";
import appendToast from "../mixins/appendToast.vue";
import Api from "../mixins/Api.vue";
import Loader from "../components/Loader.vue";
import Utils from "../mixins/Utils.vue";

export default {
    data() {
        return {
            loaded: false,
            edit: false,
            target: {},

            categories: [],
            subcategories: [],

            selected_categories: [],
            selected_category: "",

            selected_subcategories: [],
            selected_subcategory: "",

            title: "",
            description: "",
            deadline: "",
            complexity: "",
            tolerance: "",

            order: false,
            field: "deadline"
        };
    },

    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.getTarget();
                this.edit = false;
                this.fetchCategories();
            }
        );
        this.access_token = Cookie.get("access_token");

        this.getTarget();
        this.fetchCategories();
    },

    computed: {
        editableStages() {
            return this.target.stages;
        },

        stagesCompleted() {
            return this.target.stages.filter(stage => stage.status);
        },

        stagesPercentual() {
            let data = {
                text: this.target.stages ? `${this.stagesCompleted.length}/${this.target.stages.length}` : 0,
                number: this.target.stages ? (this.stagesCompleted.length / this.target.stages.length) * 100 : 100
            };

            return data;
        }
    },

    methods: {

        remove() {
            this.loaded = false;
            this.del("target/" + this.target.id, data => {
                this.loaded = true;

                const index = this.$store.state.targets.indexOf(this.$store.state.targets.find(target => {
                    return target.id == this.target.id
                }));

                if (index > -1) {
                    this.$store.state.targets.splice(index, 1); // 2nd parameter means remove one item only
                }

                this.$router.push('/');
            }, error => {
                this.loaded = true;
            });
        },

        edit_stage(stage) {
            stage.edit = !stage.edit;
        },

        editFields() {
            this.edit = !this.edit;
        },

        checkStage(stage) {
            const payload = {
                status: stage.status ? 1 : 0,
            };

            this.patch("stage/" + stage.id, payload, data => {
                let emojis = stage.status
                    ? [
                            "arrow-through-heart-fill",
                            "calendar-check-fill",
                            "check-circle-fill",
                            "clipboard-heart-fill",
                            "emoji-kiss-fill",
                            "emoji-smile-fill",
                            "emoji-wink-fill",
                            "hand-thumbs-up-fill",
                            "heart-fill",
                            "hearts",
                            "stars",
                            "emoji-heart-eyes-fill",
                            "emoji-laughing-fill",
                            "emoji-sunglasses-fill",
                        ]
                    : [
                            "emoji-dizzy-fill",
                            "emoji-expressionless-fill",
                            "emoji-frown-fill",
                            "heartbreak-fill",
                        ];

                this.showToast(
                    data.message,
                    "success",
                    `${this.getEmoji(emojis)} text-body fs-5`
                );
            }, null, true);
        },

        submit() {
            const payload = {
                title: this.title,
                description: this.description,
                target_id: this.target.id,
                tolerance: this.tolerance,
                deadline: this.deadline,
                complexity: this.complexity,
            };

            this.post("stage", payload, data => {
                let emojis = [
                    "check-circle-fill",
                    "emoji-laughing-fill",
                    "emoji-sunglasses-fill",
                    "check2-circle-fill",
                ];
                this.title = this.description = this.tolerance = this.deadline = this.complexity = '';
                this.getTarget();
            }, error => {
                this.loaded = true;
            })

        },

        submitEdit(stage) {
            this.loaded = false;
            const payload = {
                title: stage.title,
                description: stage.description,
                target_id: stage.target_id,
                tolerance: stage.tolerance,
                deadline: stage.deadline,
                complexity: stage.complexity,
                stageid: stage.stageid,
            };

            this.put("stage/" + stage.id, payload, data => {
                this.getTarget();
            }, error => {
                this.loaded = true;
            })
        },

        delete_stage(stage) {

            this.del("stage/" + stage.id, data => {
                const index = this.target.stages.indexOf(stage);
                if (index > -1) {
                    // only splice array when item is found
                    this.target.stages.splice(index, 1); // 2nd parameter means remove one item only
                }
            });
        },

        getTarget() {
            this.loaded = false;
            this.get("target/" + this.$route.params.id, data => {

                if(!data.stages) this.$router.push({name: 'notfound'});

                data.urgency = this.getUrgency(data);

                data.stages = data.stages.map((stage) => {
                    stage.edit = false;
                    return stage;
                });
                this.loaded = true;
                this.target = data;
                this.changeField();
            }, error => {
                this.loaded = true;
            }, true);
        },

        submitTarget() {
            this.loaded = false;
            this.selected_categories = this.selected_categories.concat(this.selected_subcategories);

            const payload = {
                title: this.target.title,
                deadline: this.target.deadline,
                description: this.target.description,
                cost: this.target.cost,
                gain: this.target.gain,
                priority: this.target.priority,
                categories: this.selected_categories.map((category) => {
                    return category.id;
                }),
            };

            this.put('target/' + this.target.id, payload, (data) => {
                this.target.categories = this.selected_categories;
                this.edit = false;
                this.loaded = true;
                this.selected_categories = this.selected_categories.filter(category => category.is_father)

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

                    data.forEach(category => {
                        if(this.target.categories.find(
                            (c) => c.id == category.id
                        )){
                            this.selected_categories.push(category);

                            category.categories.forEach(subcategory => {
                                if(this.target.categories.find(
                                    (c) => c.id == subcategory.id
                                )){
                                    this.selected_subcategories.push(subcategory);
                                    subcategory.disabled = "disabled";
                                }
                            })

                            this.subcategories = this.subcategories.concat(category.categories)
                            category.disabled = "disabled";
                        }
                    })

                    this.categories = data;
                } else {
                    alert(data.error);
                }
            }, null, true)
        },

        changeField(field = this.field) {
            // this.field == field ?
            //     this.order = !this.order :
            //     this.field = field;
            this.target.stages.sort(
                this.compareValues(this.field, this.order)
            );
        }
    },

    components: {Loader},

    mixins: [appendToast, Api, Utils],
};
</script>
