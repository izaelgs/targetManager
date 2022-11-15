<template>
    <div class="text-center">
        <h2>{{ target.title }}</h2>
        <div>
            <span
                v-for="category in target.categories"
                class="badge bg-primary"
            >
                {{ category.title }}
            </span>
        </div>
        <div>
            <span class="badge text-bg-secondary">
                Custo: {{ target.cost }}
            </span>
            <span class="badge text-bg-secondary">
                Retorno: {{ target.gain }}
            </span>
            <span class="badge text-bg-secondary">
                Prioridade: {{ target.priority }}
            </span>
            <span class="badge text-bg-secondary">
                Prazo: {{ target.deadline }}
            </span>
            <span class="badge text-bg-secondary">
                Urgência: {{ target.sugested_priority }}
            </span>
            <span class="badge text-bg-secondary">
                Status: {{ target.status }}
            </span>
        </div>
        <p>{{ target.description }}</p>
        <div class="list-group text-start">
            <form
                v-for="stage in editableStages"
                @submit.stop.prevent="submitEdit(stage)"
                class="list-group-item card bg-secondary text-light my-2 d-flex flex-row align-items-center"
            >
                <input
                    class="form-check-input me-1 pointer"
                    type="checkbox"
                    value=""
                />
                <div class="vr me-2"></div>
                <div class="w-100">
                    <div class="d-flex w-100 justify-content-between">
                        <input
                            v-show="stage.edit"
                            v-model="stage.title"
                            type="text"
                            name="title"
                            class="form-control"
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
                            class="btn text-light px-2"
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
                        <div class="col">
                            <input
                                v-model="stage.deadline"
                                type="date"
                                class="form-control my-1"
                            />
                        </div>
                        <div class="col-6">
                            <input
                                v-model="stage.complexity"
                                type="number"
                                class="form-control my-1"
                            />
                        </div>
                        <div class="col-6">
                            <input
                                v-model="stage.tolerance"
                                type="number"
                                class="form-control my-1"
                            />
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                Salvar
                            </button>
                        </div>
                    </div>
                    <span v-show="!stage.edit" class="badge text-bg-primary">
                        Complexidade: {{ stage.complexity }}
                    </span>
                    <span v-show="!stage.edit" class="badge text-bg-primary">
                        Tolerância: {{ stage.tolerance }}
                    </span>
                    <br />
                    <small v-show="!stage.edit">{{ stage.description }}</small>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Cookie from "js-cookie";
import appendToast from "../mixins/appendToast.vue";

export default {
    data() {
        return {
            target: {},
            access_token: "",
        };
    },

    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.fetchData();
            }
        );
        this.access_token = Cookie.get("access_token");

        this.fetchData();
    },

    computed: {
        editableStages() {
            console.log(this.target.stages);
            return this.target.stages;
        },
    },

    methods: {
        edit_stage(stage) {
            stage.edit = !stage.edit;
        },

        submitEdit(stage) {
            const payload = {
                title: stage.title,
                description: stage.description,
                target_id: stage.target_id,
                tolerance: stage.tolerance,
                deadline: stage.deadline,
                complexity: stage.complexity,
                stageid: stage.stageid,
            };

            fetch("http://127.0.0.1:8000/api/stage/" + stage.id, {
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
                        this.appendToast(data.message, "success").then(
                            (element) => {
                                const toast = new bootstrap.Toast(element);
                                toast.show();
                            }
                        );

                        this.fetchData();
                    } else {
                        this.appendToast(data.error, "danger").then(
                            (element) => {
                                const toast = new bootstrap.Toast(element);
                                toast.show();
                            }
                        );
                    }
                });
        },

        delete_stage(stage) {
            fetch("http://localhost:8000/api/stage/" + stage.id, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.error) {
                        const index = this.target.stages.indexOf(stage);
                        if (index > -1) {
                            // only splice array when item is found
                            this.target.stages.splice(index, 1); // 2nd parameter means remove one item only
                        }
                    } else {
                        alert(data.error);
                    }
                });
        },

        fetchData() {
            fetch("http://localhost:8000/api/target/" + this.$route.params.id, {
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
                        data.stages = data.stages.map((stage) => {
                            stage.edit = false;
                            return stage;
                        });
                        this.target = data;
                    } else {
                        alert(data.error);
                    }
                });
        },
    },

    mixins: [appendToast]
};
</script>
