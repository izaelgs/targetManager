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
    </div>
</template>

<script>
import Cookie from "js-cookie";

export default {
    data() {
        return {
            target: {},
            token: "",
        };
    },

    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.getContent();
            }
        );
        this.token = Cookie.get("access_token");

        this.getContent();
    },

    methods: {
        getContent() {
            fetch("http://localhost:8000/api/target/" + this.$route.params.id, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.error) {
                        this.target = data;
                    } else {
                        alert(data.error);
                    }
                });
        },
    },
};
</script>
